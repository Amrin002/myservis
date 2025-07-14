{{-- File: resources/views/pelanggan/edit.blade.php --}}
@extends('layouts.main')

@section('title', 'Edit Pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pelanggan.show', $pelanggan->id) }}">{{ $pelanggan->nama }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Edit Data Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Avatar Preview -->
                                <div class="col-md-3 text-center mb-4">
                                    <div class="avatar-preview mb-3" id="avatarPreview">
                                        {{ strtoupper(substr($pelanggan->nama, 0, 2)) }}
                                    </div>
                                    <small class="content-muted">Avatar akan berubah otomatis berdasarkan nama</small>
                                </div>

                                <!-- Form Fields -->
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nama" id="nama"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            value="{{ old('nama', $pelanggan->nama) }}"
                                            placeholder="Masukkan nama lengkap pelanggan" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Masukkan nama lengkap dan jelas</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">Nomor HP <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" name="no_hp" id="no_hp"
                                                class="form-control @error('no_hp') is-invalid @enderror"
                                                value="{{ old('no_hp', $pelanggan->no_hp) }}" placeholder="08xxxxxxxxxx"
                                                required>
                                            <a href="tel:{{ $pelanggan->no_hp }}" class="btn btn-outline-secondary"
                                                type="button">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                        </div>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Format: 08xxxxxxxxxx (tanpa spasi atau tanda hubung)</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea name="alamat" id="alamat" rows="4" class="form-control @error('alamat') is-invalid @enderror"
                                            placeholder="Masukkan alamat lengkap (opsional)">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Alamat lengkap membantu untuk pengiriman atau kunjungan</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Tambahan -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informasi Pelanggan
                                        </h6>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small>
                                                    <strong>Terdaftar:</strong>
                                                    {{ $pelanggan->created_at->format('d M Y, H:i') }}<br>
                                                    <strong>Terakhir Update:</strong>
                                                    {{ $pelanggan->updated_at->format('d M Y, H:i') }}
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <small>
                                                    <strong>Total Servisan:</strong> {{ $pelanggan->servisans->count() }}
                                                    servis<br>
                                                    <strong>Servisan Aktif:</strong>
                                                    {{ $pelanggan->servisans->whereIn('status', ['menunggu', 'proses', 'selesai'])->count() }}
                                                    servis
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('pelanggan.show', $pelanggan->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </a>
                                        <div>
                                            <button type="reset" class="btn btn-outline-warning me-2"
                                                onclick="resetForm()">
                                                <i class="fas fa-undo me-2"></i>Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Pelanggan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Servisan Terkait -->
                @if ($pelanggan->servisans->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-link me-2"></i>Servisan Terkait ({{ $pelanggan->servisans->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Perhatian:</strong> Perubahan data pelanggan ini akan mempengaruhi semua servisan
                                yang terkait.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Kode Servis</th>
                                            <th>Barang</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pelanggan->servisans->take(5) as $servisan)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('servisan.show', $servisan->id) }}"
                                                        class="text-decoration-none">
                                                        {{ $servisan->kode_servis }}
                                                    </a>
                                                </td>
                                                <td>{{ $servisan->tipe_barang }} {{ $servisan->merk_barang }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $servisan->status_color }} badge-sm">
                                                        {{ $servisan->status_label }}
                                                    </span>
                                                </td>
                                                <td>{{ $servisan->tanggal_masuk->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($pelanggan->servisans->count() > 5)
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <a href="{{ route('pelanggan.show', $pelanggan->id) }}"
                                                        class="text-decoration-none">
                                                        <i class="fas fa-plus-circle me-1"></i>
                                                        Lihat {{ $pelanggan->servisans->count() - 5 }} servisan lainnya
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-preview {
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
            transition: all 0.3s ease;
        }

        .badge-sm {
            font-size: 0.7em;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaInput = document.getElementById('nama');
            const avatarPreview = document.getElementById('avatarPreview');
            const originalNama = "{{ $pelanggan->nama }}";

            // Update avatar preview when name changes
            namaInput.addEventListener('input', function() {
                const nama = this.value.trim();
                if (nama.length >= 1) {
                    const initials = nama.length >= 2 ? nama.substring(0, 2).toUpperCase() : nama.substring(
                        0, 1).toUpperCase();
                    avatarPreview.textContent = initials;
                } else {
                    avatarPreview.textContent = originalNama.substring(0, 2).toUpperCase();
                }
            });

            // Format phone number input
            const noHpInput = document.getElementById('no_hp');
            noHpInput.addEventListener('input', function() {
                // Remove non-numeric characters
                let value = this.value.replace(/\D/g, '');

                // Ensure it starts with 08 if it doesn't start with 0
                if (value.length > 0 && !value.startsWith('0')) {
                    value = '0' + value;
                }

                this.value = value;
            });

            // Auto-resize textarea
            const alamatTextarea = document.getElementById('alamat');
            alamatTextarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const nama = namaInput.value.trim();
                const noHp = noHpInput.value.trim();

                if (nama.length < 2) {
                    e.preventDefault();
                    alert('Nama harus minimal 2 karakter');
                    namaInput.focus();
                    return;
                }

                if (noHp.length < 10 || !noHp.startsWith('0')) {
                    e.preventDefault();
                    alert('Nomor HP harus minimal 10 digit dan dimulai dengan 0');
                    noHpInput.focus();
                    return;
                }

                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...';
                submitBtn.disabled = true;

                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            });
        });

        // Reset form function
        function resetForm() {
            if (confirm('Apakah Anda yakin ingin mengatur ulang form? Semua perubahan akan hilang.')) {
                document.querySelector('form').reset();

                // Reset avatar
                const avatarPreview = document.getElementById('avatarPreview');
                avatarPreview.textContent = "{{ strtoupper(substr($pelanggan->nama, 0, 2)) }}";

                // Reset textarea height
                const alamatTextarea = document.getElementById('alamat');
                alamatTextarea.style.height = 'auto';

                // Clear validation states
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + S to save
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                document.querySelector('form').submit();
            }

            // Escape to go back
            if (e.key === 'Escape') {
                if (confirm('Apakah Anda ingin kembali? Perubahan yang belum disimpan akan hilang.')) {
                    window.location.href = "{{ route('pelanggan.show', $pelanggan->id) }}";
                }
            }
        });

        // Warn before leaving if form is dirty
        let formChanged = false;
        document.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('input', () => {
                formChanged = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Mark form as clean when submitted
        document.querySelector('form').addEventListener('submit', () => {
            formChanged = false;
        });
    </script>
@endpush
