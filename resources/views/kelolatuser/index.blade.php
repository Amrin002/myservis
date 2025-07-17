@extends('layouts.main')

@section('title', 'Kelola Teknisi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Kelola Teknisi</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-users-cog me-2"></i>
            Kelola Teknisi
        </h1>
        <a href="{{ route('kelolatuser.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Tambah Teknisi
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>
                Filter & Pencarian
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('kelolatuser.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Cari Teknisi</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama, Email, Phone..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kategori Keahlian</label>
                        <select name="skill_category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($skillCategories as $category)
                                <option value="{{ $category }}"
                                    {{ request('skill_category') === $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Urutkan</label>
                        <select name="sort_by" class="form-select">
                            <option value="created_at"
                                {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>
                                Tanggal Dibuat
                            </option>
                            <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>
                                Nama
                            </option>
                            <option value="email" {{ request('sort_by') === 'email' ? 'selected' : '' }}>
                                Email
                            </option>
                            <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>
                                Status
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Cari
                    </button>
                    <a href="{{ route('kelolatuser.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo me-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="bulkActionForm" method="POST" action="{{ route('kelolatuser.bulk-action') }}">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                Pilih Semua
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <select name="action" class="form-select" style="width: auto;">
                                <option value="">Pilih Aksi</option>
                                <option value="activate">Aktifkan</option>
                                <option value="deactivate">Nonaktifkan</option>
                                <option value="delete">Hapus</option>
                            </select>
                            <button type="submit" class="btn btn-warning" disabled id="bulkActionBtn">
                                <i class="fas fa-play me-2"></i>
                                Jalankan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                Data Teknisi
                <small class="text-muted">({{ $tusers->total() }} total)</small>
            </h5>
        </div>
        <div class="card-body">
            @if ($tusers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" id="selectAllTable">
                                </th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Kategori Keahlian</th>
                                <th>Bergabung</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tusers as $tuser)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input tuser-checkbox"
                                            name="selected_tusers[]" value="{{ $tuser->id }}" form="bulkActionForm">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $tuser->name }}</strong>
                                                @if ($tuser->skill_category)
                                                    <br><small class="text-muted">{{ $tuser->skill_category }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $tuser->email }}</td>
                                    <td>
                                        @if ($tuser->phone)
                                            <div>
                                                <span>{{ $tuser->formatted_phone ?? $tuser->phone }}</span>
                                                @if ($tuser->whatsapp_link)
                                                    <a href="{{ $tuser->whatsapp_link }}" target="_blank"
                                                        class="btn btn-xs btn-outline-success ms-1" title="WhatsApp">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $tuser->status === 'active' ? 'success' : 'danger' }}">
                                            {{ $tuser->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $tuser->skill_category ?? '-' }}</td>
                                    <td>{{ $tuser->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('kelolatuser.show', $tuser->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('kelolatuser.edit', $tuser->id) }}"
                                                class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('kelolatuser.toggle-status', $tuser->id) }}"
                                                style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-{{ $tuser->status === 'active' ? 'warning' : 'success' }}"
                                                    title="{{ $tuser->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i
                                                        class="fas fa-{{ $tuser->status === 'active' ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('kelolatuser.destroy', $tuser->id) }}"
                                                style="display: inline;"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus teknisi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Hapus">
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
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $tusers->firstItem() }} - {{ $tusers->lastItem() }}
                            dari {{ $tusers->total() }} data
                        </small>
                    </div>
                    <div>
                        {{ $tusers->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada data teknisi</h5>
                    <p class="text-muted">Silakan tambahkan teknisi baru</p>
                    <a href="{{ route('kelolatuser.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Teknisi
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const selectAllTableCheckbox = document.getElementById('selectAllTable');
            const tuserCheckboxes = document.querySelectorAll('.tuser-checkbox');
            const bulkActionBtn = document.getElementById('bulkActionBtn');
            const bulkActionForm = document.getElementById('bulkActionForm');

            // Sync both select all checkboxes
            function syncSelectAll() {
                const checkedCount = document.querySelectorAll('.tuser-checkbox:checked').length;
                const totalCount = tuserCheckboxes.length;

                selectAllCheckbox.checked = checkedCount === totalCount;
                selectAllTableCheckbox.checked = checkedCount === totalCount;

                // Enable/disable bulk action button
                bulkActionBtn.disabled = checkedCount === 0;
            }

            // Handle select all checkbox
            [selectAllCheckbox, selectAllTableCheckbox].forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    tuserCheckboxes.forEach(cb => cb.checked = this.checked);
                    syncSelectAll();
                });
            });

            // Handle individual checkboxes
            tuserCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', syncSelectAll);
            });

            // Handle bulk action form submission
            bulkActionForm.addEventListener('submit', function(e) {
                const action = this.querySelector('select[name="action"]').value;
                const checkedCount = document.querySelectorAll('.tuser-checkbox:checked').length;

                if (!action) {
                    e.preventDefault();
                    alert('Pilih aksi yang ingin dilakukan');
                    return;
                }

                if (checkedCount === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu teknisi');
                    return;
                }

                let message = '';
                switch (action) {
                    case 'activate':
                        message = `Aktifkan ${checkedCount} teknisi yang dipilih?`;
                        break;
                    case 'deactivate':
                        message = `Nonaktifkan ${checkedCount} teknisi yang dipilih?`;
                        break;
                    case 'delete':
                        message =
                            `Hapus ${checkedCount} teknisi yang dipilih? Data yang dihapus tidak dapat dikembalikan.`;
                        break;
                }

                if (!confirm(message)) {
                    e.preventDefault();
                }
            });

            // Initialize state
            syncSelectAll();
        });
    </script>
@endpush

@push('styles')
    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin-right: 2px;
        }

        .table-responsive {
            border-radius: 0.375rem;
        }

        .badge {
            font-size: 0.75rem;
        }

        .form-check-input:checked[type="checkbox"] {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
@endpush
