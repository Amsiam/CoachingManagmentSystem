<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts.guest')]
class extends Component {
    use Toast;

    public LoginForm $form;

    public function login()
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->toast(
            type: 'Success',
            title: 'Loged In successfully!',
            position: 'toast-top toast-end',       // Optional (any icon)
            css: 'alert-success text-white',
        );

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

};

?>

<div class="w-96 border border-gray-400 p-10 rounded-lg">
    <h1 class="text-center font-bold text-xl">Login</h1>
    <x-form wire:submit="login">
        <x-input label="Email" wire:model="form.email" />
        <x-input label="Password" type="password" wire:model="form.password" />

        <x-slot:actions>
            <x-button label="Login" class="btn-primary w-full" type="submit" spinner="login" />

        </x-slot:actions>
    </x-form>
</div>
