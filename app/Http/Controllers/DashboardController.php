<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Goods;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getSalesPercentageByCategory()
    {
        // Ambil semua transaksi dengan relasi barang dan kategori
        $transactions = Transaction::where('status', '!=', 'hutang')->get();

        // Inisialisasi array untuk menyimpan total penjualan per kategori
        $categorySales = [];

        // Hitung total penjualan per kategori
        foreach ($transactions as $transaction) {
            foreach ($transaction->goods as $good) {
                $categoryName = $good->category->name;
                $quantitySold = $good->pivot->qty; // Ambil qty dari pivot table

                if (!isset($categorySales[$categoryName])) {
                    $categorySales[$categoryName] = 0;
                }
                $categorySales[$categoryName] += $quantitySold;
            }
        }

        // Hitung total keseluruhan untuk menghitung persentase
        $totalSales = array_sum($categorySales);

        // Siapkan data untuk chart
        $chartData = [];
        foreach ($categorySales as $category => $quantity) {
            $chartData[] = [
                'name' => $category,
                'y' => ($totalSales > 0) ? ($quantity / $totalSales) * 100 : 0, // Hitung persentase
            ];
        }

        // Kembalikan data ke view atau format JSON
        return response()->json($chartData);
    }

    public function index()
    {
        $transaction = DB::table('transactions')
            ->where('status', '!=', 'hutang')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('CAST(SUM(grand_total) AS SIGNED) AS total'),
                DB::raw('DAYNAME(created_at) AS day'),
                DB::raw('DATE(created_at) AS date')  // Add a date field to help with grouping and ordering
            )
            ->groupBy(DB::raw('DATE(created_at), DAYNAME(created_at)'))  // Group by both date and day name
            ->orderBy('date', 'asc')  // Order by date
            ->get();

        $order = DB::table('orders')
            ->where('created_at', '>=', now()->subDays(7))
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

        return view('dashboard', [
            'incomes' => Transaction::where('status', '!=', 'hutang')->sum('grand_total'),
            'pemasukan' => Transaction::sum('grand_total'),
            'debt' => Transaction::where('status', 'hutang')->sum('grand_total'),
            'expense' => Order::sum('total'),
            'customers' => Customer::all(),
            'suppliers' => Supplier::all(),

            'employees' => Employee::all(),
            'goods' => Goods::all(),
            'first_chart' => $first_chart,
            'sec_chart' => $sec_chart,
        ]);
    }
}
