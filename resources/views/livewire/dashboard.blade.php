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
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow px-2 row-span-2">
            <div class="rounded-lg flex items-center bg-green-200 text-green-500 justify-center  w-16 h-16">
                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path
                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                    </path>
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z">
                    </path>
                </svg>
            </div>
            <div class="px-4 py-2 text-gray-700">
                <h3 class="text-sm font-medium tracking-wide">Total Penjualan</h3>
                <p class="text-xl font-semibold">@currency($incomes)</p>
            </div>
        </div>
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow row-span-2">
            <div class="p-4 bg-indigo-400"><svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                    </path>
                </svg></div>
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
            <div class="p-4 bg-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div class="px-4 text-gray-700">
                <h3 class="text-sm tracking-wider">Total Pemasukan</h3>
                <p class="text-xl">@currency($pemasukan)</p>
            </div>
        </div>
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow row-span-2">
            <div class="p-4 bg-blue-400"><svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2">
                    </path>
                </svg></div>
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
            <div class="p-4 bg-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
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
            <div class="p-4 bg-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div class="px-4 text-gray-700">
                <h3 class="text-sm tracking-wider">Total Pegawai</h3>
                <p class="text-xl">{{ $employees->count() }}</p>
            </div>
        </div>


        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow">
            <div class="p-4 bg-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
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
