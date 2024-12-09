<?php

namespace App\Http\Livewire;

use App\Models\Goods;
use App\Models\Order;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\retur;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $incomes, $pemasukan, $debt, $expense, $customers, $suppliers, $employees, $goods;

    public $selectedMonth, $startDate, $endDate;

    public function mount()
    {
        $this->incomes = Transaction::where('status', '!=', 'hutang')->sum('grand_total');
        $this->pemasukan = Transaction::sum('grand_total');
        $this->debt = Transaction::where('status', 'hutang')->sum('grand_total');
        $this->expense = Order::sum('total');
        $this->customers = Customer::all();
        $this->suppliers = Supplier::all();
        $this->employees = Employee::all();
        $this->goods = Goods::all();
    }

    public function getTransactions() {}

    public function getOrders() {}

    public function render()
    {
        $transaction =
            DB::table('transactions')
            ->where('status', '!=', 'hutang')
            ->when($this->selectedMonth, function ($query) {
                // Jika bulan dipilih, filter berdasarkan bulan tersebut
                $startDate = now()->month($this->selectedMonth)->startOfMonth();
                $endDate = now()->month($this->selectedMonth)->endOfMonth();

                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                // Jika tanggal mulai dan tanggal akhir diisi, filter berdasarkan tanggal tersebut
                return $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->when(!$this->selectedMonth && !$this->startDate && !$this->endDate, function ($query) {
                // Jika tidak ada input, ambil data dari 7 hari terakhir
                return $query->where('created_at', '>=', now()->subDays(7));
            })
            ->select(
                DB::raw('CAST(SUM(grand_total) AS SIGNED) AS total'),
                DB::raw('DAYNAME(created_at) AS day'),
                DB::raw('DATE(created_at) AS date')  // Add a date field to help with grouping and ordering
            )
            ->groupBy(DB::raw('DATE(created_at), DAYNAME(created_at)'))  // Group by both date and day name
            ->orderBy('date', 'asc')  // Order by date
            ->get();

        $order =
            DB::table('orders')
            ->when($this->selectedMonth, function ($query) {
                // Jika bulan dipilih, filter berdasarkan bulan tersebut
                $startDate = now()->month($this->selectedMonth)->startOfMonth();
                $endDate = now()->month($this->selectedMonth)->endOfMonth();

                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                // Jika tanggal mulai dan tanggal akhir diisi, filter berdasarkan tanggal tersebut
                return $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->when(!$this->selectedMonth && !$this->startDate && !$this->endDate, function ($query) {
                // Jika tidak ada input, ambil data dari 7 hari terakhir
                return $query->where('created_at', '>=', now()->subDays(7));
            })
            ->select(
                DB::raw('CAST(SUM(total) AS SIGNED) AS total'),
                DB::raw('DAYNAME(created_at) AS day'),
                DB::raw('DATE(created_at) AS date')  // Add a date field to help with grouping and ordering
            )
            ->groupBy(DB::raw('DATE(created_at), DAYNAME(created_at)'))  // Group by both date and day name
            ->orderBy('date', 'asc')  // Order by date
            ->get();

        $first_chart = [
            'income' => $transaction->pluck('total'),
            'expense' => $order->pluck('total'),
            'day' => $transaction->pluck('day'), // Mengambil nama hari untuk xAxis
        ];

        $sec_chart = [
            'income' => $transaction->sum('total'),
            'expense' => $order->sum('total'),
        ];

        return view('livewire.dashboard', [
            'first_chart' => $first_chart,
            'sec_chart' => $sec_chart,
        ]);
    }
}
