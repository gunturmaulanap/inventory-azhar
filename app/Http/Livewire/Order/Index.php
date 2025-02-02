<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Goods;
use Illuminate\Support\Facades\Log;



class Index extends Component
{
    use WithPagination; // Class dari livewire untuk fitur pagination

    public $search, $startDate, $endDate;
    public $perPage = 10;

    public function setPerPage($value)
    {
        $this->perPage = $value;
    }

    protected $listeners = [ // listeners handler untuk menjalankan delete setelah confirm
        'confirm' => 'delete',
        'perpage' => 'setPerPage',
    ];

    public function validationDelete($id) // function menjalankan confirm delete
    {
        $this->dispatchBrowserEvent('validation', [
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $goods = Order::find($id)->goods;

        $deleted = Order::find($id)->delete();
        if ($deleted) {
            foreach ($goods as $good) {
                Log::info('Debug event triggered: ', ['id' => $good->id]);
                Goods::where('id', $good->id)->decrement('stock', $good->pivot->qty);
            }
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function render()
    {
        $orders = Order::when($this->search, function ($query) {
            return $query->whereHas('supplier', function ($subquery) {
                $subquery->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('company', 'like', '%' . $this->search . '%');
            });
        })
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.order.index', [
            'orders' => $orders,
        ]);
    }
}
