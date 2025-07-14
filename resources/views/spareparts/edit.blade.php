@extends('layouts.main')
@section('title', 'Edit Spare Parts')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Spare Part</a></li>
    <li class="breadcrumb-item"><a href="{{ route('spareparts.show', $sparepart->id) }}">{{ $sparepart->kode_sparepart }}</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Edit Sparepart: {{ $sparepart->nama_sparepart }}</h3>
                    </div>

                    <form action="{{ route('spareparts.update', $sparepart) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            {{-- Nama Sparepart --}}
                            <div class="form-group">
                                <label for="nama_sparepart">Nama Sparepart <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_sparepart') is-invalid @enderror"
                                    id="nama_sparepart" name="nama_sparepart"
                                    value="{{ old('nama_sparepart', $sparepart->nama_sparepart) }}"
                                    placeholder="Masukkan nama sparepart" required>
                                @error('nama_sparepart')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Merk --}}
                            <div class="form-group">
                                <label for="merk">Merk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('merk') is-invalid @enderror"
                                    id="merk" name="merk" value="{{ old('merk', $sparepart->merk) }}"
                                    placeholder="Masukkan merk" required>
                                @error('merk')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="form-group">
                                <label for="kategori">Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kategori') is-invalid @enderror"
                                    id="kategori" name="kategori" value="{{ old('kategori', $sparepart->kategori) }}"
                                    placeholder="Masukkan kategori" required>
                                @error('kategori')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"
                                    placeholder="Masukkan deskripsi sparepart">{{ old('deskripsi', $sparepart->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Stok --}}
                            <div class="form-group">
                                <label for="stok">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                    id="stok" name="stok" value="{{ old('stok', $sparepart->stok) }}"
                                    placeholder="Masukkan stok" required min="0">
                                @error('stok')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Harga Beli --}}
                            <div class="form-group">
                                <label for="harga_beli">Harga Beli <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control @error('harga_beli') is-invalid @enderror"
                                        id="harga_beli" name="harga_beli"
                                        value="{{ old('harga_beli', $sparepart->harga_beli) }}"
                                        placeholder="Masukkan harga beli" required min="0" step="0.01">
                                    @error('harga_beli')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Harga Jual --}}
                            <div class="form-group">
                                <label for="harga_jual">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control @error('harga_jual') is-invalid @enderror"
                                        id="harga_jual" name="harga_jual"
                                        value="{{ old('harga_jual', $sparepart->harga_jual) }}"
                                        placeholder="Masukkan harga jual" required min="0" step="0.01">
                                    @error('harga_jual')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Lokasi Penyimpanan --}}
                            <div class="form-group">
                                <label for="lokasi_penyimpanan">Lokasi Penyimpanan</label>
                                <input type="text" class="form-control @error('lokasi_penyimpanan') is-invalid @enderror"
                                    id="lokasi_penyimpanan" name="lokasi_penyimpanan"
                                    value="{{ old('lokasi_penyimpanan', $sparepart->lokasi_penyimpanan) }}"
                                    placeholder="Masukkan lokasi penyimpanan">
                                @error('lokasi_penyimpanan')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Tanggal Masuk --}}
                            <div class="form-group">
                                <label for="tanggal_masuk">Tanggal Masuk</label>
                                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                    id="tanggal_masuk" name="tanggal_masuk"
                                    value="{{ old('tanggal_masuk', $sparepart->tanggal_masuk->format('Y-m-d')) }}">
                                @error('tanggal_masuk')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Perbarui Sparepart
                            </button>
                            <a href="{{ route('spareparts.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Optional: Tambahkan validasi atau logika tambahan di sini
            $('#harga_jual').on('input', function() {
                let hargaBeli = parseFloat($('#harga_beli').val()) || 0;
                let hargaJual = parseFloat($(this).val()) || 0;

                if (hargaJual < hargaBeli) {
                    alert('Harga jual tidak boleh kurang dari harga beli');
                }
            });
        });
    </script>
@endpush
