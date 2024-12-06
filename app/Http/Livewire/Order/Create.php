<?php

namespace App\Http\Livewire\Order;

use App\Models\Goods;
use App\Models\Order;
use Livewire\Component;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use WithFileUploads;

    public $order, $supplier, $delivery = false;
    public $searchSupplier, $search, $byCategory;
    public $goodOrders = [];
    public $imagePreview;

    public function updatedOrderImage()
    {
        if ($this->order['image']) {
            $this->imagePreview = $this->order['image']->temporaryUrl();
        }
    }

    public function deleteImage()
    {
        $this->order['image'] = null;
        $this->imagePreview = null;
    }

    public function updatedDelivery($value)
    {
        $this->order['status'] = $value ? 'pengiriman' : 'selesai';
    }

    protected $rules = [
        'order.company' => 'required',
        'order.name' => 'required',
        'order.phone' => 'required',
    ];

    public function messages() //function untuk pesan error
    {
        return [
            'order.company.required' => 'Nama perusahaan harus diisi.',
            'order.name.required' => 'Nama supplier harus diisi.',
            'order.phone.required' => 'Nomor Supplier harus diisi.',
        ];
    }

    public function updated($fields) //function dari livewire untuk real-time validation
    {
        $this->validateOnly($fields);
    }

    public function setSupplier($suppleirId)
    {
        $supplier = Supplier::find($suppleirId);
        $this->order['supplier_id'] = $supplier->id;
        $this->order['company'] = $supplier->company;
        $this->order['name'] = $supplier->name;
        $this->order['phone'] = $supplier->phone;
        $this->order['address'] = $supplier->address;
        $this->order['keterangan'] = $supplier->keterangan;

        $this->validate();
    }

    public function updatedOrderCompany()
    {
        if (isset($this->order['supplier_id'])) {
            $this->order['supplier_id'] = null;
        }
    }

    public function updatedOrderName()
    {
        if (isset($this->order['supplier_id'])) {
            $this->order['supplier_id'] = null;
        }
    }

    public function updetedOrderPhone()
    {
        if (isset($this->order['supplier_id'])) {
            $this->order['supplier_id'] = null;
        }
    }

    public function updatedOrderAddress()
    {
        if (isset($this->order['supplier_id'])) {
            $this->order['supplier_id'] = null;
        }
    }

    public function addGood($good_id)
    {
        $good = Goods::find($good_id);

        if (!$good) {
            $this->dispatchBrowserEvent('error', ['message' => 'Barang tidak ditemukan']);
            return;
        }

        // Cek apakah barang sudah ada di goodOrders
        $productKey = collect($this->goodOrders)->search(function ($item) use ($good) {
            return $item['goods_id'] == $good->id;
        });

        if ($productKey !== false) {
            // Jika barang sudah ada, tampilkan pesan error
            $this->dispatchBrowserEvent('error', ['message' => 'Barang sudah terinput']);
        } else {
            // Jika barang belum ada, tambahkan sebagai entri baru
            $this->goodOrders[] = [
                'goods_id' => $good->id,
                'name' => $good->name,
                'cost' => $good->cost,
                'qty' => 1,
                'unit' => $good->unit,
                'subtotal' => $good->cost * 1,
            ];

            $this->calculateTotal();
            $this->dispatchBrowserEvent('success', ['message' => 'Barang berhasil ditambahkan']);
        }
    }


    public function updatedGoodOrders($value, $propertyName)
    {
        $this->calculateSubtotal(explode('.', $propertyName)[0]);
    }

    public function calculateSubtotal($index)
    {
        $this->goodOrders[$index]['subtotal'] = $this->goodOrders[$index]['cost'] * $this->goodOrders[$index]['qty'];
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->order['total'] = array_sum(array_column($this->goodOrders, 'subtotal'));
    }

    public function increment($index)
    {
        $this->goodOrders[$index]['qty'] += 1;
        $this->goodOrders[$index]['subtotal'] = $this->goodOrders[$index]['cost'] * $this->goodOrders[$index]['qty'];
        $this->calculateTotal();
    }

    public function decrement($index)
    {
        if ($this->goodOrders[$index]['qty'] <= 1) {
            $this->goodOrders[$index]['qty'] = 1;
            $this->dispatchBrowserEvent('error', ['message' => 'Tidak bisa kurang dari 1']);
        } else {
            $this->goodOrders[$index]['qty'] -= 1;
            $this->goodOrders[$index]['subtotal'] = $this->goodOrders[$index]['cost'] * $this->goodOrders[$index]['qty'];
            $this->calculateTotal();
        }
    }

    public function deleteGood($index)
    {
        unset($this->goodOrders[$index]);
        array_values($this->goodOrders);
        $this->calculateTotal();
    }

    public function setImage()
    {
        if (!empty($this->order['image'])) {
            $extension = $this->order['image']->getClientOriginalExtension();

            // Membuat nama file yang unik
            $uniqueFileName = date('dmyHis') . '.' . $extension;

            // Upload gambar dan simpan path ke dalam database
            $imagePath = $this->order['image']->storeAs('images/products', $uniqueFileName, 'public');
            $this->order['image'] = $imagePath;
        }
    }
    public function resetInput()
    {
        return redirect()->route('order.create');
    }

    public function save()
    {
        // Validasi: Jika barang belum diisi
        if (empty($this->goodOrders)) {
            $this->dispatchBrowserEvent('error', ['message' => 'Silakan isi data barang terlebih dahulu']);
            return false;
        }

        // Validasi: Jika data supplier belum lengkap
        if (empty($this->order['name']) || empty($this->order['phone']) || empty($this->order['company'])) {
            $this->dispatchBrowserEvent('error', ['message' => 'Silakan isi data supplier terlebih dahulu']);
            return false;
        }

        // Cek atau buat supplier baru
        $supplier = Supplier::firstOrCreate(
            ['phone' => $this->order['phone']],
            [
                'company' => $this->order['company'],
                'name' => $this->order['name'],
                'address' => $this->order['address'] ?? '',
                'keterangan' => $this->order['keterangan'] ?? '',
            ]
        );

        // Mengatur supplier_id
        $this->order['supplier_id'] = $supplier->id;

        $this->setImage();

        // Membuat Order baru
        $order = Order::create([
            'user_id' => Auth::user()->id,
            'supplier_id' => $this->order['supplier_id'],
            'company' => $this->order['company'],
            'name' => $this->order['name'],
            'phone' => $this->order['phone'],
            'address' => $this->order['address'] ?? '',
            // 'keterangan' => $this->order['keterangan'] ?? '',
            'total' => $this->order['total'],
            'status' => $this->order['status'] ?? 'selesai',
            'image' => $this->order['image'] ?? null,
        ]);

        foreach ($this->goodOrders as $good) {
            // Menambahkan data ke tabel pivot
            $order->goods()->attach($good['goods_id'], [
                'cost' => $good['cost'],
                'qty' => $good['qty'],
                'subtotal' => $good['subtotal'],
            ]);

            // Melakukan increment stok
            Goods::where('id', $good['goods_id'])->increment('stock', $good['qty']);
        }

        if ($order) {
            return redirect()->route('order.index')->with('success', 'Pesanan berhasil!');
        }
    }



    public function render()
    {
        $suppliers = Supplier::when($this->searchSupplier, function ($query) {
            $query->search($this->searchSupplier); // menjalankan query search
        })
            ->orderBy('created_at', 'desc')
            ->get();

        $goods = Goods::when($this->search, function ($query) {
            $query->search($this->search); // menjalankan query search
        })->when($this->byCategory, function ($query) {
            $query->where('category_id', $this->byCategory); // menjalankan query by Category
        })
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::all();

        return view('livewire.order.create', [
            'suppliers' => $suppliers,
            'goods' => $goods,
            'categories' => $categories,
        ]);
    }
}
