{{-- File: resources/views/servisan/edit.blade.php --}}
@extends('layouts.main')

@section('title', 'Edit Servisan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('servisan.index') }}">Servisan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('servisan.show', $servisan->id) }}">{{ $servisan->kode_servis }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Edit Servisan {{ $servisan->kode_servis }}</h5>
                            <span class="badge bg-{{ $servisan->status_color }} fs-6">
                                {{ $servisan->status_label }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('servisan.update', $servisan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

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
                                            value="{{ old('nama_pelanggan', $servisan->pelanggan->nama) }}" required>
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
                                            value="{{ old('no_hp', $servisan->pelanggan->no_hp) }}" required>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea name="alamat" id="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $servisan->pelanggan->alamat) }}</textarea>
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
                                            value="{{ old('tipe_barang', $servisan->tipe_barang) }}"
                                            placeholder="Laptop, HP, Tablet, dll" required>
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
                                            value="{{ old('merk_barang', $servisan->merk_barang) }}"
                                            placeholder="Asus, HP, Samsung, dll" required>
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
                                            value="{{ old('model_barang', $servisan->model_barang) }}"
                                            placeholder="X441BA, Pavilion, dll">
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
                                            placeholder="Jelaskan kerusakan yang dialami..." required>{{ old('kerusakan', $servisan->kerusakan) }}</textarea>
                                        @error('kerusakan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="aksesoris" class="form-label">Aksesoris</label>
                                        <textarea name="aksesoris" id="aksesoris" rows="2"
                                            class="form-control @error('aksesoris') is-invalid @enderror" placeholder="Charger, tas, mouse, dll (opsional)">{{ old('aksesoris', $servisan->aksesoris) }}</textarea>
                                        @error('aksesoris')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="catatan_teknisi" class="form-label">Catatan Teknisi</label>
                                        <textarea name="catatan_teknisi" id="catatan_teknisi" rows="3"
                                            class="form-control @error('catatan_teknisi') is-invalid @enderror"
                                            placeholder="Catatan dari teknisi mengenai perbaikan...">{{ old('catatan_teknisi', $servisan->catatan_teknisi) }}</textarea>
                                        @error('catatan_teknisi')
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
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="estimasi_biaya" class="form-label">Estimasi Biaya <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="estimasi_biaya" id="estimasi_biaya"
                                                class="form-control @error('estimasi_biaya') is-invalid @enderror"
                                                value="{{ old('estimasi_biaya', $servisan->estimasi_biaya) }}"
                                                min="0" step="1000" required>
                                        </div>
                                        @error('estimasi_biaya')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="biaya_akhir" class="form-label">Biaya Akhir</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="biaya_akhir" id="biaya_akhir"
                                                class="form-control @error('biaya_akhir') is-invalid @enderror"
                                                value="{{ old('biaya_akhir', $servisan->biaya_akhir) }}" min="0"
                                                step="1000">
                                        </div>
                                        @error('biaya_akhir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Isi jika sudah ada kepastian biaya final</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="dp" class="form-label">DP (Down Payment)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="dp" id="dp"
                                                class="form-control @error('dp') is-invalid @enderror"
                                                value="{{ old('dp', $servisan->dp) }}" min="0" step="1000">
                                        </div>
                                        @error('dp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select name="status" id="status"
                                            class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="">Pilih Status</option>
                                            <option value="menunggu"
                                                {{ old('status', $servisan->status) == 'menunggu' ? 'selected' : '' }}>
                                                Menunggu</option>
                                            <option value="proses"
                                                {{ old('status', $servisan->status) == 'proses' ? 'selected' : '' }}>Dalam
                                                Proses</option>
                                            <option value="selesai"
                                                {{ old('status', $servisan->status) == 'selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                            <option value="diambil"
                                                {{ old('status', $servisan->status) == 'diambil' ? 'selected' : '' }}>
                                                Diambil</option>
                                            <option value="dibatalkan"
                                                {{ old('status', $servisan->status) == 'dibatalkan' ? 'selected' : '' }}>
                                                Dibatalkan</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Info Pembayaran -->
                                <div class="col-12">
                                    <div class="card content-bg">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">
                                                <i class="fas fa-calculator me-2"></i>Informasi Pembayaran
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="lunas"
                                                            id="lunas" value="1"
                                                            {{ old('lunas', $servisan->lunas) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lunas">
                                                            <strong>Pembayaran Lunas</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="row text-sm">
                                                        <div class="col-md-4">
                                                            <strong>Sisa Pembayaran:</strong><br>
                                                            <span class="text-danger" id="sisa-pembayaran">
                                                                Rp
                                                                {{ number_format($servisan->sisa_pembayaran, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>Total DP:</strong><br>
                                                            <span class="text-info">
                                                                Rp {{ number_format($servisan->dp, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>Status:</strong><br>
                                                            <span
                                                                class="badge bg-{{ $servisan->lunas ? 'success' : 'warning' }}">
                                                                {{ $servisan->lunas ? 'LUNAS' : 'BELUM LUNAS' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Tanggal -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-calendar me-2"></i>Informasi Tanggal
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Masuk</label>
                                        <input type="text" class="form-control"
                                            value="{{ $servisan->tanggal_masuk->format('d M Y, H:i') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="text" class="form-control"
                                            value="{{ $servisan->tanggal_selesai ? $servisan->tanggal_selesai->format('d M Y, H:i') : 'Belum selesai' }}"
                                            readonly>
                                        <div class="form-text">Otomatis diisi saat status diubah ke "Selesai"</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('servisan.show', $servisan->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </a>
                                        <div>
                                            <button type="reset" class="btn btn-outline-warning me-2">
                                                <i class="fas fa-undo me-2"></i>Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Servisan
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const estimasiBiayaInput = document.getElementById('estimasi_biaya');
            const biayaAkhirInput = document.getElementById('biaya_akhir');
            const dpInput = document.getElementById('dp');
            const sisaPembayaranSpan = document.getElementById('sisa-pembayaran');

            function hitungSisaPembayaran() {
                const estimasi = parseFloat(estimasiBiayaInput.value) || 0;
                const biayaAkhir = parseFloat(biayaAkhirInput.value) || 0;
                const dp = parseFloat(dpInput.value) || 0;

                const totalBiaya = biayaAkhir > 0 ? biayaAkhir : estimasi;
                const sisa = totalBiaya - dp;

                sisaPembayaranSpan.textContent = 'Rp ' + sisa.toLocaleString('id-ID');
                sisaPembayaranSpan.className = sisa <= 0 ? 'text-success' : 'text-danger';
            }

            // Event listeners untuk real-time calculation
            estimasiBiayaInput.addEventListener('input', hitungSisaPembayaran);
            biayaAkhirInput.addEventListener('input', hitungSisaPembayaran);
            dpInput.addEventListener('input', hitungSisaPembayaran);

            // Auto-check lunas jika sisa pembayaran <= 0
            function checkLunas() {
                const estimasi = parseFloat(estimasiBiayaInput.value) || 0;
                const biayaAkhir = parseFloat(biayaAkhirInput.value) || 0;
                const dp = parseFloat(dpInput.value) || 0;

                const totalBiaya = biayaAkhir > 0 ? biayaAkhir : estimasi;
                const sisa = totalBiaya - dp;

                const lunasCheckbox = document.getElementById('lunas');
                if (sisa <= 0 && totalBiaya > 0) {
                    lunasCheckbox.checked = true;
                }
            }

            dpInput.addEventListener('input', checkLunas);
            biayaAkhirInput.addEventListener('input', checkLunas);

            // Konfirmasi jika mengubah status ke dibatalkan
            document.getElementById('status').addEventListener('change', function() {
                if (this.value === 'dibatalkan') {
                    if (!confirm('Apakah Anda yakin ingin membatalkan servisan ini?')) {
                        this.value = '{{ $servisan->status }}'; // Reset ke status sebelumnya
                    }
                }
            });

            // Format input number saat blur
            [estimasiBiayaInput, biayaAkhirInput, dpInput].forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value) {
                        const value = parseFloat(this.value);
                        this.value = Math.round(value);
                    }
                });
            });
        });
    </script>
@endpush
