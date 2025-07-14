@extends('layouts.main')

@section('title', 'Kelola Servisan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('servisan.index') }}">Servisan</a></li>
    <li class="breadcrumb-item active">Daftar Servisan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-1">Kelola Servisan</h1>
                        <p class="content-muted">Kelola semua data servisan pelanggan</p>
                    </div>
                    <a href="{{ route('servisan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Servisan
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter dan Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('servisan.filter') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Filter Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu
                            </option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Dalam Proses
                            </option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Cari berdasarkan kode, nama pelanggan, atau tipe barang..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Filter
                            </button>
                            <a href="{{ route('servisan.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Servisan -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Daftar Servisan
                    @if (request('status'))
                        - Status: <span class="badge bg-primary">{{ ucfirst(request('status')) }}</span>
                    @endif
                    @if (request('search'))
                        - Pencarian: <span class="badge bg-info">{{ request('search') }}</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if ($servisans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode Servis</th>
                                    <th>Pelanggan</th>
                                    <th>Barang</th>
                                    <th>Kerusakan</th>
                                    <th>Status</th>
                                    <th>Biaya</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servisans as $servisan)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $servisan->kode_servis }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ $servisan->pelanggan->nama }}</div>
                                                <small class="content-muted">{{ $servisan->pelanggan->no_hp }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div>{{ $servisan->tipe_barang }}</div>
                                                <small class="content-muted">{{ $servisan->merk_barang }}
                                                    {{ $servisan->model_barang }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                                title="{{ $servisan->kerusakan }}">
                                                {{ $servisan->kerusakan }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $servisan->status_color }}">
                                                {{ $servisan->status_label }}
                                            </span>
                                            @if ($servisan->lunas)
                                                <br><small class="badge bg-success mt-1">LUNAS</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <div class="small">Estimasi: Rp
                                                    {{ number_format($servisan->estimasi_biaya, 0, ',', '.') }}</div>
                                                @if ($servisan->biaya_akhir)
                                                    <div class="small text-success">Final: Rp
                                                        {{ number_format($servisan->biaya_akhir, 0, ',', '.') }}</div>
                                                @endif
                                                @if ($servisan->dp > 0)
                                                    <div class="small text-info">DP: Rp
                                                        {{ number_format($servisan->dp, 0, ',', '.') }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="small">Masuk: {{ $servisan->tanggal_masuk->format('d/m/Y') }}
                                                </div>
                                                @if ($servisan->tanggal_selesai)
                                                    <div class="small text-success">Selesai:
                                                        {{ $servisan->tanggal_selesai->format('d/m/Y') }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('servisan.show', $servisan->id) }}"
                                                    class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('servisan.edit', $servisan->id) }}"
                                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if ($servisan->status === 'proses')
                                                    <form action="{{ route('servisan.complete', $servisan->id) }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            title="Tandai Selesai"
                                                            onclick="return confirm('Apakah Anda yakin ingin menandai servisan ini sebagai selesai?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($servisan->status === 'selesai')
                                                    <a href="{{ route('servisan.show', $servisan->id) }}#deliver"
                                                        class="btn btn-sm btn-outline-primary" title="Proses Pengambilan">
                                                        <i class="fas fa-handshake"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('servisan.print', $servisan->id) }}"
                                                    class="btn btn-sm btn-outline-secondary" title="Print" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <form action="{{ route('servisan.destroy', $servisan->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Hapus"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus servisan ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <p class="content-muted">
                                Menampilkan {{ $servisans->firstItem() }} - {{ $servisans->lastItem() }}
                                dari {{ $servisans->total() }} data
                            </p>
                        </div>
                        <div>
                            {{ $servisans->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="content-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <h5>Tidak ada data servisan</h5>
                            <p>Belum ada data servisan yang tersedia.</p>
                            <a href="{{ route('servisan.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Servisan Pertama
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto submit form when status or search changes
        document.getElementById('status').addEventListener('change', function() {
            this.form.submit();
        });

        // Enter key search
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    </script>
@endpush
