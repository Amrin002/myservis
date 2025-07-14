<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SparepartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sparepart::query();

        // Filter pencarian
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter kategori
        if ($request->has('kategori')) {
            $query->byKategori($request->kategori);
        }

        // Update status sparepart
        $query->updateStatus();

        // Ambil daftar kategori untuk filter
        $kategoris = Sparepart::distinct('kategori')->pluck('kategori');

        // Pagination
        $spareparts = $query->latest()->paginate(10);

        return view('spareparts.index', [
            'spareparts' => $spareparts,
            'kategoris' => $kategoris,
            'search' => $request->search ?? '',
            'kategori_terpilih' => $request->kategori ?? ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('spareparts.create');
    }

    /**
     * Menyimpan sparepart baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_sparepart' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'tanggal_masuk' => 'nullable|date'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan sparepart
        $sparepart = Sparepart::create($validator->validated());

        // Redirect dengan pesan sukses
        return redirect()->route('spareparts.index')
            ->with('success', 'Sparepart berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail sparepart
     */
    public function show(Sparepart $sparepart)
    {
        return view('spareparts.show', compact('sparepart'));
    }

    /**
     * Menampilkan form edit sparepart
     */
    public function edit(Sparepart $sparepart)
    {
        return view('spareparts.edit', compact('sparepart'));
    }

    /**
     * Memperbarui sparepart
     */
    public function update(Request $request, Sparepart $sparepart)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_sparepart' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'tanggal_masuk' => 'nullable|date'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update sparepart
        $sparepart->update($validator->validated());

        // Redirect dengan pesan sukses
        return redirect()->route('spareparts.index')
            ->with('success', 'Sparepart berhasil diperbarui.');
    }

    /**
     * Menghapus sparepart
     */
    public function destroy(Sparepart $sparepart)
    {
        // Hapus sparepart
        $sparepart->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('spareparts.index')
            ->with('success', 'Sparepart berhasil dihapus.');
    }

    /**
     * Menambah stok sparepart
     */
    public function tambahStok(Request $request, Sparepart $sparepart)
    {
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Tambah stok
        $sparepart->tambahStok($request->jumlah);

        return redirect()->route('spareparts.show', $sparepart)
            ->with('success', "Stok {$sparepart->nama_sparepart} berhasil ditambah.");
    }

    /**
     * Mengurangi stok sparepart
     */
    public function kurangiStok(Request $request, Sparepart $sparepart)
    {
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kurangi stok
        if ($sparepart->kurangiStok($request->jumlah)) {
            return redirect()->route('spareparts.show', $sparepart)
                ->with('success', "Stok {$sparepart->nama_sparepart} berhasil dikurangi.");
        }

        // Jika stok tidak mencukupi
        return redirect()->back()
            ->with('error', "Stok tidak mencukupi untuk pengurangan.");
    }
}
