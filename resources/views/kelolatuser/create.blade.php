@extends('layouts.main')

@section('title', 'Tambah Teknisi')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('kelolatuser.index') }}">Kelola Teknisi</a>
    </li>
    <li class="breadcrumb-item active">Tambah Teknisi</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Tambah Teknisi Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('kelolatuser.store') }}" id="tuserForm">
                        @csrf

                        <!-- Informasi Pribadi -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>
                                Informasi Pribadi
                            </h6>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            Nama
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Masukan Nama">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            Email
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="Masukan Nama">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">
                                            Nomor Telepon
                                            <i class="fas fa-info-circle text-muted ms-1" data-bs-toggle="tooltip"
                                                title="Format: 08xxxxxxxxx (akan otomatis dikonversi ke +62)"></i>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                id="phone" name="phone" value="{{ old('phone') }}"
                                                placeholder="08xxxxxxxxxx" maxlength="15">
                                            <button class="btn btn-outline-secondary" type="button" id="clearPhone"
                                                title="Hapus nomor">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">
                                            <i class="fas fa-lightbulb me-1"></i>
                                            Contoh: 081234567890, 08211234567
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <!-- Preview akan ditambahkan oleh JavaScript -->
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="">Pilih Status</option>
                                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                                Aktif
                                            </option>
                                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                                Tidak Aktif
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    Alamat
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                    placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informasi Keahlian -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-tools me-2"></i>
                                Informasi Keahlian
                            </h6>

                            <div class="mb-3">
                                <label for="skill_category" class="form-label">
                                    Kategori Keahlian
                                </label>
                                <select class="form-select @error('skill_category') is-invalid @enderror"
                                    id="skill_category" name="skill_category">
                                    <option value="">Pilih Kategori Keahlian</option>
                                    <option value="PC" {{ old('skill_category') === 'PC' ? 'selected' : '' }}>
                                        PC
                                    </option>
                                    <option value="Laptop" {{ old('skill_category') === 'Laptop' ? 'selected' : '' }}>
                                        Laptop
                                    </option>
                                    <option value="HP" {{ old('skill_category') === 'HP' ? 'selected' : '' }}>
                                        HP
                                    </option>
                                    <option value="Rakitan PC"
                                        {{ old('skill_category') === 'Rakitan PC' ? 'selected' : '' }}>
                                        Rakitan PC
                                    </option>
                                    <option value="Lainnya" {{ old('skill_category') === 'Lainnya' ? 'selected' : '' }}>
                                        Lainnya
                                    </option>
                                </select>
                                @error('skill_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih kategori keahlian utama teknisi
                                </div>
                            </div>

                            <!-- Custom skill category input -->
                            <div class="mb-3" id="customSkillDiv" style="display: none;">
                                <label for="custom_skill" class="form-label">
                                    Kategori Keahlian Lainnya
                                </label>
                                <input type="text" class="form-control" id="custom_skill"
                                    placeholder="Masukkan kategori keahlian lainnya">
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Contoh: Gaming PC, Tablet, Elektronik, dll.
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Akun -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-lock me-2"></i>
                                Informasi Akun
                            </h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password" required placeholder="Masukkan password">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Password minimal 8 karakter
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">
                                            Konfirmasi Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                id="password_confirmation" name="password_confirmation" required
                                                placeholder="Ulangi password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                id="togglePasswordConfirmation">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kelolatuser.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>

                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-outline-warning">
                                    <i class="fas fa-undo me-2"></i>
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Teknisi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // =================================================================
            // INITIALIZATION
            // =================================================================

            const form = document.querySelector('form');
            const phoneInput = document.getElementById('phone');
            const skillSelect = document.getElementById('skill_category');
            const customSkillDiv = document.getElementById('customSkillDiv');
            const customSkillInput = document.getElementById('custom_skill');
            const passwordInput = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const clearPhoneBtn = document.getElementById('clearPhone');

            // Predefined skill categories
            const predefinedSkills = ['PC', 'Laptop', 'HP', 'Rakitan PC'];

            // =================================================================
            // PASSWORD FUNCTIONALITY
            // =================================================================

            // Setup password toggle visibility
            function setupPasswordToggle(inputId, buttonId) {
                const input = document.getElementById(inputId);
                const button = document.getElementById(buttonId);

                if (input && button) {
                    button.addEventListener('click', function() {
                        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                        input.setAttribute('type', type);

                        const icon = this.querySelector('i');
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    });
                }
            }

            // Initialize password toggles
            setupPasswordToggle('password', 'togglePassword');
            setupPasswordToggle('password_confirmation', 'togglePasswordConfirmation');

            // Password confirmation validation
            if (passwordConfirmation) {
                passwordConfirmation.addEventListener('input', function() {
                    const password = passwordInput ? passwordInput.value : '';
                    const confirmation = this.value;

                    if (confirmation && password !== confirmation) {
                        this.setCustomValidity('Password tidak cocok');
                        this.classList.add('is-invalid');
                    } else {
                        this.setCustomValidity('');
                        this.classList.remove('is-invalid');
                    }
                });
            }

            // Password strength calculation
            function calculatePasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score++;
                if (/[a-z]/.test(password)) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[^A-Za-z0-9]/.test(password)) score++;
                return score;
            }

            // =================================================================
            // SKILL CATEGORY FUNCTIONALITY
            // =================================================================

            if (skillSelect && customSkillDiv && customSkillInput) {
                // Handle skill category change
                skillSelect.addEventListener('change', function() {
                    if (this.value === 'Lainnya') {
                        customSkillDiv.style.display = 'block';
                        customSkillInput.required = true;
                        customSkillInput.focus();
                    } else {
                        customSkillDiv.style.display = 'none';
                        customSkillInput.required = false;
                        customSkillInput.value = '';
                    }
                });

                // Custom skill input validation
                customSkillInput.addEventListener('blur', function() {
                    if (skillSelect.value === 'Lainnya' && !this.value.trim()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });

                // Initialize state for edit form
                initializeSkillCategory();
            }

            function initializeSkillCategory() {
                const currentSkill = skillSelect.getAttribute('data-current-skill') ||
                    (typeof tuserSkillCategory !== 'undefined' ? tuserSkillCategory : '');

                // Handle existing custom skill or "Lainnya" selection
                if (skillSelect.value === 'Lainnya' ||
                    (skillSelect.value === '' && customSkillInput.value !== '')) {
                    customSkillDiv.style.display = 'block';
                    customSkillInput.required = true;

                    if (customSkillInput.value !== '' && skillSelect.value === '') {
                        skillSelect.value = 'Lainnya';
                    }
                }

                // Handle skill not in predefined list
                if (currentSkill && !predefinedSkills.includes(currentSkill) && currentSkill !== 'Lainnya') {
                    skillSelect.value = 'Lainnya';
                    customSkillDiv.style.display = 'block';
                    customSkillInput.value = currentSkill;
                    customSkillInput.required = true;
                }
            }

            // =================================================================
            // PHONE NUMBER FUNCTIONALITY
            // =================================================================

            if (phoneInput) {
                // Create and append phone preview element
                const previewElement = createPhonePreview();
                phoneInput.parentNode.appendChild(previewElement);

                // Phone input formatting and validation
                phoneInput.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, ''); // Remove non-digits

                    // Limit length
                    if (value.length > 15) {
                        value = value.substring(0, 15);
                    }

                    // Auto-add 0 prefix for Indonesian numbers
                    if (value.length > 0 && !value.startsWith('0') && !value.startsWith('62')) {
                        value = '0' + value;
                    }

                    this.value = value;

                    // Real-time validation and preview
                    validatePhoneNumber(value);
                    showFormattedPreview(value);

                    // Handle clear button visibility
                    if (clearPhoneBtn) {
                        clearPhoneBtn.style.display = value.length > 0 ? 'block' : 'none';
                    }
                });

                // Phone validation on blur
                phoneInput.addEventListener('blur', function() {
                    validatePhoneNumber(this.value);
                });

                // Initialize phone preview if value exists
                if (phoneInput.value) {
                    showFormattedPreview(phoneInput.value);
                    validatePhoneNumber(phoneInput.value);
                }
            }

            // Phone clear button functionality
            if (clearPhoneBtn && phoneInput) {
                clearPhoneBtn.addEventListener('click', function() {
                    phoneInput.value = '';
                    phoneInput.classList.remove('is-valid', 'is-invalid');

                    const preview = document.getElementById('phonePreview');
                    if (preview) preview.style.display = 'none';

                    this.style.display = 'none';
                    phoneInput.focus();
                });

                // Initialize clear button visibility
                clearPhoneBtn.style.display = phoneInput.value.length > 0 ? 'block' : 'none';
            }

            // Phone helper functions
            function createPhonePreview() {
                const preview = document.createElement('div');
                preview.id = 'phonePreview';
                preview.className = 'form-text mt-1';
                preview.innerHTML = '<i class="fas fa-phone me-1"></i><span id="phoneFormatted"></span>';
                preview.style.display = 'none';
                return preview;
            }

            function validatePhoneNumber(phone) {
                if (!phoneInput) return true;

                const feedback = phoneInput.parentNode.querySelector('.phone-feedback') || createFeedbackElement();

                if (!phone) {
                    phoneInput.classList.remove('is-invalid', 'is-valid');
                    feedback.style.display = 'none';
                    return true;
                }

                const isValid = isValidIndonesianPhone(phone);

                if (isValid) {
                    phoneInput.classList.remove('is-invalid');
                    phoneInput.classList.add('is-valid');
                    feedback.style.display = 'none';
                } else {
                    phoneInput.classList.remove('is-valid');
                    phoneInput.classList.add('is-invalid');
                    feedback.textContent =
                        'Format nomor telepon tidak valid. Gunakan format Indonesia (08xxxxxxxxx).';
                    feedback.style.display = 'block';
                }

                return isValid;
            }

            function createFeedbackElement() {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback phone-feedback';
                phoneInput.parentNode.appendChild(feedback);
                return feedback;
            }

            function isValidIndonesianPhone(phone) {
                phone = phone.replace(/\D/g, '');

                if (phone.startsWith('628')) {
                    return phone.length >= 11 && phone.length <= 13;
                } else if (phone.startsWith('08')) {
                    return phone.length >= 10 && phone.length <= 12;
                } else if (phone.startsWith('8')) {
                    return phone.length >= 9 && phone.length <= 11;
                }

                return false;
            }

            function showFormattedPreview(phone) {
                const preview = document.getElementById('phonePreview');
                const formatted = document.getElementById('phoneFormatted');

                if (!phone || phone.length < 8) {
                    if (preview) preview.style.display = 'none';
                    return;
                }

                const formattedPhone = formatPhoneNumber(phone);
                const whatsappLink = getWhatsAppLink(phone);

                if (formatted) {
                    formatted.innerHTML = `
                <span class="text-success">${formattedPhone}</span>
                ${whatsappLink ? `<a href="${whatsappLink}" target="_blank" class="btn btn-xs btn-outline-success ms-2" title="Test WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>` : ''}
            `;
                }

                if (preview) preview.style.display = 'block';
            }

            function formatPhoneNumber(phone) {
                phone = phone.replace(/\D/g, '');

                if (phone.startsWith('628')) {
                    return '+62 ' + phone.substring(2, 5) + '-' + phone.substring(5, 9) + '-' + phone.substring(9);
                } else if (phone.startsWith('08')) {
                    return '+62 ' + phone.substring(1, 4) + '-' + phone.substring(4, 8) + '-' + phone.substring(8);
                } else if (phone.startsWith('8')) {
                    return '+62 ' + phone.substring(0, 3) + '-' + phone.substring(3, 7) + '-' + phone.substring(7);
                }

                return phone;
            }

            function getWhatsAppLink(phone) {
                if (!phone) return null;

                phone = phone.replace(/\D/g, '');

                if (phone.startsWith('08')) {
                    phone = '62' + phone.substring(1);
                } else if (phone.startsWith('8')) {
                    phone = '62' + phone;
                } else if (!phone.startsWith('628')) {
                    phone = phone.startsWith('0') ? '62' + phone.substring(1) : '62' + phone;
                }

                return `https://wa.me/${phone}`;
            }

            // =================================================================
            // FORM VALIDATION AND SUBMISSION
            // =================================================================

            if (form) {
                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Validate required fields
                    const requiredFields = form.querySelectorAll('[required]');
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });

                    // Validate phone number if present
                    if (phoneInput && phoneInput.value && !validatePhoneNumber(phoneInput.value)) {
                        e.preventDefault();
                        phoneInput.focus();
                        alert('Format nomor telepon tidak valid. Silakan perbaiki sebelum melanjutkan.');
                        return false;
                    }

                    // Handle custom skill category
                    if (skillSelect && skillSelect.value === 'Lainnya' && customSkillInput &&
                        customSkillInput.value.trim()) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'skill_category';
                        hiddenInput.value = customSkillInput.value.trim();
                        this.appendChild(hiddenInput);
                        skillSelect.removeAttribute('name');
                    }

                    // Prevent submission if validation failed
                    if (!isValid) {
                        e.preventDefault();
                        alert('Mohon lengkapi semua field yang wajib diisi');
                        return false;
                    }
                });
            }

            // =================================================================
            // TOOLTIPS INITIALIZATION
            // =================================================================

            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .input-group .btn {
            border-left: 0;
        }

        .input-group .form-control:focus+.btn {
            border-color: #86b7fe;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }

        h6.text-primary {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .btn-group .btn+.btn {
            margin-left: 0.5rem;
        }

        .input-group .btn#clearPhone {
            border-left: 0;
            display: none;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: 0;
        }

        .input-group .form-control {
            border-left: 0;
            border-right: 0;
        }

        .input-group .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .input-group .form-control:focus+.btn,
        .input-group .form-control:focus~.btn {
            border-color: #86b7fe;
        }

        #phonePreview {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        #phonePreview .text-success {
            font-weight: 600;
        }

        .btn-xs {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
            line-height: 1;
            border-radius: 0.2rem;
        }

        .is-valid {
            border-color: #198754;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .phone-feedback {
            display: none;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }
    </style>
@endpush
