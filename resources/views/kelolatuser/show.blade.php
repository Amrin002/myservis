@extends('layouts.main')

@section('title', 'Detail Teknisi')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('kelolatuser.index') }}">Kelola Teknisi</a>
    </li>
    <li class="breadcrumb-item active">Detail Teknisi</li>
@endsection

@section('content')
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        Profil Teknisi
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div
                        class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>

                    <h4 class="mb-1">{{ $tuser->name }}</h4>
                    <p class="text-muted mb-3">{{ $tuser->skill_category ?? 'Belum ada kategori' }}</p>

                    <span class="badge bg-{{ $tuser->status === 'active' ? 'success' : 'danger' }} fs-6 mb-3">
                        {{ $tuser->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                    </span>

                    <div class="row text-center">
                        <div class="col">
                            <div class="border-end">
                                <h5 class="mb-0 text-primary">{{ $statistik['servisan_bulan_ini'] }}</h5>
                                <small class="text-muted">Servisan Bulan Ini</small>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="mb-0 text-warning">{{ $statistik['servisan_proses'] }}</h5>
                            <small class="text-muted">Sedang Proses</small>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('kelolatuser.edit', $tuser->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Edit Data
                        </a>
                        <form method="POST" action="{{ route('kelolatuser.toggle-status', $tuser->id) }}"
                            style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="btn btn-{{ $tuser->status === 'active' ? 'secondary' : 'success' }} w-100">
                                <i class="fas fa-{{ $tuser->status === 'active' ? 'pause' : 'play' }} me-2"></i>
                                {{ $tuser->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-address-card me-2"></i>
                        Informasi Kontak
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email:</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <span>{{ $tuser->email }}</span>
                            <a href="mailto:{{ $tuser->email }}" class="btn btn-sm btn-outline-primary ms-auto">
                                <i class="fas fa-paper-plane"></i>
                            </a>
                        </div>
                    </div>

                    @if ($tuser->phone)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Telepon:</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-muted me-2"></i>
                                <div class="flex-grow-1">
                                    <span>{{ $tuser->formatted_phone ?? $tuser->phone }}</span>
                                    <br><small class="text-muted">{{ $tuser->phone }}</small>
                                </div>
                                <div class="btn-group ms-auto">
                                    <a href="tel:{{ $tuser->phone }}" class="btn btn-sm btn-outline-success"
                                        title="Telepon">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                    @if ($tuser->whatsapp_link)
                                        <a href="{{ $tuser->whatsapp_link }}" target="_blank"
                                            class="btn btn-sm btn-outline-success" title="WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($tuser->address)
                        <div class="mb-0">
                            <label class="form-label fw-bold">Alamat:</label>
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-muted me-2 mt-1"></i>
                                <span>{{ $tuser->address }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center border-primary">
                        <div class="card-body">
                            <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                            <h4 class="mb-0 text-primary">{{ $statistik['servisan_hari_ini'] }}</h4>
                            <small class="text-muted">Hari Ini</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card text-center border-info">
                        <div class="card-body">
                            <i class="fas fa-calendar-week fa-2x text-info mb-2"></i>
                            <h4 class="mb-0 text-info">{{ $statistik['servisan_minggu_ini'] }}</h4>
                            <small class="text-muted">Minggu Ini</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card text-center border-warning">
                        <div class="card-body">
                            <i class="fas fa-cog fa-2x text-warning mb-2"></i>
                            <h4 class="mb-0 text-warning">{{ $statistik['servisan_proses'] }}</h4>
                            <small class="text-muted">Sedang Proses</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="card text-center border-danger">
                        <div class="card-body">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                            <h4 class="mb-0 text-danger">{{ $statistik['servisan_prioritas'] }}</h4>
                            <small class="text-muted">Prioritas</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Services -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Servisan Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @if ($recentServisan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Pelanggan</th>
                                        <th>Jenis Barang</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentServisan as $servisan)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $servisan->pelanggan->nama ?? 'N/A' }}</strong>
                                                    <br><small
                                                        class="text-muted">{{ $servisan->pelanggan->phone ?? '' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $servisan->jenis_barang }}</strong>
                                                    <br><small class="text-muted">{{ $servisan->merk_model ?? '' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClasses = [
                                                        'pending' => 'warning',
                                                        'proses' => 'info',
                                                        'selesai' => 'success',
                                                        'diambil' => 'primary',
                                                        'batal' => 'danger',
                                                    ];
                                                    $statusLabels = [
                                                        'pending' => 'Menunggu',
                                                        'proses' => 'Proses',
                                                        'selesai' => 'Selesai',
                                                        'diambil' => 'Diambil',
                                                        'batal' => 'Batal',
                                                    ];
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $statusClasses[$servisan->status] ?? 'secondary' }}">
                                                    {{ $statusLabels[$servisan->status] ?? $servisan->status }}
                                                </span>
                                                @if ($servisan->is_prioritas)
                                                    <span class="badge bg-danger ms-1">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $servisan->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('servisan.show', $servisan) }}"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('servisan.index', ['tuser_id' => $tuser->id]) }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>
                                Lihat Semua Servisan
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Belum ada servisan</h6>
                            <p class="text-muted">Teknisi ini belum menangani servisan apapun</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Bergabung:</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-plus text-muted me-2"></i>
                                    <span>{{ $tuser->created_at->format('d F Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Terakhir Diperbarui:</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-edit text-muted me-2"></i>
                                    <span>{{ $tuser->updated_at->format('d F Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori Keahlian:</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tools text-muted me-2"></i>
                                    <span>{{ $tuser->skill_category ?? 'Belum ditentukan' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ID Teknisi:</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-hashtag text-muted me-2"></i>
                                    <span class="font-monospace">#{{ str_pad($tuser->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh statistik setiap 30 detik (jika API tersedia)
            // setInterval(function() {
            //     fetch(`/api/admin/tuser/{{ $tuser->id }}/statistics`)
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data.success) {
            //                 updateStatistics(data.statistik);
            //             }
            //         })
            //         .catch(error => {
            //             console.log('Error refreshing statistics:', error);
            //         });
            // }, 30000);

            function updateStatistics(stats) {
                // Update statistik cards
                const elements = {
                    'servisan_hari_ini': document.querySelector('.text-primary.mb-0'),
                    'servisan_minggu_ini': document.querySelector('.text-info.mb-0'),
                    'servisan_proses': document.querySelector('.text-warning.mb-0'),
                    'servisan_prioritas': document.querySelector('.text-danger.mb-0')
                };

                Object.keys(elements).forEach(key => {
                    if (elements[key] && stats[key] !== undefined) {
                        const currentValue = parseInt(elements[key].textContent);
                        const newValue = stats[key];

                        if (currentValue !== newValue) {
                            elements[key].textContent = newValue;
                            elements[key].classList.add('bg-warning');
                            setTimeout(() => {
                                elements[key].classList.remove('bg-warning');
                            }, 2000);
                        }
                    }
                });
            }

            // Tooltip initialization
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .avatar-lg {
            width: 80px;
            height: 80px;
            font-size: 1.5rem;
        }

        .border-end {
            border-right: 1px solid #dee2e6 !important;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
            transition: box-shadow 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .border-primary {
            border-color: #0d6efd !important;
        }

        .border-info {
            border-color: #0dcaf0 !important;
        }

        .border-warning {
            border-color: #ffc107 !important;
        }

        .border-danger {
            border-color: #dc3545 !important;
        }

        .fw-bold {
            font-weight: 600 !important;
        }

        .font-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .badge {
            font-size: 0.75rem;
        }

        .fs-6 {
            font-size: 1rem !important;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .d-grid {
            display: grid !important;
        }

        .gap-2 {
            gap: 0.5rem !important;
        }

        @keyframes highlight {
            0% {
                background-color: #fff3cd;
            }

            100% {
                background-color: transparent;
            }
        }

        .bg-warning {
            animation: highlight 2s ease-in-out;
        }
    </style>
@endpush
