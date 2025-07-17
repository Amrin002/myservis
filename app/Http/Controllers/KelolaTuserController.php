<?php

namespace App\Http\Controllers;

use App\Models\Tuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class KelolaTuserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tuser::query();

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('skill_category', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori skill
        if ($request->filled('skill_category')) {
            $query->where('skill_category', $request->skill_category);
        }

        // Urutkan berdasarkan parameter atau default created_at desc
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $tusers = $query->paginate(10)->withQueryString();

        // Ambil data untuk filter dropdown
        $skillCategories = Tuser::select('skill_category')
            ->distinct()
            ->whereNotNull('skill_category')
            ->pluck('skill_category');

        return view('kelolatuser.index', compact('tusers', 'skillCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kelolatuser.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tusers',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->isValidIndonesianPhone($value)) {
                        $fail('Format nomor telepon tidak valid. Gunakan format Indonesia (08xxxxxxxxx).');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string',
            'skill_category' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Tuser::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password, // Akan otomatis di-hash oleh mutator
                'status' => $request->status,
                'address' => $request->address,
                'skill_category' => $request->skill_category,
            ]);

            return redirect()->route('kelolatuser.index')
                ->with('success', 'Teknisi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan teknisi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     * Parameter harus sesuai dengan nama route parameter
     */
    public function show($kelolatuser)
    {
        // Cari berdasarkan ID atau slug tergantung implementasi
        $tuser = Tuser::findOrFail($kelolatuser);

        // Load statistik untuk teknisi
        $statistik = $tuser->getDashboardStatistik();

        // Load recent servisan
        $recentServisan = $tuser->servisans()
            ->with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('kelolatuser.show', compact('tuser', 'statistik', 'recentServisan'));
    }

    /**
     * Show the form for editing the specified resource.
     * Parameter harus sesuai dengan nama route parameter
     */
    public function edit($kelolatuser)
    {
        $tuser = Tuser::findOrFail($kelolatuser);
        return view('kelolatuser.edit', compact('tuser'));
    }

    /**
     * Update the specified resource in storage.
     * Parameter harus sesuai dengan nama route parameter
     */
    public function update(Request $request, $kelolatuser)
    {
        // Cari tuser berdasarkan ID
        $tuser = Tuser::findOrFail($kelolatuser);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tusers,email,' . $tuser->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string',
            'skill_category' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
                'address' => $request->address,
                'skill_category' => $request->skill_category,
            ];

            // Update password jika diisi
            if ($request->filled('password')) {
                $updateData['password'] = $request->password;
            }

            $tuser->update($updateData);

            return redirect()->route('kelolatuser.index')
                ->with('success', 'Data teknisi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data teknisi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * Parameter harus sesuai dengan nama route parameter
     */
    public function destroy($kelolatuser)
    {
        $tuser = Tuser::findOrFail($kelolatuser);

        try {
            // Cek apakah teknisi masih memiliki servisan aktif
            $servisanAktif = $tuser->servisans()->whereIn('status', ['pending', 'proses'])->count();

            if ($servisanAktif > 0) {
                return redirect()->route('kelolatuser.index')
                    ->with('error', 'Tidak dapat menghapus teknisi yang masih memiliki servisan aktif!');
            }

            $tuser->delete();

            return redirect()->route('kelolatuser.index')
                ->with('success', 'Teknisi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus teknisi: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status teknisi (active/inactive)
     * Parameter harus sesuai dengan nama route parameter
     */
    public function toggleStatus($kelolatuser)
    {
        $tuser = Tuser::findOrFail($kelolatuser);

        try {
            $newStatus = $tuser->status === 'active' ? 'inactive' : 'active';
            $tuser->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()->back()
                ->with('success', "Status teknisi berhasil {$statusText}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status teknisi: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions untuk multiple teknisi
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'selected_tusers' => 'required|array|min:1',
            'selected_tusers.*' => 'exists:tusers,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $tusers = Tuser::whereIn('id', $request->selected_tusers);
            $count = $tusers->count();

            switch ($request->action) {
                case 'activate':
                    $tusers->update(['status' => 'active']);
                    $message = "{$count} teknisi berhasil diaktifkan!";
                    break;

                case 'deactivate':
                    $tusers->update(['status' => 'inactive']);
                    $message = "{$count} teknisi berhasil dinonaktifkan!";
                    break;

                case 'delete':
                    // Cek apakah ada teknisi yang masih memiliki servisan aktif
                    $servisanAktif = $tusers->withCount(['servisans' => function ($query) {
                        $query->whereIn('status', ['pending', 'proses']);
                    }])->get()->sum('servisans_count');

                    if ($servisanAktif > 0) {
                        return redirect()->back()
                            ->with('error', 'Tidak dapat menghapus teknisi yang masih memiliki servisan aktif!');
                    }

                    $tusers->delete();
                    $message = "{$count} teknisi berhasil dihapus!";
                    break;
            }

            return redirect()->back()
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat melakukan aksi: ' . $e->getMessage());
        }
    }
    /**
     * Normalize phone number to consistent format
     */
    private function normalizePhoneNumber($phone)
    {
        if (!$phone) return '';

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Ensure it starts with 0 for local Indonesian format
        if (!empty($phone) && !str_starts_with($phone, '0')) {
            $phone = '0' . $phone;
        }

        return $phone;
    }

    /**
     * Validate Indonesian phone number format
     */
    private function isValidIndonesianPhone($phone)
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Check if it's a valid Indonesian number
        // Format: 628xxxxxxxxx (11-13 digits total)
        // Or: 08xxxxxxxxx (10-12 digits total)

        if (substr($phone, 0, 3) === '628') {
            // International format: should be 11-13 digits
            return strlen($phone) >= 11 && strlen($phone) <= 13;
        } elseif (substr($phone, 0, 2) === '08') {
            // Local format: should be 10-12 digits
            return strlen($phone) >= 10 && strlen($phone) <= 12;
        } elseif (substr($phone, 0, 1) === '8') {
            // Missing zero (8xxx): should be 9-11 digits
            return strlen($phone) >= 9 && strlen($phone) <= 11;
        }

        return false;
    }
}
