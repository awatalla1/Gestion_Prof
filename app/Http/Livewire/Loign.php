<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Loign extends Component
{

    public $username;
    public $password;
    public function render()
    {
        return view('livewire.loign')->layout('layout.login-app');
    }

    public function login()
    {
        $this->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);
        if (Auth::attempt(['name' => $this->username, 'password' => $this->password])) {
            session()->flash('success', 'Login Successful');
            $user_type = Auth::user()->usertype;
            if ($user_type == 'user') {
                return redirect(route('userdashboard'));
            } else {
                return redirect(route('dashboard'));
            }
        } else {
            session()->flash('error', 'Invalid Credentials');
        }
    }

} 

