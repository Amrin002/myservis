{{-- File: resources/views/dashboard/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-3">Dashboard</h1>
                <p class="content-muted">Selamat datang di sistem kelola servisan Anda</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="h5 mb-0">{{ $totalPelanggan ?? 0 }}</div>
                                <div class="text-white-50">Total Pelanggan</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('pelanggan.index') }}" class="text-white text-decoration-none small">
                            <i class="fas fa-arrow-right me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="h5 mb-0">{{ $servisanMenunggu ?? 0 }}</div>
                                <div class="text-white-50">Antrian Servisan</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('servisan.index') }}?status=menunggu"
                            class="text-white text-decoration-none small">
                            <i class="fas fa-arrow-right me-1"></i> Lihat Antrian
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="h5 mb-0">{{ $servisanProses ?? 0 }}</div>
                                <div class="text-white-50">Dalam Proses</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wrench fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('servisan.index') }}?status=proses" class="text-white text-decoration-none small">
                            <i class="fas fa-arrow-right me-1"></i> Lihat Progress
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="h5 mb-0">{{ $servisanSelesai ?? 0 }}</div>
                                <div class="text-white-50">Servisan Selesai</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('servisan.index') }}?status=selesai"
                            class="text-white text-decoration-none small">
                            <i class="fas fa-arrow-right me-1"></i> Lihat Selesai
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sparepart Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="h5 mb-0">{{ $sparepartStats['total_sparepart'] ?? 0 }}</div>
                            <div class="text-white-50">Total Sparepart</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('spareparts.index') }}" class="text-white text-decoration-none small">
                        <i class="fas fa-arrow-right me-1"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="h4 mb-0">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                                <div class="text-white-50">Total Pendapatan (Lunas)</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Grafik Statistik Servisan</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary chart-period-btn active"
                                data-period="mingguan">Mingguan</button>
                            <button type="button" class="btn btn-sm btn-outline-primary chart-period-btn"
                                data-period="bulanan">Bulanan</button>
                            <button type="button" class="btn btn-sm btn-outline-primary chart-period-btn"
                                data-period="tahunan">Tahunan</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <canvas id="servisanChart" width="400" height="200"></canvas>
                            </div>
                            <div class="col-lg-4">
                                <div class="mt-3">
                                    <h6>Ringkasan Periode</h6>
                                    <div id="chart-summary">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Servisan:</span>
                                            <strong id="total-servisan">-</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Pendapatan:</span>
                                            <strong id="total-pendapatan">-</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Rata-rata per Periode:</span>
                                            <strong id="rata-rata">-</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Servisan Terbaru -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Servisan Terbaru</h5>
                        <a href="{{ route('servisan.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode Servis</th>
                                        <th>Pelanggan</th>
                                        <th>Barang</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($servisanTerbaru ?? [] as $servisan)
                                        <tr>
                                            <td>
                                                <span
                                                    class="fw-bold text-primary">{{ $servisan->kode_servis ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-medium">{{ $servisan->pelanggan->nama ?? 'N/A' }}</div>
                                                    <small
                                                        class="content-muted">{{ $servisan->pelanggan->no_hp ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div>{{ $servisan->tipe_barang ?? 'N/A' }}</div>
                                                    <small
                                                        class="content-muted">{{ $servisan->merk_barang ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $servisan->status_badge ?? 'secondary' }}">
                                                    {{ ucfirst($servisan->status ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="content-muted">
                                                    {{ $servisan->created_at ? $servisan->created_at->format('d/m/Y') : 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('servisan.show', $servisan->id) }}"
                                                        class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if ($servisan->status === 'proses')
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                            onclick="markAsCompleted({{ $servisan->id }})"
                                                            title="Tandai Selesai">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="content-muted">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                                    <p>Belum ada data servisan</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik dan Aktivitas -->
            <div class="col-lg-4">
                <!-- Statistik Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik Status</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($servisanPerStatus))
                            @foreach ($servisanPerStatus as $stat)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="badge" style="background-color: {{ $stat['warna'] }}">
                                                {{ $stat['status'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="fw-bold">{{ $stat['jumlah'] }}</div>
                                </div>
                            @endforeach
                        @else
                            <p class="content-muted text-center">Belum ada data statistik</p>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('servisan.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Servisan Baru
                            </a>
                            <a href="{{ route('pelanggan.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Tambah Pelanggan
                            </a>
                            <a href="{{ route('servisan.index') }}?status=menunggu" class="btn btn-outline-warning">
                                <i class="fas fa-clock me-2"></i>Lihat Antrian
                            </a>
                            <a href="{{ route('servisan.index') }}?status=selesai" class="btn btn-outline-success">
                                <i class="fas fa-check me-2"></i>Siap Diambil
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistik Sparepart -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik Sparepart</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-primary">Total Stok</span>
                                </div>
                            </div>
                            <div class="fw-bold">{{ $sparepartStats['total_stok'] ?? 0 }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-danger">Sparepart Habis</span>
                                </div>
                            </div>
                            <div class="fw-bold">{{ $sparepartStats['sparepart_habis'] ?? 0 }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-warning">Segera Habis</span>
                                </div>
                            </div>
                            <div class="fw-bold">{{ $sparepartStats['sparepart_segera_habis'] ?? 0 }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-success">Nilai Stok</span>
                                </div>
                            </div>
                            <div class="fw-bold">
                                Rp {{ number_format($sparepartStats['total_nilai_stok'] ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let servisanChart;

        // Data dari controller
        const chartData = {
            mingguan: @json($statistikMingguan ?? []),
            bulanan: @json($statistikBulanan ?? []),
            tahunan: @json($statistikTahunan ?? [])
        };

        // Inisialisasi chart
        function initChart() {
            const ctx = document.getElementById('servisanChart').getContext('2d');

            servisanChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Jumlah Servisan',
                        data: [],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        yAxisID: 'y',
                        tension: 0.1
                    }, {
                        label: 'Pendapatan (Ribu)',
                        data: [],
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        yAxisID: 'y1',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Periode'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Jumlah Servisan'
                            },
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Pendapatan (Ribu)'
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Grafik Servisan dan Pendapatan'
                        }
                    }
                }
            });
        }

        // Update chart berdasarkan periode
        function updateChart(period) {
            const data = chartData[period] || [];

            const labels = data.map(item => item.periode);
            const servisanData = data.map(item => item.jumlah_servisan);
            const pendapatanData = data.map(item => Math.round(item.pendapatan / 1000)); // Dalam ribu

            servisanChart.data.labels = labels;
            servisanChart.data.datasets[0].data = servisanData;
            servisanChart.data.datasets[1].data = pendapatanData;

            servisanChart.update();

            // Update summary
            updateSummary(data);
        }

        // Update ringkasan
        function updateSummary(data) {
            const totalServisan = data.reduce((sum, item) => sum + item.jumlah_servisan, 0);
            const totalPendapatan = data.reduce((sum, item) => sum + item.pendapatan, 0);
            const rataRata = data.length > 0 ? Math.round(totalServisan / data.length) : 0;

            document.getElementById('total-servisan').textContent = totalServisan;
            document.getElementById('total-pendapatan').textContent = 'Rp ' + totalPendapatan.toLocaleString('id-ID');
            document.getElementById('rata-rata').textContent = rataRata + ' per periode';
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            initChart();
            updateChart('mingguan'); // Default ke mingguan

            // Button handlers
            document.querySelectorAll('.chart-period-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class dari semua button
                    document.querySelectorAll('.chart-period-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    // Add active class ke button yang diklik
                    this.classList.add('active');

                    // Update chart
                    const period = this.dataset.period;
                    updateChart(period);
                });
            });
        });

        // Fungsi untuk menandai servisan selesai
        function markAsCompleted(id) {
            if (confirm('Apakah Anda yakin ingin menandai servisan ini sebagai selesai?')) {
                fetch(`/servisan/${id}/complete`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Reload page untuk update data
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan sistem');
                    });
            }
        }
    </script>
@endpush
