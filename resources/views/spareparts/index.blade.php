@extends('layouts.main')

@section('title', 'Kelola Spare Part')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Spare Part</a></li>
    <li class="breadcrumb-item active">Daftar Spare Part</li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Sparepart</h3>
                        <div class="card-tools">
                            <a href="{{ route('spareparts.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Sparepart
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Filter dan Pencarian --}}
                        <form action="{{ route('spareparts.index') }}" method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari sparepart..." value="{{ $search }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="kategori" class="form-control">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategoris as $kat)
                                            <option value="{{ $kat }}"
                                                {{ $kategori_terpilih == $kat ? 'selected' : '' }}>
                                                {{ $kat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('spareparts.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>

                        {{-- Tabel Sparepart --}}
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Sparepart</th>
                                        <th>Merk</th>
                                        <th>Kategori</th>
                                        <th>Stok</th>
                                        <th>Harga Jual</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($spareparts as $sparepart)
                                        <tr>
                                            <td>{{ $sparepart->kode_sparepart }}</td>
                                            <td>{{ $sparepart->nama_sparepart }}</td>
                                            <td>{{ $sparepart->merk }}</td>
                                            <td>{{ $sparepart->kategori }}</td>
                                            <td>{{ $sparepart->stok }}</td>
                                            <td>Rp {{ number_format($sparepart->harga_jual, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $sparepart->status_color }}">
                                                    {{ $sparepart->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('spareparts.show', $sparepart) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('spareparts.edit', $sparepart) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('spareparts.destroy', $sparepart) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Yakin ingin menghapus?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data sparepart</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center">
                            {{ $spareparts->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Konfirmasi hapus
            $('.btn-delete').on('click', function(e) {
                if (!confirm('Yakin ingin menghapus sparepart ini?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
