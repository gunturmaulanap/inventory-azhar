<div>
    {{-- <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-x-4">
            <!-- Input Search -->
            <input wire:model.debounce.100ms="search"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                placeholder="Cari barang berdasarkan nama...">


            <!-- Dropdown Bulan -->
            <select wire:model="selectedMonth"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-40 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                <option value="">Pilih Bulan</option>
                @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}" @if ($month == now()->month) selected @endif>
                        {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>


        </div>

        <div class="flex items-center gap-x-6 mt-4">
            <!-- Input Tanggal Mulai -->
            <label for="startDate" class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md">
                <span class="text-xs ps-2">Dari</span>
                <input wire:model="startDate" type="date" id="startDate"
                    class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>

            <!-- Input Tanggal Akhir -->
            <label for="endDate" class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md">
                <span class="text-xs ps-2">Sampai</span>
                <input wire:model="endDate" type="date" id="endDate"
                    class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>
        </div>

    </div> --}}


    <div class="grid grid-cols-4 gap-4 px-4 mt-8 sm:grid-cols-2 sm:px-8">
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow pr-2 row-span-2">
            <div class="p-4 bg-green-400 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>

            </div>
            <div class="px-4 py-2 text-gray-700">
                <h3 class="text-sm font-medium tracking-wide">Total Penjualan</h3>
                <p class="text-xl font-semibold">@currency($incomes)</p>
            </div>
        </div>
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow row-span-2">
            <div class="p-4 bg-indigo-400 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                </svg>

            </div>
            <div class="px-4 text-gray-700">
                <h3 class="text-sm tracking-wider">Total Hutang Penjualan</h3>
                <p class="text-xl">@currency($debt)</p>
            </div>
        </div>
    </div>
    <div id="first-chart" class="w-full h-[250px] overflow-auto bg-gray-100 rounded-md mt-4"></div>
    {{-- <div id="sec-chart" class="w-full h-[250px] overflow-auto bg-gray-100 rounded-md mt-4"></div> --}}

    <div class="grid grid-cols-4 gap-4 px-4 mt-8 sm:grid-cols-2 sm:px-8">
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow row-span-2">
            <div class="p-4 bg-green-400 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
            </div>
            <div class="px-4 text-gray-700">
                <h3 class="text-sm tracking-wider">Total Pemasukan</h3>
                <p class="text-xl">@currency($pemasukan)</p>
            </div>
        </div>
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow row-span-2">
            <div class="p-4 bg-blue-400 text-white"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            </div>
            <div class="px-4 text-gray-700">
                <h3 class="text-sm tracking-wider">Total Pengeluaran</h3>
                <p class="text-xl">@currency($expense)</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 px-4 mt-8 sm:grid-cols-3 sm:px-8">
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow">
            <div class="p-4 bg-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div class="px-4 text-gray-700">
                <h3 class="text-sm tracking-wider">Total Customer</h3>
                <p class="text-xl">{{ $customers->count() }}</p>
            </div>
        </div>
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow">
            <div class="p-4 bg-green-400 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                </svg>

            </div>
            <div class="px-4 text-gray-700">
                <h3 class="text-sm tracking-wider">Total Supplier</h3>
                <p class="text-xl">{{ $suppliers->count() }}</p>
            </div>
        </div>



        <div class=" max-w-sm w-full rounded-sm  bg-white  shadow row-span-2">
            <div class="py-6" id="donut-chart"></div>


        </div>


        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow">
            <div class="p-4 bg-green-400 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                </svg>

            </div>
            <div class="px-4 text-gray-700">
                <h3 class="text-sm tracking-wider">Total Pegawai</h3>
                <p class="text-xl">{{ $employees->count() }}</p>
            </div>
        </div>


        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow">
            <div class="p-4 bg-green-400 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>

            </div>
            <div class="px-4 text-gray-700 ">
                <h3 class="text-sm tracking-wider">Total Produk</h3>
                <p class="text-xl">{{ $goods->count() }}</p>
            </div>
        </div>

    </div>
    <div class="grid grid-cols-2 gap-4 px-4 mt-8 sm:grid-cols- sm:px-8">
    </div>

</div>

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    {{-- ---------------------- FIRST CHART ---------------------- --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chart =
                Highcharts.chart('first-chart', {
                    chart: {
                        type: 'column',
                        scrollablePlotArea: {
                            minWidth: 1000,
                            scrollPositionX: 1
                        }
                    },
                    title: {
                        text: 'Pendapatan dan Pengeluaran '
                    },
                    subtitle: {
                        text: 'Berdasarkan Minggu ini'
                    },
                    xAxis: {
                        categories: {!! json_encode($first_chart['day']) !!},
                        crosshair: true,
                    },
                    yAxis: {
                        title: {
                            text: null
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0;font-size:12px">{series.name}</td>' +
                            '<td style="font-size: 10px">:</td>' +
                            '<td style="padding:0;padding-top:1px;font-size:15px"><b>&nbsp;{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true,
                            },
                        }
                    },
                    series: [{
                            name: 'Pendapatan',
                            data: {!! json_encode($first_chart['income']) !!},
                            color: 'blue',
                        },
                        {
                            name: 'Pengeluaran',
                            data: {!! json_encode($first_chart['expense']) !!},
                            color: 'green',
                        },
                    ],
                    credits: {
                        enabled: false
                    }
                });
        });



        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/sales-percentage-by-category') // Sesuaikan dengan route Anda
                .then(response => response.json())
                .then(data => {
                    Highcharts.chart('donut-chart', {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: 'Barang Terjual'
                        },
                        tooltip: {
                            pointFormat: '{point.name}: <b>{point.y:.1f}%</b>'
                        },
                        series: [{
                            name: 'Kategori',
                            colorByPoint: true,
                            data: data
                        }],
                        credits: {
                            enabled: false
                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>
@endpush
