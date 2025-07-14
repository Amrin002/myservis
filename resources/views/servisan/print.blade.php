{{-- File: resources/views/servisan/print.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Servis - {{ $servisan->kode_servis }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }

        .receipt {
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            margin-bottom: 2px;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 5px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .label {
            font-weight: bold;
        }

        .value {
            text-align: right;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 5px 0;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border: 1px solid #000;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .receipt {
                width: 100%;
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>NOTA SERVIS</h1>
            <p>{{ config('app.name', 'Toko Servis') }}</p>
            <p>Jl. Contoh No. 123, Kota</p>
            <p>Telp: 0123-456-789</p>
        </div>

        <!-- Info Servis -->
        <div class="section">
            <div class="row">
                <span class="label">Kode Servis:</span>
                <span class="value">{{ $servisan->kode_servis }}</span>
            </div>
            <div class="row">
                <span class="label">Tanggal Masuk:</span>
                <span class="value">{{ $servisan->tanggal_masuk->format('d/m/Y H:i') }}</span>
            </div>
            @if ($servisan->tanggal_selesai)
                <div class="row">
                    <span class="label">Tanggal Selesai:</span>
                    <span class="value">{{ $servisan->tanggal_selesai->format('d/m/Y H:i') }}</span>
                </div>
            @endif
            <div class="row">
                <span class="label">Status:</span>
                <span class="value">
                    <span class="status-badge">{{ strtoupper($servisan->status) }}</span>
                </span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Data Pelanggan -->
        <div class="section">
            <div class="section-title">DATA PELANGGAN</div>
            <div class="row">
                <span class="label">Nama:</span>
                <span class="value">{{ $servisan->pelanggan->nama }}</span>
            </div>
            <div class="row">
                <span class="label">No. HP:</span>
                <span class="value">{{ $servisan->pelanggan->no_hp }}</span>
            </div>
            @if ($servisan->pelanggan->alamat)
                <div class="row">
                    <span class="label">Alamat:</span>
                </div>
                <div style="text-align: justify; margin-top: 3px;">
                    {{ $servisan->pelanggan->alamat }}
                </div>
            @endif
        </div>

        <div class="separator"></div>

        <!-- Data Barang -->
        <div class="section">
            <div class="section-title">DATA BARANG</div>
            <div class="row">
                <span class="label">Tipe:</span>
                <span class="value">{{ $servisan->tipe_barang }}</span>
            </div>
            <div class="row">
                <span class="label">Merk:</span>
                <span class="value">{{ $servisan->merk_barang }}</span>
            </div>
            @if ($servisan->model_barang)
                <div class="row">
                    <span class="label">Model:</span>
                    <span class="value">{{ $servisan->model_barang }}</span>
                </div>
            @endif
        </div>

        <!-- Kerusakan -->
        <div class="section">
            <div class="section-title">KERUSAKAN</div>
            <div style="text-align: justify;">
                {{ $servisan->kerusakan }}
            </div>
        </div>

        @if ($servisan->aksesoris)
            <!-- Aksesoris -->
            <div class="section">
                <div class="section-title">AKSESORIS</div>
                <div style="text-align: justify;">
                    {{ $servisan->aksesoris }}
                </div>
            </div>
        @endif

        @if ($servisan->catatan_teknisi)
            <!-- Catatan Teknisi -->
            <div class="section">
                <div class="section-title">CATATAN TEKNISI</div>
                <div style="text-align: justify;">
                    {{ $servisan->catatan_teknisi }}
                </div>
            </div>
        @endif

        <div class="separator"></div>

        <!-- Rincian Biaya -->
        <div class="section">
            <div class="section-title">RINCIAN BIAYA</div>
            <div class="row">
                <span class="label">Estimasi Biaya:</span>
                <span class="value">Rp {{ number_format($servisan->estimasi_biaya, 0, ',', '.') }}</span>
            </div>

            @if ($servisan->biaya_akhir)
                <div class="row">
                    <span class="label">Biaya Akhir:</span>
                    <span class="value">Rp {{ number_format($servisan->biaya_akhir, 0, ',', '.') }}</span>
                </div>
            @endif

            @if ($servisan->dp > 0)
                <div class="row">
                    <span class="label">DP Dibayar:</span>
                    <span class="value">Rp {{ number_format($servisan->dp, 0, ',', '.') }}</span>
                </div>

                <div class="separator"></div>

                <div class="row total">
                    <span class="label">SISA BAYAR:</span>
                    <span class="value">Rp {{ number_format($servisan->sisa_pembayaran, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="row">
                <span class="label">Status Bayar:</span>
                <span class="value">{{ $servisan->lunas ? 'LUNAS' : 'BELUM LUNAS' }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Syarat dan Ketentuan -->
        <div class="section" style="font-size: 9px;">
            <div class="section-title">SYARAT & KETENTUAN</div>
            <div style="text-align: justify;">
                1. Barang yang sudah diperbaiki dan tidak diambil dalam 30 hari, tidak menjadi tanggung jawab kami.<br>
                2. Kerusakan yang timbul setelah perbaikan diluar tanggung jawab kami.<br>
                3. Simpan nota ini sebagai bukti pengambilan barang.<br>
                4. Garansi servis 7 hari (tidak termasuk kerusakan baru).
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <!-- Print Button (Hidden saat print) -->
    <div class="no-print" style="text-align: center; margin: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px;">
            Print Nota
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; margin-left: 10px;">
            Tutup
        </button>
    </div>

    <script>
        // Auto print saat halaman dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>

</html>
