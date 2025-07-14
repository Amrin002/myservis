<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Servisan;
use App\Models\Sparepart;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Statistik utama
            $totalPelanggan = Pelanggan::count();
            $servisanSelesai = Servisan::where('status', 'selesai')->count();
            $servisanProses = Servisan::where('status', 'proses')->count();
            $servisanMenunggu = Servisan::where('status', 'menunggu')->count();

            // Total pendapatan dari servisan yang sudah lunas
            $totalPendapatan = Servisan::where('lunas', true)->sum('biaya_akhir');

            // Servisan terbaru (5 data terakhir)
            $servisanTerbaru = Servisan::with('pelanggan')
                ->latest()
                ->take(5)
                ->get();

            // Statistik untuk grafik
            $statistikBulanan = $this->getMonthlyStats();
            $statistikMingguan = $this->getWeeklyStats();
            $statistikTahunan = $this->getYearlyStats();
            $servisanPerStatus = $this->getStatusStats();

            //statistik spare parts
            $sparepartStats = $this->getSparepartStats();

            return view('dashboard.index', compact(
                'totalPelanggan',
                'servisanSelesai',
                'servisanProses',
                'servisanMenunggu',
                'totalPendapatan',
                'servisanTerbaru',
                'statistikBulanan',
                'statistikMingguan',
                'statistikTahunan',
                'servisanPerStatus',
                'sparepartStats'
            ));
        } catch (\Exception $e) {
            // Jika ada error, return view dengan data kosong
            return view('dashboard.index', [
                'totalPelanggan' => 0,
                'servisanSelesai' => 0,
                'servisanProses' => 0,
                'servisanMenunggu' => 0,
                'totalPendapatan' => 0,
                'servisanTerbaru' => collect(),
                'statistikBulanan' => [],
                'statistikMingguan' => [],
                'statistikTahunan' => [],
                'servisanPerStatus' => [],
                'spraepartStats' => [],
            ]);
        }
    }

    /**
     * Get statistik servisan per minggu (8 minggu terakhir)
     */
    private function getWeeklyStats()
    {
        $stats = [];

        for ($i = 7; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();

            $minggu = 'Minggu ' . $startOfWeek->format('d M');

            $jumlah = Servisan::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();

            $pendapatan = Servisan::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->where('lunas', true)
                ->sum('biaya_akhir');

            $stats[] = [
                'periode' => $minggu,
                'jumlah_servisan' => $jumlah,
                'pendapatan' => $pendapatan
            ];
        }

        return $stats;
    }

    /**
     * Get statistik servisan per bulan (12 bulan terakhir)
     */
    private function getMonthlyStats()
    {
        $stats = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulan = $date->format('M Y');

            $jumlah = Servisan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $pendapatan = Servisan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('lunas', true)
                ->sum('biaya_akhir');

            $stats[] = [
                'periode' => $bulan,
                'jumlah_servisan' => $jumlah,
                'pendapatan' => $pendapatan
            ];
        }

        return $stats;
    }

    /**
     * Get statistik servisan per tahun (5 tahun terakhir)
     */
    private function getYearlyStats()
    {
        $stats = [];

        for ($i = 4; $i >= 0; $i--) {
            $year = now()->subYears($i)->year;

            $jumlah = Servisan::whereYear('created_at', $year)->count();

            $pendapatan = Servisan::whereYear('created_at', $year)
                ->where('lunas', true)
                ->sum('biaya_akhir');

            $stats[] = [
                'periode' => $year,
                'jumlah_servisan' => $jumlah,
                'pendapatan' => $pendapatan
            ];
        }

        return $stats;
    }

    /**
     * Get statistik berdasarkan status
     */
    private function getStatusStats()
    {
        $statuses = ['menunggu', 'proses', 'selesai', 'diambil', 'dibatalkan'];
        $stats = [];

        foreach ($statuses as $status) {
            $jumlah = Servisan::where('status', $status)->count();
            $stats[] = [
                'status' => ucfirst($status),
                'jumlah' => $jumlah,
                'warna' => $this->getStatusColor($status)
            ];
        }

        return $stats;
    }

    /**
     * Get warna untuk status
     */
    private function getStatusColor($status)
    {
        $colors = [
            'menunggu' => '#6c757d',
            'proses' => '#ffc107',
            'selesai' => '#198754',
            'diambil' => '#0d6efd',
            'dibatalkan' => '#dc3545'
        ];

        return $colors[$status] ?? '#6c757d';
    }

    /**
     * API endpoint untuk mendapatkan data grafik berdasarkan periode
     */
    public function getChartData(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');

        switch ($periode) {
            case 'mingguan':
                $data = $this->getWeeklyStats();
                break;
            case 'tahunan':
                $data = $this->getYearlyStats();
                break;
            default:
                $data = $this->getMonthlyStats();
                break;
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    private function getSparepartStats()
    {
        return [
            'total_sparepart' => Sparepart::count(),
            'total_stok' => Sparepart::sum('stok'),
            'sparepart_habis' => Sparepart::where('stok', 0)->count(),
            'sparepart_segera_habis' => Sparepart::where('stok', '<=', 5)->count(),
            'total_nilai_stok' => Sparepart::sum(DB::raw('stok * harga_beli')),
        ];
    }
}
