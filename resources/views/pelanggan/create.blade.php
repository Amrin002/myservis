{{-- File: resources/views/pelanggan/create.blade.php --}}
@extends('layouts.main')

@section('title', 'Tambah Pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
    <li class="breadcrumb-item active">Tambah Pelanggan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-plus me-2"></i>Tambah Pelanggan Baru
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pelanggan.store') }}" method="POST" id="createPelangganForm">
                            @csrf

                            <div class="row">
                                <!-- Avatar Preview -->
                                <div class="col-md-3 text-center mb-4">
                                    <div class="avatar-preview mb-3" id="avatarPreview">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                    <small class="content-muted">Avatar akan muncul berdasarkan nama</small>
                                </div>

                                <!-- Form Fields -->
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nama" id="nama"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            value="{{ old('nama') }}" placeholder="Masukkan nama lengkap pelanggan"
                                            required>
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
                                                value="{{ old('no_hp') }}" placeholder="08xxxxxxxxx atau 628xxxxxxxxx"
                                                required>
                                            <button class="btn btn-outline-secondary" type="button" id="formatPhoneBtn">
                                                <i class="fas fa-magic"></i>
                                            </button>
                                        </div>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Format yang didukung: 08xxxxxxxxx, 628xxxxxxxxx, +628xxxxxxxxx
                                            <br><small class="content-muted">Akan otomatis dikonversi ke format
                                                internasional</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea name="alamat" id="alamat" rows="4" class="form-control @error('alamat') is-invalid @enderror"
                                            placeholder="Masukkan alamat lengkap (opsional)">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Alamat lengkap membantu untuk pengiriman atau kunjungan</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="row mt-4" id="previewSection" style="display: none;">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading"><i class="fas fa-eye me-2"></i>Preview Data Pelanggan</h6>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Nama:</strong> <span id="previewNama">-</span><br>
                                                <strong>No. HP (Display):</strong> <span id="previewPhone">-</span><br>
                                                <strong>No. HP (Tersimpan):</strong> <span
                                                    id="previewInternational">-</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Alamat:</strong> <span id="previewAlamat">-</span><br>
                                                <strong>WhatsApp Link:</strong> <a href="#" id="previewWhatsApp"
                                                    target="_blank" class="text-decoration-none">Test WhatsApp</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </a>
                                        <div>
                                            <button type="reset" class="btn btn-outline-warning me-2"
                                                onclick="resetForm()">
                                                <i class="fas fa-undo me-2"></i>Reset
                                            </button>
                                            <button type="button" class="btn btn-outline-info me-2"
                                                onclick="togglePreview()">
                                                <i class="fas fa-eye me-2"></i>Preview
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Simpan Pelanggan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Panduan Pengisian
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Format Nomor HP yang Didukung:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i><code>08123456789</code> (Format
                                        lokal)</li>
                                    <li><i class="fas fa-check text-success me-2"></i><code>628123456789</code> (Format
                                        internasional)</li>
                                    <li><i class="fas fa-check text-success me-2"></i><code>+628123456789</code> (Dengan
                                        tanda +)</li>
                                    <li><i class="fas fa-check text-success me-2"></i><code>8123456789</code> (Tanpa awalan
                                        0)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Tips Pengisian:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-lightbulb text-warning me-2"></i>Nama harus lengkap dan jelas</li>
                                    <li><i class="fas fa-lightbulb text-warning me-2"></i>Nomor HP akan otomatis diformat
                                    </li>
                                    <li><i class="fas fa-lightbulb text-warning me-2"></i>Alamat sangat membantu untuk
                                        identifikasi</li>
                                    <li><i class="fas fa-lightbulb text-warning me-2"></i>Gunakan preview untuk memastikan
                                        data benar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
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

        .form-text {
            font-size: 0.875em;
        }

        .alert-info {
            border-left: 4px solid #17a2b8;
        }

        code {
            background-color: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.875em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaInput = document.getElementById('nama');
            const noHpInput = document.getElementById('no_hp');
            const alamatInput = document.getElementById('alamat');
            const avatarPreview = document.getElementById('avatarPreview');
            const formatPhoneBtn = document.getElementById('formatPhoneBtn');

            // Update avatar preview when name changes
            namaInput.addEventListener('input', function() {
                const nama = this.value.trim();
                if (nama.length >= 1) {
                    const words = nama.split(' ');
                    let initials;
                    if (words.length >= 2) {
                        initials = words[0].substring(0, 1) + words[1].substring(0, 1);
                    } else {
                        initials = nama.substring(0, 2);
                    }
                    avatarPreview.textContent = initials.toUpperCase();
                } else {
                    avatarPreview.innerHTML = '<i class="fas fa-user fa-2x"></i>';
                }
                updatePreview();
            });

            // Format phone number on input
            noHpInput.addEventListener('input', function() {
                // Remove non-numeric characters
                let value = this.value.replace(/\D/g, '');

                // Auto-format based on input
                if (value.startsWith('628')) {
                    // International format - keep as is
                    this.value = value;
                } else if (value.startsWith('8')) {
                    // Could be local without 0, or international without 62
                    if (value.length <= 10) {
                        this.value = '0' + value; // Assume local
                    } else {
                        this.value = '62' + value; // Assume international
                    }
                } else if (value.startsWith('0')) {
                    // Local format
                    this.value = value;
                } else if (value.startsWith('62')) {
                    // International format
                    if (!value.startsWith('628') && value.length > 3) {
                        this.value = '628' + value.substring(2);
                    } else {
                        this.value = value;
                    }
                } else if (value.length > 0) {
                    // Unknown format, assume local
                    this.value = '0' + value;
                }

                validatePhone();
                updatePreview();
            });

            // Format phone button
            formatPhoneBtn.addEventListener('click', function() {
                const value = noHpInput.value.replace(/\D/g, '');
                if (value) {
                    const formatted = normalizePhoneNumber(value);
                    noHpInput.value = formatted;
                    validatePhone();
                    updatePreview();
                }
            });

            // Phone validation
            function validatePhone() {
                const value = noHpInput.value.replace(/\D/g, '');
                let isValid = false;

                if (value.startsWith('628') && value.length >= 11 && value.length <= 13) {
                    isValid = true;
                } else if (value.startsWith('08') && value.length >= 10 && value.length <= 12) {
                    isValid = true;
                }

                if (!isValid && value.length > 0) {
                    noHpInput.classList.add('is-invalid');
                } else {
                    noHpInput.classList.remove('is-invalid');
                }

                return isValid;
            }

            // Normalize phone number
            function normalizePhoneNumber(phone) {
                if (!phone) return '';

                phone = phone.replace(/\D/g, '');

                if (phone.startsWith('628')) {
                    return phone;
                } else if (phone.startsWith('08')) {
                    return '62' + phone.substring(1);
                } else if (phone.startsWith('8')) {
                    return '62' + phone;
                } else if (phone.startsWith('62')) {
                    if (!phone.startsWith('628')) {
                        return '628' + phone.substring(2);
                    }
                    return phone;
                } else {
                    return '628' + phone;
                }
            }

            // Format phone for display
            function formatPhoneDisplay(phone) {
                const clean = phone.replace(/\D/g, '');
                if (clean.startsWith('628')) {
                    return '+62 ' + clean.substring(2, 5) + '-' + clean.substring(5, 9) + '-' + clean.substring(9);
                } else if (clean.startsWith('08')) {
                    return '+62 ' + clean.substring(1, 4) + '-' + clean.substring(4, 8) + '-' + clean.substring(8);
                }
                return phone;
            }

            // Update preview
            function updatePreview() {
                const nama = namaInput.value.trim();
                const noHp = noHpInput.value.trim();
                const alamat = alamatInput.value.trim();

                document.getElementById('previewNama').textContent = nama || '-';
                document.getElementById('previewAlamat').textContent = alamat || '-';

                if (noHp) {
                    const normalized = normalizePhoneNumber(noHp);
                    const formatted = formatPhoneDisplay(normalized);

                    document.getElementById('previewPhone').textContent = formatted;
                    document.getElementById('previewInternational').textContent = normalized;
                    document.getElementById('previewWhatsApp').href = `https://wa.me/${normalized}`;
                } else {
                    document.getElementById('previewPhone').textContent = '-';
                    document.getElementById('previewInternational').textContent = '-';
                    document.getElementById('previewWhatsApp').href = '#';
                }
            }

            // Toggle preview
            window.togglePreview = function() {
                const previewSection = document.getElementById('previewSection');
                if (previewSection.style.display === 'none') {
                    updatePreview();
                    previewSection.style.display = 'block';
                } else {
                    previewSection.style.display = 'none';
                }
            };

            // Auto-resize textarea
            alamatInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
                updatePreview();
            });

            // Form validation
            const form = document.getElementById('createPelangganForm');
            form.addEventListener('submit', function(e) {
                const nama = namaInput.value.trim();
                const noHp = noHpInput.value.trim();

                if (nama.length < 2) {
                    e.preventDefault();
                    alert('Nama harus minimal 2 karakter');
                    namaInput.focus();
                    return;
                }

                if (!validatePhone()) {
                    e.preventDefault();
                    alert('Format nomor HP tidak valid');
                    noHpInput.focus();
                    return;
                }

                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                submitBtn.disabled = true;

                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            });

            // Reset form function
            window.resetForm = function() {
                if (confirm('Apakah Anda yakin ingin mengatur ulang form? Semua data akan hilang.')) {
                    form.reset();
                    avatarPreview.innerHTML = '<i class="fas fa-user fa-2x"></i>';
                    alamatInput.style.height = 'auto';
                    document.getElementById('previewSection').style.display = 'none';

                    // Clear validation states
                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                }
            };

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl + S to save
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    form.submit();
                }

                // Escape to go back
                if (e.key === 'Escape') {
                    if (confirm('Apakah Anda ingin kembali? Data yang belum disimpan akan hilang.')) {
                        window.location.href = "{{ route('pelanggan.index') }}";
                    }
                }
            });

            // Warn before leaving if form is dirty
            let formChanged = false;
            [namaInput, noHpInput, alamatInput].forEach(input => {
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
            form.addEventListener('submit', () => {
                formChanged = false;
            });
        });
    </script>
@endpush
