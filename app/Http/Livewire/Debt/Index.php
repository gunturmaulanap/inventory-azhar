<?php

namespace App\Http\Livewire\Debt;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // Class dari livewire untuk fitur pagination

    public $search, $startDate, $endDate;
    public $perPage = 10;

    protected $listeners = [ // listeners handler untuk menjalankan delete setelah confirm
        'confirm' => 'delete',
    ];

    public function validationDelete($id) // function menjalankan confirm delete
    {
        $this->dispatchBrowserEvent('validation', [
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $deleted = Transaction::find($id)->delete();

        if ($deleted) {
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function render()
    {
        $data = Transaction::where('status', 'hutang')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.debt.index', [
            'data' => $data,
        ]);
    }
}
