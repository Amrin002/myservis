{{-- File: resources/views/servisan/show.blade.php --}}
@extends('layouts.main')

@section('title', 'Detail Servisan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('servisan.index') }}">Servisan</a></li>
    <li class="breadcrumb-item active">{{ $servisan->kode_servis }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Header Info -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">{{ $servisan->kode_servis }}</h5>
                            <p class="content-muted mb-0">
                                <i class="fas fa-calendar me-1"></i>
                                Masuk: {{ $servisan->tanggal_masuk->format('d M Y, H:i') }}
                                @if ($servisan->tanggal_selesai)
                                    | Selesai: {{ $servisan->tanggal_selesai->format('d M Y, H:i') }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="badge bg-{{ $servisan->status_color }} fs-6">
                                {{ $servisan->status_label }}
                            </span>
                            @if ($servisan->lunas)
                                <span class="badge bg-success fs-6 ms-1">LUNAS</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Data Pelanggan -->
                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Data Pelanggan
                                </h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="40%"><strong>Nama</strong></td>
                                        <td>: {{ $servisan->pelanggan->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. HP</strong></td>
                                        <td>: {{ $servisan->pelanggan->no_hp }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat</strong></td>
                                        <td>: {{ $servisan->pelanggan->alamat ?: '-' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Data Barang -->
                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-laptop me-2"></i>Data Barang
                                </h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="40%"><strong>Tipe</strong></td>
                                        <td>: {{ $servisan->tipe_barang }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Merk</strong></td>
                                        <td>: {{ $servisan->merk_barang }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Model</strong></td>
                                        <td>: {{ $servisan->model_barang ?: '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-tools me-2"></i>Detail Kerusakan & Servis
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Kerusakan:</strong>
                                    <p class="mt-1 content-muted">{{ $servisan->kerusakan }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Aksesoris:</strong>
                                    <p class="mt-1 content-muted">{{ $servisan->aksesoris ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if ($servisan->catatan_teknisi)
                                    <div class="mb-3">
                                        <strong>Catatan Teknisi:</strong>
                                        <p class="mt-1 content-muted">{{ $servisan->catatan_teknisi }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Status -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>Riwayat Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Servisan Diterima</h6>
                                    <p class="content-muted mb-0">Barang diterima untuk diperbaiki</p>
                                    <small
                                        class="content-muted">{{ $servisan->tanggal_masuk->format('d M Y, H:i') }}</small>
                                </div>
                            </div>

                            @if ($servisan->status === 'proses' || $servisan->status === 'selesai' || $servisan->status === 'diambil')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Dalam Proses</h6>
                                        <p class="content-muted mb-0">Sedang dalam proses perbaikan</p>
                                        <small
                                            class="content-muted">{{ $servisan->updated_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                            @endif

                            @if ($servisan->status === 'selesai' || $servisan->status === 'diambil')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Perbaikan Selesai</h6>
                                        <p class="content-muted mb-0">Barang sudah selesai diperbaiki</p>
                                        <small class="content-muted">
                                            {{ $servisan->tanggal_selesai ? $servisan->tanggal_selesai->format('d M Y, H:i') : '-' }}
                                        </small>
                                    </div>
                                </div>
                            @endif

                            @if ($servisan->status === 'diambil')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Barang Diambil</h6>
                                        <p class="content-muted mb-0">Barang sudah diambil pelanggan</p>
                                        <small
                                            class="content-muted">{{ $servisan->updated_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                            @endif

                            @if ($servisan->status === 'dibatalkan')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Servisan Dibatalkan</h6>
                                        <p class="content-muted mb-0">Servisan dibatalkan</p>
                                        <small
                                            class="content-muted">{{ $servisan->updated_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Info Biaya -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>Informasi Biaya
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td><strong>Estimasi Biaya</strong></td>
                                <td>: Rp {{ number_format($servisan->estimasi_biaya, 0, ',', '.') }}</td>
                            </tr>
                            @if ($servisan->biaya_akhir)
                                <tr>
                                    <td><strong>Biaya Akhir</strong></td>
                                    <td>: Rp {{ number_format($servisan->biaya_akhir, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><strong>DP</strong></td>
                                <td>: Rp {{ number_format($servisan->dp, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Sisa Pembayaran</strong></td>
                                <td>: <strong class="text-danger">Rp
                                        {{ number_format($servisan->sisa_pembayaran, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Status Pembayaran</strong></td>
                                <td>:
                                    @if ($servisan->lunas)
                                        <span class="badge bg-success">LUNAS</span>
                                    @else
                                        <span class="badge bg-warning">BELUM LUNAS</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cog me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('servisan.edit', $servisan->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit Servisan
                            </a>

                            @if ($servisan->status !== 'diambil' && $servisan->status !== 'dibatalkan')
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle w-100" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-sync me-2"></i>Update Status
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        @if ($servisan->status !== 'proses')
                                            <li><a class="dropdown-item" href="#" onclick="updateStatus('proses')">
                                                    <i class="fas fa-spinner me-2"></i>Proses
                                                </a></li>
                                        @endif
                                        @if ($servisan->status !== 'selesai')
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="updateStatus('selesai')">
                                                    <i class="fas fa-check me-2"></i>Selesai
                                                </a></li>
                                        @endif
                                        @if ($servisan->status === 'selesai')
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="updateStatus('diambil')">
                                                    <i class="fas fa-handshake me-2"></i>Diambil
                                                </a></li>
                                        @endif
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="#"
                                                onclick="updateStatus('dibatalkan')">
                                                <i class="fas fa-times me-2"></i>Batalkan
                                            </a></li>
                                    </ul>
                                </div>
                            @endif

                            <button class="btn btn-secondary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Cetak
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('servisan.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Kembali ke Daftar
                            </a>
                            <a href="{{ route('servisan.create') }}" class="btn btn-outline-success">
                                <i class="fas fa-plus me-2"></i>Tambah Servisan Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage">Apakah Anda yakin ingin mengubah status?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmBtn">Ya, Ubah</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentStatus = '';

        function updateStatus(status) {
            currentStatus = status;
            const statusLabels = {
                'proses': 'Dalam Proses',
                'selesai': 'Selesai',
                'diambil': 'Diambil',
                'dibatalkan': 'Dibatalkan'
            };

            document.getElementById('confirmMessage').textContent =
                `Apakah Anda yakin ingin mengubah status menjadi "${statusLabels[status]}"?`;

            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        }

        document.getElementById('confirmBtn').addEventListener('click', function() {
            if (currentStatus) {
                // Kirim request AJAX untuk update status
                fetch(`{{ route('servisan.updateStatus', $servisan->id) }}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: currentStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });

                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
                modal.hide();
            }
        });

        // Print styles
        window.addEventListener('beforeprint', function() {
            document.body.classList.add('printing');
        });

        window.addEventListener('afterprint', function() {
            document.body.classList.remove('printing');
        });
    </script>

    <style>
        @media print {

            .btn,
            .card-header .badge,
            .timeline-marker,
            nav,
            .breadcrumb,
            .col-lg-4,
            .modal,
            .dropdown-menu {
                display: none !important;
            }

            .col-lg-8 {
                width: 100% !important;
                max-width: 100% !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .timeline-content {
                border-left: none !important;
                background: transparent !important;
            }
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 1rem;
            width: 2px;
            height: 100%;
            background: #dee2e6;
        }

        .timeline-marker {
            position: absolute;
            left: -1.75rem;
            top: 0.25rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #dee2e6;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #007bff;
        }
    </style>
@endpush
