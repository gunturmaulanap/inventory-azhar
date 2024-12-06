<?php

namespace App\Http\Livewire\Master;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Admin extends Component
{
    use WithPagination; // Class dari livewire untuk fitur pagination

    public $search;
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
        $deleted = User::find($id)->delete();

        if ($deleted) {
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function render()
    {
        $data = User::whereIn('role', ['admin', 'super_admin'])
            // ketika input search terisi
            ->when($this->search, function ($query) {
                $query->search($this->search); // menjalankan query search
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.master.admin', [
            'data' => $data,
        ]);
    }
}
