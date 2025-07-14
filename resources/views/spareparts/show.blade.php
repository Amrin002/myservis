@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Detail Sparepart</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4>{{ $sparepart->nama_sparepart }}</h4>
                                    <span class="badge badge-{{ $sparepart->status_color }} p-2">
                                        {{ $sparepart->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong>Kode Sparepart</strong>
                                <p>{{ $sparepart->kode_sparepart }}</p>

                                <strong>Merk</strong>
                                <p>{{ $sparepart->merk }}</p>

                                <strong>Kategori</strong>
                                <p>{{ $sparepart->kategori }}</p>

                                <strong>Lokasi Penyimpanan</strong>
                                <p>{{ $sparepart->lokasi_penyimpanan ?? 'Tidak ditentukan' }}</p>
                            </div>

                            <div class="col-md-6">
                                <strong>Stok</strong>
                                <p>
                                    {{ $sparepart->stok }}
                                    <span class="badge badge-{{ $sparepart->stok > 5 ? 'success' : 'warning' }}">
                                        {{ $sparepart->stok <= 5 ? 'Stok Rendah' : 'Stok Cukup' }}
                                    </span>
                                </p>

                                <strong>Harga Beli</strong>
                                <p>Rp {{ number_format($sparepart->harga_beli, 0, ',', '.') }}</p>

                                <strong>Harga Jual</strong>
                                <p>Rp {{ number_format($sparepart->harga_jual, 0, ',', '.') }}</p>

                                <strong>Tanggal Masuk</strong>
                                <p>{{ $sparepart->tanggal_masuk->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <strong>Deskripsi</strong>
                                <p>{{ $sparepart->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{-- Modal Tambah Stok --}}
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tambahStokModal">
                            <i class="fas fa-plus"></i> Tambah Stok
                        </button>

                        {{-- Modal Kurangi Stok --}}
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#kurangiStokModal">
                            <i class="fas fa-minus"></i> Kurangi Stok
                        </button>

                        <div class="float-right">
                            <a href="{{ route('spareparts.edit', $sparepart) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('spareparts.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Tambah Stok --}}
        <div class="modal fade" id="tambahStokModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Stok</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('spareparts.tambah-stok', $sparepart) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="jumlah_tambah">Jumlah Stok</label>
                                <input type="number" class="form-control" id="jumlah_tambah" name="jumlah" required
                                    min="1" placeholder="Masukkan jumlah stok yang ditambahkan">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Kurangi Stok --}}
        <div class="modal fade" id="kurangiStokModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kurangi Stok</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('spareparts.kurangi-stok', $sparepart) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="jumlah_kurang">Jumlah Stok</label>
                                <input type="number" class="form-control" id="jumlah_kurang" name="jumlah" required
                                    min="1" max="{{ $sparepart->stok }}"
                                    placeholder="Masukkan jumlah stok yang dikurangi">
                                <small class="form-text content-muted">Stok saat ini: {{ $sparepart->stok }}</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Kurangi</button>
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
            // Validasi tambah stok
            $('#tambahStokModal form').on('submit', function(e) {
                let jumlah = $('#jumlah_tambah').val();
                if (jumlah < 1) {
                    e.preventDefault();
                    alert('Jumlah stok harus lebih dari 0');
                }
            });

            // Validasi kurangi stok
            $('#kurangiStokModal form').on('submit', function(e) {
                let jumlah = $('#jumlah_kurang').val();
                let stokSaatIni = {{ $sparepart->stok }};

                if (jumlah < 1) {
                    e.preventDefault();
                    alert('Jumlah stok harus lebih dari 0');
                } else if (jumlah > stokSaatIni) {
                    e.preventDefault();
                    alert('Jumlah stok yang dikurangi melebihi stok saat ini');
                }
            });
        });
    </script>
@endpush
