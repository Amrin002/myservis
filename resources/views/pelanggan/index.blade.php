{{-- File: resources/views/pelanggan/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Kelola Pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Kelola Pelanggan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-1">Kelola Pelanggan</h1>
                        <p class="content-muted">Kelola semua data pelanggan dan riwayat servisannya</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pelanggan.export') }}{{ request()->search ? '?search=' . request()->search : '' }}"
                            class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>Export CSV
                        </a>
                        <a type="button" class="btn btn-primary" href="{{ route('pelanggan.create') }}">
                            <i class="fas fa-plus me-2"></i>Tambah Pelanggan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter dan Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Cari berdasarkan nama, nomor HP, atau alamat..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="sort_by" class="form-label">Urutkan berdasarkan</label>
                        <select name="sort_by" id="sort_by" class="form-select">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal
                                Daftar</option>
                            <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Nama</option>
                            <option value="servisans_count" {{ request('sort_by') == 'servisans_count' ? 'selected' : '' }}>
                                Jumlah Servis</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="sort_order" class="form-label">Urutan</label>
                        <select name="sort_order" id="sort_order" class="form-select">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Pelanggan -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        Daftar Pelanggan
                        @if (request('search'))
                            <span class="badge bg-info">Pencarian: "{{ request('search') }}"</span>
                        @endif
                    </h5>
                    <small class="content-muted">Total: {{ $pelanggans->total() }} pelanggan</small>
                </div>
            </div>
            <div class="card-body">
                @if ($pelanggans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-white text-decoration-none">
                                            Nama
                                            @if (request('sort_by') == 'nama')
                                                <i
                                                    class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>No. HP</th>
                                    <th>Alamat</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'servisans_count', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-white text-decoration-none">
                                            Total Servis
                                            @if (request('sort_by') == 'servisans_count')
                                                <i
                                                    class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-white text-decoration-none">
                                            Terdaftar
                                            @if (request('sort_by') == 'created_at')
                                                <i
                                                    class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelanggans as $index => $pelanggan)
                                    <tr>
                                        <td>{{ $pelanggans->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    {{ strtoupper(substr($pelanggan->nama, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $pelanggan->nama }}</div>
                                                    <small class="content-muted">ID: {{ $pelanggan->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="tel:+{{ $pelanggan->international_phone }}"
                                                class="text-decoration-none">
                                                {{ $pelanggan->formatted_phone }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                                title="{{ $pelanggan->alamat }}">
                                                {{ $pelanggan->alamat ?: '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($pelanggan->servisans_count > 0)
                                                <span class="badge bg-primary">{{ $pelanggan->servisans_count }}
                                                    servis</span>
                                            @else
                                                <span class="content-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small
                                                class="content-muted">{{ $pelanggan->created_at->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('pelanggan.show', $pelanggan->id) }}"
                                                    class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" type="button"
                                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="deletePelanggan({{ $pelanggan->id }}, '{{ $pelanggan->nama }}')"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
                            <p class="content-muted mb-0">
                                Menampilkan {{ $pelanggans->firstItem() }} - {{ $pelanggans->lastItem() }}
                                dari {{ $pelanggans->total() }} pelanggan
                            </p>
                        </div>
                        <div>
                            {{ $pelanggans->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="content-muted">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5>
                                @if (request('search'))
                                    Tidak ada pelanggan yang ditemukan
                                @else
                                    Belum ada data pelanggan
                                @endif
                            </h5>
                            <p>
                                @if (request('search'))
                                    Coba ubah kata kunci pencarian Anda
                                @else
                                    Mulai dengan menambahkan pelanggan pertama
                                @endif
                            </p>
                            @if (!request('search'))
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addPelangganModal">
                                    <i class="fas fa-plus me-2"></i>Tambah Pelanggan Pertama
                                </button>
                            @else
                                <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Lihat Semua Pelanggan
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


@endsection

@push('styles')
    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .table th a {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .table th a:hover {
            text-decoration: underline !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Add Pelanggan
        document.getElementById('addPelangganForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;

            // Clear previous errors
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            fetch('{{ route('pelanggan.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal and reload page
                        bootstrap.Modal.getInstance(document.getElementById('addPelangganModal')).hide();
                        location.reload();
                    } else {
                        // Show validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const input = document.getElementById('add_' + field);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    input.nextElementSibling.textContent = data.errors[field][0];
                                }
                            });
                        }
                        alert(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem');
                })
                .finally(() => {
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        // Edit Pelanggan
        function editPelanggan(id) {
            // Get pelanggan data
            fetch(`/pelanggan/${id}/get`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const pelanggan = data.data;

                        // Fill form
                        document.getElementById('edit_pelanggan_id').value = pelanggan.id;
                        document.getElementById('edit_nama').value = pelanggan.nama;
                        document.getElementById('edit_no_hp').value = pelanggan.no_hp;
                        document.getElementById('edit_alamat').value = pelanggan.alamat || '';

                        // Show modal
                        new bootstrap.Modal(document.getElementById('editPelangganModal')).show();
                    } else {
                        alert('Gagal mengambil data pelanggan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem');
                });
        }

        // Update Pelanggan
        document.getElementById('editPelangganForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const pelangganId = document.getElementById('edit_pelanggan_id').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...';
            submitBtn.disabled = true;

            // Clear previous errors
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            fetch(`/pelanggan/${pelangganId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal and reload page
                        bootstrap.Modal.getInstance(document.getElementById('editPelangganModal')).hide();
                        location.reload();
                    } else {
                        // Show validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const input = document.getElementById('edit_' + field);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    input.nextElementSibling.textContent = data.errors[field][0];
                                }
                            });
                        }
                        alert(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem');
                })
                .finally(() => {
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        // Delete Pelanggan
        function deletePelanggan(id, nama) {
            if (confirm(
                    `Apakah Anda yakin ingin menghapus pelanggan "${nama}"?\n\nPerhatian: Pelanggan yang memiliki servisan aktif tidak dapat dihapus.`
                )) {

                // Method 1: Using Form Submission (Most reliable)
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

                // Method 2: Using Fetch with FormData (Alternative if you want AJAX)
                /*
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('_method', 'DELETE');
                
                fetch(`/pelanggan/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal menghapus pelanggan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
                */
            }
        }

        // Search functionality
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });

        // Auto submit on sort change
        document.getElementById('sort_by').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('sort_order').addEventListener('change', function() {
            this.form.submit();
        });

        // Phone number input formatting
        function formatPhoneInput(input) {
            input.addEventListener('input', function() {
                // Remove non-numeric characters
                let value = this.value.replace(/\D/g, '');

                // Auto-format based on input
                if (value.startsWith('628')) {
                    // International format - keep as is
                    this.value = value;
                } else if (value.startsWith('8')) {
                    // Add 62 prefix for international
                    if (value.length > 10) {
                        this.value = '62' + value;
                    } else {
                        this.value = '0' + value; // Local format
                    }
                } else if (value.startsWith('0')) {
                    // Local format
                    this.value = value;
                } else if (value.startsWith('62')) {
                    // International without proper 628
                    if (!value.startsWith('628') && value.length > 3) {
                        this.value = '628' + value.substring(2);
                    } else {
                        this.value = value;
                    }
                } else if (value.length > 0) {
                    // Unknown format, assume local
                    this.value = '0' + value;
                }
            });

            // Validation on blur
            input.addEventListener('blur', function() {
                const value = this.value.replace(/\D/g, '');
                let isValid = false;

                if (value.startsWith('628') && value.length >= 11 && value.length <= 13) {
                    isValid = true;
                } else if (value.startsWith('08') && value.length >= 10 && value.length <= 12) {
                    isValid = true;
                }

                if (!isValid && value.length > 0) {
                    this.classList.add('is-invalid');
                    const feedback = this.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = 'Format nomor HP tidak valid';
                    }
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        }

        // Apply phone formatting to inputs
        formatPhoneInput(document.getElementById('add_no_hp'));
        formatPhoneInput(document.getElementById('edit_no_hp'));

        // Clear search
        function clearSearch() {
            document.getElementById('search').value = '';
            document.getElementById('search').form.submit();
        }

        // Reset form when modal is hidden
        document.getElementById('addPelangganModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('addPelangganForm');
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        document.getElementById('editPelangganModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('editPelangganForm');
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });
    </script>
@endpush
