<?php

namespace App\Http\Controllers;

use App\Models\Servisan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ServisanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servisans = Servisan::with('pelanggan')
            ->latest()
            ->paginate(10);

        return view('servisan.index', compact('servisans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servisan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string|max:500',
            'tipe_barang' => 'required|string|max:100',
            'merk_barang' => 'required|string|max:100',
            'model_barang' => 'nullable|string|max:100',
            'kerusakan' => 'required|string',
            'aksesoris' => 'nullable|string',
            'estimasi_biaya' => 'required|numeric|min:0',
            'dp' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:menunggu,proses,selesai,diambil,dibatalkan',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // 1. Cari atau buat pelanggan
            $pelanggan = Pelanggan::findOrCreatePelanggan(
                $request->nama_pelanggan,
                $request->no_hp,
                $request->alamat
            );

            // 2. Buat data servisan
            $servisanData = [
                'pelanggan_id' => $pelanggan->id,
                'tipe_barang' => $request->tipe_barang,
                'merk_barang' => $request->merk_barang,
                'model_barang' => $request->model_barang,
                'kerusakan' => $request->kerusakan,
                'aksesoris' => $request->aksesoris,
                'estimasi_biaya' => $request->estimasi_biaya,
                'dp' => $request->dp ?? 0,
                'status' => $request->status,
                'tanggal_masuk' => now(),
                'lunas' => false
            ];

            $servisan = Servisan::create($servisanData);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data servis berhasil ditambahkan dengan kode: ' . $servisan->kode_servis,
                    'data' => $servisan->load('pelanggan')
                ]);
            }

            return redirect()->route('servisan.index')
                ->with('success', 'Data servis berhasil ditambahkan dengan kode: ' . $servisan->kode_servis);
        } catch (\Exception $e) {
            DB::rollback();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menambahkan data servis: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menambahkan data servis: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Servisan $servisan)
    {
        $servisan->load('pelanggan');
        return view('servisan.show', compact('servisan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servisan $servisan)
    {
        $servisan->load('pelanggan');
        return view('servisan.edit', compact('servisan'));
    }

    /**
     * Get a specific resource for editing in modal.
     */
    public function getServisan($id)
    {
        $servisan = Servisan::with('pelanggan')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $servisan->id,
                'kode_servis' => $servisan->kode_servis,
                'nama_pelanggan' => $servisan->pelanggan->nama,
                'no_hp' => $servisan->pelanggan->no_hp,
                'alamat' => $servisan->pelanggan->alamat,
                'tipe_barang' => $servisan->tipe_barang,
                'merk_barang' => $servisan->merk_barang,
                'model_barang' => $servisan->model_barang,
                'kerusakan' => $servisan->kerusakan,
                'aksesoris' => $servisan->aksesoris,
                'catatan_teknisi' => $servisan->catatan_teknisi,
                'status' => $servisan->status,
                'estimasi_biaya' => $servisan->estimasi_biaya,
                'biaya_akhir' => $servisan->biaya_akhir,
                'dp' => $servisan->dp,
                'lunas' => $servisan->lunas,
                'sisa_pembayaran' => $servisan->sisa_pembayaran
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $servisan = Servisan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string|max:500',
            'tipe_barang' => 'required|string|max:100',
            'merk_barang' => 'required|string|max:100',
            'model_barang' => 'nullable|string|max:100',
            'kerusakan' => 'required|string',
            'aksesoris' => 'nullable|string',
            'catatan_teknisi' => 'nullable|string',
            'status' => 'required|string|in:menunggu,proses,selesai,diambil,dibatalkan',
            'estimasi_biaya' => 'required|numeric|min:0',
            'biaya_akhir' => 'nullable|numeric|min:0',
            'dp' => 'nullable|numeric|min:0',
            'lunas' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update atau buat pelanggan baru jika nomor HP berubah
            $pelanggan = Pelanggan::findOrCreatePelanggan(
                $request->nama_pelanggan,
                $request->no_hp,
                $request->alamat
            );

            // Update data servisan
            $updateData = [
                'pelanggan_id' => $pelanggan->id,
                'tipe_barang' => $request->tipe_barang,
                'merk_barang' => $request->merk_barang,
                'model_barang' => $request->model_barang,
                'kerusakan' => $request->kerusakan,
                'aksesoris' => $request->aksesoris,
                'catatan_teknisi' => $request->catatan_teknisi,
                'status' => $request->status,
                'estimasi_biaya' => $request->estimasi_biaya,
                'biaya_akhir' => $request->biaya_akhir,
                'dp' => $request->dp ?? 0,
                'lunas' => $request->boolean('lunas', false)
            ];

            // Jika status selesai dan tanggal_selesai kosong, isi dengan waktu sekarang
            if ($request->status == 'selesai' && empty($servisan->tanggal_selesai)) {
                $updateData['tanggal_selesai'] = now();
            }

            $servisan->update($updateData);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data servis berhasil diperbarui!',
                    'data' => $servisan->load('pelanggan')
                ]);
            }

            return redirect()->route('servisan.index')
                ->with('success', 'Data servis berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui data servis: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal memperbarui data servis: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateStatus(Request $request, Servisan $servisan)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:menunggu,proses,selesai,diambil,dibatalkan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $updateData = ['status' => $request->status];

            // Jika status berubah menjadi selesai, set tanggal selesai
            if ($request->status === 'selesai' && !$servisan->tanggal_selesai) {
                $updateData['tanggal_selesai'] = now();
            }

            $servisan->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui menjadi ' . ucfirst($request->status),
                'data' => [
                    'status' => $servisan->status,
                    'status_label' => $servisan->status_label,
                    'status_color' => $servisan->status_color,
                    'tanggal_selesai' => $servisan->tanggal_selesai ? $servisan->tanggal_selesai->format('d M Y, H:i') : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $servisan = Servisan::findOrFail($id);
            $kodeServis = $servisan->kode_servis;
            $servisan->delete();

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data servis ' . $kodeServis . ' berhasil dihapus!'
                ]);
            }

            return redirect()->route('servisan.index')
                ->with('success', 'Data servis ' . $kodeServis . ' berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menghapus data servis: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('servisan.index')
                ->with('error', 'Gagal menghapus data servis: ' . $e->getMessage());
        }
    }

    /**
     * Ubah status servis menjadi selesai.
     */
    public function markAsCompleted($id)
    {
        try {
            $servisan = Servisan::findOrFail($id);

            $servisan->update([
                'status' => 'selesai',
                'tanggal_selesai' => now()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Servis ' . $servisan->kode_servis . ' berhasil diselesaikan!',
                    'data' => $servisan->load('pelanggan')
                ]);
            }

            return redirect()->route('servisan.index')
                ->with('success', 'Servis ' . $servisan->kode_servis . ' berhasil diselesaikan!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menyelesaikan servis: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('servisan.index')
                ->with('error', 'Gagal menyelesaikan servis: ' . $e->getMessage());
        }
    }

    /**
     * Ubah status servis menjadi diambil dan lunas.
     */
    public function markAsDelivered(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'biaya_akhir' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $servisan = Servisan::findOrFail($id);

            $servisan->update([
                'status' => 'diambil',
                'biaya_akhir' => $request->biaya_akhir,
                'lunas' => true
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Barang ' . $servisan->kode_servis . ' berhasil diambil dan pembayaran lunas!',
                    'data' => $servisan->load('pelanggan')
                ]);
            }

            return redirect()->route('servisan.index')
                ->with('success', 'Barang ' . $servisan->kode_servis . ' berhasil diambil dan pembayaran lunas!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memproses pengambilan barang: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('servisan.index')
                ->with('error', 'Gagal memproses pengambilan barang: ' . $e->getMessage());
        }
    }

    /**
     * Filter servis berdasarkan status.
     */
    public function filter(Request $request)
    {
        $status = $request->status;
        $search = $request->search;

        $servisans = Servisan::with('pelanggan')
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('kode_servis', 'like', "%{$search}%")
                        ->orWhere('tipe_barang', 'like', "%{$search}%")
                        ->orWhere('merk_barang', 'like', "%{$search}%")
                        ->orWhereHas('pelanggan', function ($pelangganQuery) use ($search) {
                            $pelangganQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('no_hp', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('servisan.partials.servisan_table', compact('servisans', 'status', 'search'))->render();
        }

        return view('servisan.index', compact('servisans', 'status', 'search'));
    }

    /**
     * Print service receipt.
     */
    public function printReceipt($id)
    {
        $servisan = Servisan::with('pelanggan')->findOrFail($id);
        return view('servisan.print', compact('servisan'));
    }
}
