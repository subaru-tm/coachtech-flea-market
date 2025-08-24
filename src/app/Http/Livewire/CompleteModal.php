<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Dealing;

class CompleteModal extends Component
{

    public $dealing;

    public $user;

    public $showModal=false;

    public function openModal(){
        $this->showModal=true;
    }

    public function closeModal(){
        $this->showModal=false;
    }


    public function render()
    {
        if( $this->dealing->status == "completed" )
        {
            $this->showModal=true;
        }

        return view('livewire.complete-modal');
    }
}
