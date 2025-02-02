<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;





Route::get('/design', function () {
    return view('design');
})->middleware(['auth', 'verified'])->name('design');


// Route::get('/customer/login', [AuthenticatedSessionController::class, 'create'])
// ->name('customer.login');
// Route::post('/customer/login', [AuthenticatedSessionController::class, 'store'])->name('customer.login');

Route::middleware(['auth:customer'])->group(function () {
    Route::get('customer/{id}', App\Http\Livewire\Master\CustomerDetail::class)
        ->name('customer.detail'); // Nama rute harus sesuai

    Route::get('customer/detail-transaksi/{id}', App\Http\Livewire\Transaction\Detail::class)->name('customer.transaction.detail');
    Route::get('customer/pengiriman-barang/{id}', App\Http\Livewire\Delivery\Detail::class)->name('customer.delivery.detail');
});


Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/sales-percentage-by-category', [DashboardController::class, 'getSalesPercentageByCategory']);


    // SUPER ADMIN ACCESS
    Route::middleware(['role:super_admin'])->group(function () {
        // Data Admin
        Route::get('data-admin', App\Http\Livewire\Master\Admin::class)->name('master.admin');
        Route::get('tambah-data-admin', App\Http\Livewire\Master\AdminForm::class)->name('master.create-admin');
        Route::get('ubah-data-admin/{id}', App\Http\Livewire\Master\AdminForm::class)->name('master.update-admin');

        // Data Pegawai
        Route::get('data-employee', App\Http\Livewire\Master\Employee::class)->name('master.employee');
        Route::get('tambah-data-pegawai', App\Http\Livewire\Master\EmployeeForm::class)->name('master.create-employee');
        Route::get('ubah-data-pegawai/{id}', App\Http\Livewire\Master\EmployeeForm::class)->name('master.update-employee');

        Route::get('absensi', App\Http\Livewire\Attendace\Index::class)->name('attendance.index');
        Route::get('absensi-baru', App\Http\Livewire\Attendace\Create::class)->name('attendance.create');
        Route::get('absensi-hari-ini/{id}', App\Http\Livewire\Attendace\Create::class)->name('attendance.update');
        Route::get('detail-absensi/{id}', App\Http\Livewire\Attendace\Detail::class)->name('attendance.detail');

        // Data Supplier
        Route::get('data-supplier', App\Http\Livewire\Master\Supplier::class)->name('master.supplier');
        Route::get('tambah-data-supplier', App\Http\Livewire\Master\SupplierForm::class)->name('master.create-supplier');
        Route::get('ubah-data-supplier/{id}', App\Http\Livewire\Master\SupplierForm::class)->name('master.update-supplier');

        // Data Customer
        Route::get('data-customer', App\Http\Livewire\Master\Customer::class)->name('master.customer');
        Route::get('tambah-data-customer', App\Http\Livewire\Master\CustomerForm::class)->name('master.create-customer');
        Route::get('ubah-data-customer/{id}', App\Http\Livewire\Master\CustomerForm::class)->name('master.update-customer');
        Route::get('detail-data-customer/{id}', App\Http\Livewire\Master\CustomerDetail::class)->name('master.detail-customer');


        // Data Laporan
        Route::get('laporan-penjualan', App\Http\Livewire\Report\Index::class)->name('report.index');
        Route::get('laporan-barang', App\Http\Livewire\Report\Goods::class)->name('report.goods');
    });

    // ADMIN ACCESS
    Route::middleware(['role:super_admin,admin'])->group(function () {

        // Data Hutang
        Route::get('data-hutang', App\Http\Livewire\Debt\Index::class)->name('debt.index');

        // Transaksi
        Route::get('transaksi', App\Http\Livewire\Transaction\Create::class)->name('transaction.create');
        Route::get('riwayat-transaksi', App\Http\Livewire\Transaction\History::class)->name('transaction.history');
        Route::get('detail-transaksi/{id}', App\Http\Livewire\Transaction\Detail::class)->name('transaction.detail');
        Route::get('pengiriman-barang', App\Http\Livewire\Delivery\Index::class)->name('delivery.index');
        Route::get('pengiriman-barang/{id}', App\Http\Livewire\Delivery\Detail::class)->name('delivery.detail');
        Route::get('invoice/{id}', App\Http\Livewire\Transaction\Invoice::class)->name('transaction.invoice');
        Route::get('mini-invoice/{id}', App\Http\Livewire\Transaction\MiniInvoice::class)->name('transaction.mini-invoice');

        // Barang
        Route::get('brand-baru', App\Http\Livewire\Brand\Form::class)->name('goods.brand-create');
        Route::get('ubah-brand/{id}', App\Http\Livewire\Brand\Form::class)->name('goods.brand-update');
        Route::get('kategori-baru', App\Http\Livewire\Category\Form::class)->name('goods.category-create');
        Route::get('ubah-kategori/{id}', App\Http\Livewire\Category\Form::class)->name('goods.category-update');
        Route::get('data-barang', App\Http\Livewire\Goods\Data::class)->name('goods.data');
        Route::get('tambah-data-barang', App\Http\Livewire\Goods\Form::class)->name('goods.create');
        Route::get('ubah-data-barang/{id}', App\Http\Livewire\Goods\Form::class)->name('goods.update');
        Route::get('kelola-data-barang', App\Http\Livewire\Goods\Management::class)->name('goods.management');
        Route::get('retur-barang', App\Http\Livewire\Retur\Create::class)->name('goods.retur');
        Route::get('detail-retur/{id}', App\Http\Livewire\Retur\Detail::class)->name('goods.retur-detail');

        // Data Order
        Route::get('data-order', App\Http\Livewire\Order\Index::class)->name('order.index');
        Route::get('order', App\Http\Livewire\Order\Create::class)->name('order.create');
        Route::get('detail-order/{id}', App\Http\Livewire\Order\Detail::class)->name('order.detail');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
