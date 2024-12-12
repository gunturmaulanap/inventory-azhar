    <div>
    <x-slot name="title">{{ __('Riwayat Transaksi') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Transaksi', 'Riwayat Transaksi'];
        @endphp
        @foreach ($breadcumb as $item)
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <span
                    class="block transition hover:text-gray-700 @if ($loop->last) text-gray-950 font-medium @endif">
                    {{ $item }}
                </span>
            </li>
        @endforeach
    </x-slot>

    {{-- Header Table (Filter Search, Per Page, Date Filter) --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-x-4">
            <input wire:model="search"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                placeholder="Cari customer...">
            <select wire:model="perPage"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-20 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
            </select>
        </div>
        <div class="flex items-center gap-x-6">
            <label for="startDate" class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md">
                <span class="text-xs ps-2">Dari</span>
                <input wire:model="startDate" type="date" id="startDate"
                    class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>
            <label for="endDate" class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md">
                <span class="text-xs ps-2">Sampai</span>
                <input wire:model="endDate" type="date" id="endDate"
                    class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="rounded-md border bg-white">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 px-4 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Nama Customer
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Jumlah Barang
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Tagihan
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Status
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Pengiriman
                            </span>
                        </th>
                        <th class="h-10 px-2 text-right">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Tanggal Transaksi
                            </span>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr class="border-b transition-colors hover:bg-gray-50">
                            <td class="p-2 px-4 w-[20%]">
                                {{ $item->name }}
                            </td>
                            <td class="p-2 text-center">
                                {{ $item->goods()->count() }}
                            </td>
                            <td class="p-2 text-center">
                                @currency($item->total)
                            </td>
                            <td
                                class="p-2 text-center capitalize @if ($item->status !== 'selesai') text-yellow-600 @else text-green-600 @endif">
                                {{ $item->status }}
                            </td>
                            <td class="p-2 text-center">
                                <span class="capitalize">{{ $item->delivery->status ?? '' }}</span>
                            </td>
                            <td class="px-4 text-right">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-2">
                                <div class="flex items-center gap-x-4 justify-center">
                                    <a href="{{ route('transaction.detail', ['id' => $item->id]) }}"
                                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-sky-500 text-white text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="size-3">
                                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                            <path fill-rule="evenodd"
                                                d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Detail
                                    </a>
                                    @if (auth()->user()->role === 'super_admin')
                                    <button wire:click="validationDelete({{ $item->id }})"
                                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" class="size-3">
                                            <path fill-rule="evenodd"
                                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Hapus
                                    </button>

                                    
                                    @endif
                                    
                                     
                                    @if ($item->status == 'draft')
                                        <a href="{{ route('master.update-customer', ['id' => $item->id]) }}"
                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                            Ubah
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="py-2 text-red-500 text-center">
                                    Data tidak ditemukan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="pt-4">
        {{ $data->links() }}
    </div>

</div>
