<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\User;

use Xenon\LaravelBDSms\Facades\SMS;

new
#[Layout('layouts.guest')]
class extends Component {
    use Toast;

    public LoginForm $form;

    public string $otp;

    public string $inputOtp;



    public function login()
    {
        $this->validate();

        //check if user exists
        $user=User::where("email",$this->form->email)->first();
        if(!$user){
            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }



        //check password
        if(!Hash::check($this->form->password,$user->password)){
            throw ValidationException::withMessages([
                'form.password' => trans('auth.failed'),
            ]);
        }

        //send otp
        $this->otp=rand(100000,999999);

        SMS::shoot($user->mobile, "Your OTP is " . $this->otp);

        $this->toast(
            type: 'Success',
            title: 'OTP sent successfully!',
            position: 'toast-top toast-end',       // Optional (any icon)
            css: 'alert-success text-white',
        );



        // $this->form->authenticate();

        // Session::regenerate();

        // $this->toast(
        //     type: 'Success',
        //     title: 'Loged In successfully!',
        //     position: 'toast-top toast-end',       // Optional (any icon)
        //     css: 'alert-success text-white',
        // );

        // $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function verifyOtp()
    {

        if($this->inputOtp!=$this->otp){
            throw ValidationException::withMessages([
                'inputOtp' => 'Invalid OTP',
            ]);
        }

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
    @if(!$otp)
    <x-form wire:submit="login">
        <x-input label="Email" wire:model="form.email" />
        <x-input label="Password" type="password" wire:model="form.password" />

        <x-slot:actions>
            <x-button label="Login" class="btn-primary w-full" type="submit" spinner="login" />

        </x-slot:actions>
    </x-form>
    @else
    <x-form wire:submit="verifyOtp">
        <x-input label="OTP" wire:model="inputOtp" />

        <x-slot:actions>
            <x-button label="Verify" class="btn-primary w-full" type="submit" spinner="verifyOtp" />

        </x-slot:actions>
    </x-form>
    @endif
</div>
