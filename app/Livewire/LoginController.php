<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class LoginController extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $this->addError('email', 'Tu cuenta ha sido desactivada. Comunícate con un administrador.');
                return;
            }

            session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        $this->addError('email', 'Las credenciales proporcionadas no coinciden con nuestros registros.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
