{{-- File: resources/views/pelanggan/show.blade.php --}}
@extends('layouts.main')

@section('title', 'Detail Pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
    <li class="breadcrumb-item active">{{ $pelanggan->nama }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Info Pelanggan -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>Informasi Pelanggan
                        </h5>
                        <div>
                            <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="deletePelanggan({{ $pelanggan->id }}, '{{ $pelanggan->nama }}')">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <div class="avatar-large mb-3">
                                    {{ strtoupper(substr($pelanggan->nama, 0, 2)) }}
                                </div>
                            </div>
                            <div class="col-md-10">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Nama Lengkap</strong></td>
                                        <td>: {{ $pelanggan->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. HP</strong></td>
                                        <td>:
                                            <a href="tel:+{{ $pelanggan->international_phone }}"
                                                class="text-decoration-none">
                                                {{ $pelanggan->formatted_phone }}
                                            </a>
                                            <a href="{{ $pelanggan->whatsapp_link }}" target="_blank"
                                                class="btn btn-success btn-sm ms-2">
                                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat</strong></td>
                                        <td>: {{ $pelanggan->alamat ?: 'Belum diisi' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Terdaftar</strong></td>
                                        <td>: {{ $pelanggan->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Terakhir Update</strong></td>
                                        <td>: {{ $pelanggan->updated_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Servisan -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>Riwayat Servisan
                        </h5>
                        <a href="{{ route('servisan.create') }}?pelanggan_id={{ $pelanggan->id }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Tambah Servisan
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($pelanggan->servisans->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kode Servis</th>
                                            <th>Barang</th>
                                            <th>Status</th>
                                            <th>Biaya</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pelanggan->servisans as $servisan)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold text-primary">{{ $servisan->kode_servis }}</span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-medium">{{ $servisan->tipe_barang }}</div>
                                                        <small class="content-muted">{{ $servisan->merk_barang }}
                                                            {{ $servisan->model_barang }}</small>
                                                    </div>
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
                                                    <div class="small">
                                                        @if ($servisan->biaya_akhir)
                                                            <div class="text-success">Final: Rp
                                                                {{ number_format($servisan->biaya_akhir, 0, ',', '.') }}
                                                            </div>
                                                        @endif
                                                        <div>Estimasi: Rp
                                                            {{ number_format($servisan->estimasi_biaya, 0, ',', '.') }}
                                                        </div>
                                                        @if ($servisan->dp > 0)
                                                            <div class="text-info">DP: Rp
                                                                {{ number_format($servisan->dp, 0, ',', '.') }}</div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        <div>Masuk: {{ $servisan->tanggal_masuk->format('d/m/Y') }}</div>
                                                        @if ($servisan->tanggal_selesai)
                                                            <div class="text-success">Selesai:
                                                                {{ $servisan->tanggal_selesai->format('d/m/Y') }}</div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('servisan.show', $servisan->id) }}"
                                                            class="btn btn-sm btn-outline-info" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('servisan.edit', $servisan->id) }}"
                                                            class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="content-muted">
                                    <i class="fas fa-tools fa-2x mb-3"></i>
                                    <h6>Belum ada riwayat servisan</h6>
                                    <p>Pelanggan ini belum pernah melakukan servis</p>
                                    <a href="{{ route('servisan.create') }}?pelanggan_id={{ $pelanggan->id }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Buat Servisan Pertama
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statistik -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Statistik Pelanggan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="h4 text-primary">{{ $pelanggan->servisans->count() }}</div>
                                <small class="content-muted">Total Servisan</small>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="h4 text-success">
                                    {{ $pelanggan->servisans->where('status', 'diambil')->count() }}</div>
                                <small class="content-muted">Selesai Diambil</small>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="h4 text-warning">
                                    {{ $pelanggan->servisans->whereIn('status', ['menunggu', 'proses'])->count() }}</div>
                                <small class="content-muted">Sedang Proses</small>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="h4 text-danger">
                                    {{ $pelanggan->servisans->where('status', 'dibatalkan')->count() }}</div>
                                <small class="content-muted">Dibatalkan</small>
                            </div>
                        </div>

                        <hr>

                        <div class="row text-center">
                            <div class="col-12 mb-3">
                                <div class="h5 text-info">
                                    Rp
                                    {{ number_format(
                                        $pelanggan->servisans->where('lunas', true)->sum(function ($servisan) {
                                            return $servisan->biaya_akhir ?: $servisan->estimasi_biaya;
                                        }),
                                        0,
                                        ',',
                                        '.',
                                    ) }}
                                </div>
                                <small class="content-muted">Total Pembayaran</small>
                            </div>
                            <div class="col-12">
                                <div class="h6 content-muted">
                                    {{ $pelanggan->servisans->where('lunas', true)->count() }} dari
                                    {{ $pelanggan->servisans->count() }} servisan lunas
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Servisan Aktif -->
                @php
                    $activeServisans = $pelanggan->servisans->whereIn('status', ['menunggu', 'proses', 'selesai']);
                @endphp

                @if ($activeServisans->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock me-2"></i>Servisan Aktif
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach ($activeServisans as $servisan)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-2 content-bg rounded">
                                    <div>
                                        <div class="fw-medium">{{ $servisan->kode_servis }}</div>
                                        <small class="content-muted">{{ $servisan->tipe_barang }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span
                                            class="badge bg-{{ $servisan->status_color }}">{{ $servisan->status_label }}</span>
                                        <div class="small content-muted">{{ $servisan->tanggal_masuk->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-lightning-bolt me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('servisan.create') }}?pelanggan_id={{ $pelanggan->id }}"
                                class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Servisan Baru
                            </a>
                            <a href="tel:+{{ $pelanggan->international_phone }}" class="btn btn-outline-success">
                                <i class="fas fa-phone me-2"></i>Telepon
                            </a>
                            <a href="{{ $pelanggan->whatsapp_link }}" target="_blank" class="btn btn-outline-success">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                            <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="btn btn-outline-warning">
                                <i class="fas fa-edit me-2"></i>Edit Data
                            </a>
                            <hr>
                            <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
            margin: 0 auto;
        }
    </style>
@endpush
@push('scripts')
    <script>
        // Delete Pelanggan
        function deletePelanggan(id, nama) {
            if (confirm(
                    `Apakah Anda yakin ingin menghapus pelanggan "${nama}"?\n\nPerhatian: Semua data servisan terkait akan ikut terhapus.`
                )) {

                // Using Form Submission (Most reliable)
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pelanggan/${id}`;
                form.style.display = 'none';

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);

                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
