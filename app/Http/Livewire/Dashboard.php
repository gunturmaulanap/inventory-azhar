<?php

namespace App\Http\Livewire;

use App\Models\Goods;
use App\Models\Order;
use App\Models\retur;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $incomes, $pemasukan, $debt, $expense, $customers, $suppliers, $employees, $goods;

    public $selectedWeek, $selectedMonth, $startDate, $endDate;

    public function mount()
    {
        $this->customers = Customer::all();
        $this->suppliers = Supplier::all();
        $this->employees = Employee::all();
        $this->goods = Goods::all();

        $this->selectedMonth = Carbon::now()->month;
        $this->selectedWeek = Carbon::now()->weekOfMonth;
    }

    public function render()
    {
        $data = $this->getChartData();
        $donutChartData = $this->getDonutChartData();

        return view('livewire.dashboard', compact('data', 'donutChartData'));
    }

    public function getChartData()
    {
        // Menghitung awal dan akhir bulan
        $startOfMonth = Carbon::create(Carbon::now()->year, $this->selectedMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Menghitung awal dan akhir pekan dari bulan yang dipilih
        $startOfWeek = $startOfMonth->copy()->addWeeks($this->selectedWeek)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        // // Pastikan bahwa pekan yang dipilih berada dalam bulan yang dipilih
        // if ($startOfWeek->month !== $this->selectedMonth) {
        //     // Jika pekan awal berada di bulan yang berbeda, atur ulang ke pekan pertama bulan yang dipilih
        //     $this->selectedWeek = 1; // Reset pekan ke 1 jika tidak valid
        //     $startOfWeek = $startOfMonth->copy()->startOfWeek();
        //     $endOfWeek = $startOfWeek->copy()->endOfWeek();
        // }

        $incomes = Transaction::where('status', '!=', 'hutang')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->pluck('total', 'date');

        $expenses = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->pluck('total', 'date');

        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            if ($date->month == $this->selectedMonth) { // Hanya ambil data dalam bulan yang dipilih
                $labels[] = $date->translatedFormat('D');
                $incomeData[] = $incomes->get($date->toDateString(), 0);
                $expenseData[] = $expenses->get($date->toDateString(), 0);
            }
        }

        $this->incomes = Transaction::where('status', '!=', 'hutang')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('grand_total');
        $this->pemasukan = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('grand_total');
        $this->debt = Transaction::where('status', 'hutang')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('grand_total');
        $this->expense = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total');

        return [
            'labels' => $labels,
            'incomeData' => $incomeData,
            'expenseData' => $expenseData,
        ];
    }

    public function getDonutChartData()
    {
        // Menghitung awal dan akhir bulan
        $startOfMonth = Carbon::create(Carbon::now()->year, $this->selectedMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Menghitung awal dan akhir pekan dari bulan yang dipilih
        $startOfWeek = $startOfMonth->copy()->addWeeks($this->selectedWeek)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        // // Pastikan bahwa pekan yang dipilih berada dalam bulan yang dipilih
        // if ($startOfWeek->month !== $this->selectedMonth) {
        //     // Jika pekan awal berada di bulan yang berbeda, atur ulang ke pekan pertama bulan yang dipilih
        //     $this->selectedWeek = 1; // Reset pekan ke 1 jika tidak valid
        //     $startOfWeek = $startOfMonth->copy()->startOfWeek();
        //     $endOfWeek = $startOfWeek->copy()->endOfWeek();
        // }

        // Ambil semua transaksi dan barang yang terlibat
        $salesData = Transaction::where('status', '!=', 'hutang')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->with('goods.category')
            ->has('goods')
            ->get();

        // Inisialisasi array untuk kategori dan jumlah
        $categories = [];

        foreach ($salesData as $transaction) {
            foreach ($transaction->goods as $good) {
                $categoryName = $good->category->name;

                if (!isset($categories[$categoryName])) {
                    $categories[$categoryName] = 0;
                }
                $categories[$categoryName] += $good->pivot->qty; // Menggunakan qty dari pivot
            }
        }

        // Siapkan data untuk chart
        $labels = array_keys($categories);
        $data = array_values($categories);

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }


    public function updatedSelectedMonth()
    {
        $data = $this->getChartData();
        $donut = $this->getDonutChartData();
        // dd($data);
        $this->emit('refreshChart', $data, $donut);
    }

    public function updatedSelectedWeek()
    {
        $data = $this->getChartData();
        $donut = $this->getDonutChartData();
        // dd($data);
        $this->emit('refreshChart', $data, $donut);
    }
}
