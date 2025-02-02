<?php

namespace App\Http\Livewire\Master;

use App\Models\Employee as ModelsEmployee;
use Livewire\Component;
use Livewire\WithPagination;

class Employee extends Component
{
    use WithPagination; // Class dari livewire untuk fitur pagination

    public $search;
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
        $deleted = ModelsEmployee::find($id)->delete();

        if ($deleted) {
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function render()
    {
        $data = ModelsEmployee::when($this->search, function ($query) {
            $query->search($this->search);
        })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.master.employee', [
            'data' => $data,
        ]);
    }
}
