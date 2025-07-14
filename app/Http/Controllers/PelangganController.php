<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pelanggan::withCount('servisans');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['nama', 'no_hp', 'created_at', 'servisans_count'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $pelanggans = $query->paginate(15);

        return view('pelanggan.index', compact('pelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20|unique:pelanggans,no_hp',
            'alamat' => 'nullable|string|max:500'
        ], [
            'nama.required' => 'Nama pelanggan harus diisi',
            'nama.max' => 'Nama pelanggan maksimal 255 karakter',
            'no_hp.required' => 'Nomor HP harus diisi',
            'no_hp.unique' => 'Nomor HP sudah terdaftar',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter',
            'alamat.max' => 'Alamat maksimal 500 karakter'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $pelanggan = Pelanggan::create([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pelanggan berhasil ditambahkan',
                    'data' => $pelanggan->load('servisans')
                ]);
            }

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan pelanggan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['servisans' => function ($query) {
            $query->latest();
        }]);

        return view('pelanggan.show', compact('pelanggan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20|unique:pelanggans,no_hp,' . $pelanggan->id,
            'alamat' => 'nullable|string|max:500'
        ], [
            'nama.required' => 'Nama pelanggan harus diisi',
            'nama.max' => 'Nama pelanggan maksimal 255 karakter',
            'no_hp.required' => 'Nomor HP harus diisi',
            'no_hp.unique' => 'Nomor HP sudah terdaftar',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter',
            'alamat.max' => 'Alamat maksimal 500 karakter'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $pelanggan->update([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pelanggan berhasil diperbarui',
                    'data' => $pelanggan->fresh()->load('servisans')
                ]);
            }

            return redirect()->route('pelanggan.show', $pelanggan->id)
                ->with('success', 'Data pelanggan berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui data pelanggan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal memperbarui data pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        try {
            // Check if pelanggan has active servisans
            $activeServisans = $pelanggan->servisans()
                ->whereIn('status', ['menunggu', 'proses'])
                ->count();

            if ($activeServisans > 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menghapus pelanggan yang masih memiliki servisan aktif'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus pelanggan yang masih memiliki servisan aktif');
            }

            $nama = $pelanggan->nama;
            $pelanggan->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pelanggan ' . $nama . ' berhasil dihapus'
                ]);
            }

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan ' . $nama . ' berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus pelanggan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }


    /**
     * Get pelanggan data for AJAX requests
     */
    public function getPelanggan(Pelanggan $pelanggan)
    {
        return response()->json([
            'success' => true,
            'data' => $pelanggan->load('servisans')
        ]);
    }

    /**
     * Search pelanggan for autocomplete
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');

        $pelanggans = Pelanggan::where('nama', 'like', "%{$search}%")
            ->orWhere('no_hp', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nama', 'no_hp', 'alamat']);

        return response()->json([
            'success' => true,
            'data' => $pelanggans
        ]);
    }

    /**
     * Export pelanggan data
     */
    public function export(Request $request)
    {
        $pelanggans = Pelanggan::withCount('servisans')
            ->when($request->search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->get();

        $filename = 'data-pelanggan-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($pelanggans) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, ['No', 'Nama', 'No HP', 'Alamat', 'Total Servis', 'Terdaftar']);

            // CSV Data
            foreach ($pelanggans as $index => $pelanggan) {
                fputcsv($file, [
                    $index + 1,
                    $pelanggan->nama,
                    $pelanggan->no_hp,
                    $pelanggan->alamat ?: '-',
                    $pelanggan->servisans_count,
                    $pelanggan->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Normalize phone number to consistent format
     */
    private function normalizePhoneNumber($phone)
    {
        if (!$phone) return '';

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Handle different input formats
        if (substr($phone, 0, 3) === '628') {
            // Already in international format (628xxx)
            return $phone;
        } elseif (substr($phone, 0, 2) === '08') {
            // Local format (08xxx) -> convert to international (628xxx)
            return '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 1) === '8') {
            // Missing zero (8xxx) -> convert to international (628xxx)
            return '62' . $phone;
        } elseif (substr($phone, 0, 2) === '62') {
            // International with country code but might be missing 8
            if (substr($phone, 0, 3) !== '628') {
                // Might be 6281xxx instead of 628xxx, fix it
                return '628' . substr($phone, 2);
            }
            return $phone;
        } else {
            // Unknown format, assume it's local without 0 prefix
            return '628' . $phone;
        }
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
        }

        return false;
    }
}
