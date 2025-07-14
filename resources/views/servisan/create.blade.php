@extends('layouts.main')

@section('title', 'Tambah Servisan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('servisan.index') }}">Servisan</a></li>
    <li class="breadcrumb-item active">Tambah Servisan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tambah Servisan Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('servisan.store') }}" method="POST">
                            @csrf

                            <!-- Data Pelanggan -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-user me-2"></i>Data Pelanggan
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_pelanggan" class="form-label">Nama Pelanggan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nama_pelanggan" id="nama_pelanggan"
                                            class="form-control @error('nama_pelanggan') is-invalid @enderror"
                                            value="{{ old('nama_pelanggan') }}" required>
                                        @error('nama_pelanggan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">No. HP <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="no_hp" id="no_hp"
                                            class="form-control @error('no_hp') is-invalid @enderror"
                                            value="{{ old('no_hp') }}" required>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea name="alamat" id="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Data Barang -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-laptop me-2"></i>Data Barang
                                    </h6>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tipe_barang" class="form-label">Tipe Barang <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="tipe_barang" id="tipe_barang"
                                            class="form-control @error('tipe_barang') is-invalid @enderror"
                                            value="{{ old('tipe_barang') }}" placeholder="Laptop, HP, Tablet, dll" required>
                                        @error('tipe_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="merk_barang" class="form-label">Merk <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="merk_barang" id="merk_barang"
                                            class="form-control @error('merk_barang') is-invalid @enderror"
                                            value="{{ old('merk_barang') }}" placeholder="Asus, HP, Samsung, dll" required>
                                        @error('merk_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="model_barang" class="form-label">Model</label>
                                        <input type="text" name="model_barang" id="model_barang"
                                            class="form-control @error('model_barang') is-invalid @enderror"
                                            value="{{ old('model_barang') }}" placeholder="X441BA, Pavilion, dll">
                                        @error('model_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="kerusakan" class="form-label">Kerusakan <span
                                                class="text-danger">*</span></label>
                                        <textarea name="kerusakan" id="kerusakan" rows="3" class="form-control @error('kerusakan') is-invalid @enderror"
                                            placeholder="Jelaskan kerusakan yang dialami..." required>{{ old('kerusakan') }}</textarea>
                                        @error('kerusakan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="aksesoris" class="form-label">Aksesoris</label>
                                        <textarea name="aksesoris" id="aksesoris" rows="2"
                                            class="form-control @error('aksesoris') is-invalid @enderror" placeholder="Charger, tas, mouse, dll (opsional)">{{ old('aksesoris') }}</textarea>
                                        @error('aksesoris')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Data Servis -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-wrench me-2"></i>Data Servis
                                    </h6>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="estimasi_biaya" class="form-label">Estimasi Biaya <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="estimasi_biaya" id="estimasi_biaya"
                                                class="form-control @error('estimasi_biaya') is-invalid @enderror"
                                                value="{{ old('estimasi_biaya') }}" min="0" step="1000"
                                                required>
                                        </div>
                                        @error('estimasi_biaya')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="dp" class="form-label">DP (Down Payment)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="dp" id="dp"
                                                class="form-control @error('dp') is-invalid @enderror"
                                                value="{{ old('dp', 0) }}" min="0" step="1000">
                                        </div>
                                        @error('dp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select name="status" id="status"
                                            class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="">Pilih Status</option>
                                            <option value="menunggu" {{ old('status') == 'menunggu' ? 'selected' : '' }}>
                                                Menunggu</option>
                                            <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Dalam
                                                Proses</option>
                                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('servisan.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </a>
                                        <div>
                                            <button type="reset" class="btn btn-outline-warning me-2">
                                                <i class="fas fa-undo me-2"></i>Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Simpan Servisan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
