<?php

namespace App\Http\Livewire\Delivery;

use App\Models\ActDeliveryDetails;
use App\Models\ActDelivery;
use Livewire\Component;
use App\Models\Delivery;
use Illuminate\Support\Facades\DB;

use App\Models\deliveryGoods;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\Auth;

class Detail extends Component
{
    use WithFileUploads;
    public $delivery, $deliveryGoods, $input;
    public bool $isUploading = false;

    public $actDeliveries = [];
    public $today;
    public $imagePreview;
    public $detail = [
        'images' => [],
    ];

    protected $table = 'act_deliveries_details';

    public function updatedDetailImage()
    {
        if ($this->detail['image']) {
            $this->imagePreview = $this->detail['image']->temporaryUrl();
        }
    }


    public function deleteImage($index)
    {
        unset($this->detail['images'][$index]);
        $this->detail['images'] = array_values($this->detail['images']); // Reset array index
    }

    public function setImage()
    {
        if (!empty($this->detail['image'])) {
            $extension = $this->detail['image']->getClientOriginalExtension();

            // Membuat nama file yang unik
            $uniqueFileName = date('dmyHis') . '.' . $extension;

            // Tujuan langsung ke public_html/storage/images/products
            $destinationPath = base_path('public_html/storage/images/products');

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Pindahkan file
            $this->detail['image']->storeAs('temp', $uniqueFileName); // Upload ke tempat sementara
            rename(
                storage_path("app/temp/{$uniqueFileName}"),
                $destinationPath . '/' . $uniqueFileName
            );

            $this->detail['image'] = "images/products/{$uniqueFileName}";
        }
    }


    public function setDetail($index)
    {
        $this->isUploading = true;
        $this->detail = [
            'index' => $index,
            'name' => $this->deliveryGoods[$index]['name'],
            'qty' => $this->deliveryGoods[$index]['qty'],
            'unit' => $this->deliveryGoods[$index]['unit'],
            'delivered' => $this->deliveryGoods[$index]['delivered'],
            'input' => $this->actDeliveries[$index]['qty'] ?? 0,
        ];
    }

    public function updatedDetailInput($value)
    {
        // Cek jika nilai input melebihi jumlah barang yang harus dikirim
        if ($value > ($this->deliveryGoods[$this->detail['index']]['qty'] - $this->detail['delivered'])) {
            $this->dispatchBrowserEvent('error', ['message' => 'Melebihi jumlah barang yang harus dikirim']);
            $this->detail['input'] = $this->deliveryGoods[$this->detail['index']]['qty'] - $this->detail['delivered'];
        }
        // Cek jika nilai input kurang dari 0
        elseif ($this->detail['input'] < 0) {
            $this->dispatchBrowserEvent('error', ['message' => 'Input barang tidak boleh kurang dari 0']);
            $this->detail['input'] = 0;
        }
        // Cek jika nilai input sama dengan 0
        elseif ($this->detail['input'] == 0) {
            $this->dispatchBrowserEvent('error', ['message' => 'Tambahkan barang yang ingin dikirim']);
        }
    }


    public function unsetDetail()
    {
        $this->detail = [];
    }

    public function resetInput()
    {
        $this->actDeliveries = [];
        $this->detail['images'] = []; // Reset images menjadi array kosong
    }


    public function submit()
    {


        $this->actDeliveries[$this->detail['index']] = $this->actDeliveries[$this->detail['index']] ?? [
            'user_id' => Auth::user()->id,
            'goods_id' => $this->deliveryGoods[$this->detail['index']]['goods_id'],
            'qty' => $this->detail['input'],
            'name' => $this->deliveryGoods[$this->detail['index']]['name'],
            'unit' => $this->deliveryGoods[$this->detail['index']]['unit'],
            'created_at' => $this->today,
        ];

        if ($this->detail['input'] >= 1) {
            $this->actDeliveries[$this->detail['index']]['qty'] = $this->detail['input'];
        }
        $this->isUploading = true;
    }




    public function save()
    {
        $this->setImage();

        if ($this->actDeliveries !== []) {
            $created_at = Carbon::now();

            // Proses penyimpanan ActDelivery
            foreach ($this->actDeliveries as $activity) {
                DB::transaction(function () use ($created_at, $activity) {
                    ActDelivery::create([
                        'delivery_id' => $this->deliveryId,
                        'user_id' => $activity['user_id'],
                        'goods_id' => $activity['goods_id'],
                        'qty' => $activity['qty'],
                        'created_at' => $created_at,
                    ]);
                });

                // Update delivered qty for deliveryGoods
                $deliveryGood = deliveryGoods::where('delivery_id', $this->deliveryId)
                    ->where('goods_id', $activity['goods_id'])
                    ->first();

                if ($deliveryGood) {
                    $deliveryGood->increment('delivered', $activity['qty']);
                }
            }

            // Proses penyimpanan ActDeliveryDetails dengan banyak gambar
            $images = [];
            if (isset($this->detail['images']) && is_array($this->detail['images'])) {
                foreach ($this->detail['images'] as $image) {
                    $images[] = $image->store('images', 'public'); // Simpan file dan dapatkan path
                }
            }

            DB::transaction(function () use ($created_at, $images) {
                ActDeliveryDetails::create([
                    'delivery_id' => $this->deliveryId,
                    'image' => $images, // Simpan array gambar
                    'created_at' => $created_at,
                ]);
            });

            return redirect()->route('delivery.detail', ['id' => $this->deliveryId])->with('success', 'Pengiriman barang berhasil disimpan!');
        } else {
            $this->dispatchBrowserEvent('error', ['message' => 'Pilih barang yang sudah dikirim']);
        }
    }



    public $deliveryId;

    public function mount($id)
    {
        $this->today = Carbon::now();
        $this->deliveryId = $id;
        $this->delivery = Delivery::findOrFail($this->deliveryId);

        foreach ($this->delivery->goods as $good) {
            $this->deliveryGoods[] = [
                'goods_id' => $good->id,
                'name' => $good->name,
                'qty' => $good->pivot->qty,
                'unit' => $good->unit,
                'delivered' => $good->pivot->delivered,
            ];
        }
    }

    public function render()
    {
        $history = actDelivery::where('delivery_id', $this->deliveryId)
            ->orderBy('created_at', 'desc')
            ->get();
        $details = ActDeliveryDetails::where('delivery_id', $this->deliveryId)->get();

        // Perbaiki: Group by detik, bukan menit
        $groupedHistory = $history->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i:s'); // Format hingga detik
        });

        return view('livewire.delivery.detail', [
            'history' => $history,
            'groupedHistory' => $groupedHistory,
            'details' => $details,
        ]);
    }
}
