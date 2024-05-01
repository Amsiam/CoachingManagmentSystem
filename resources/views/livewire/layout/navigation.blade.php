<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<x-nav sticky full-width>

    <x-slot:brand>
        {{-- Drawer toggle for "main-drawer" --}}
        <label for="main-drawer" class="lg:hidden mr-3">
            <x-icon name="o-bars-3" class="cursor-pointer" />
        </label>
        <div>{{ config('app.name', 'Laravel') }}</div>
    </x-slot:brand>

    {{-- Right side actions --}}
    <x-slot:actions>
        <x-theme-toggle class="btn btn-sm btn-circle btn-ghost" />
        <x-dropdown>
            <x-slot:trigger>
                @if (auth()->user()->image)


                <x-button class="btn-circle btn-sm" responsive>
                    <img src="{{asset('storage/'.auth()->user()->image)}}" class="w-8 h-8 bg-gray-300 rounded-full shrink-0"/>
                </x-button>

                @else

                <x-button icon="o-user" class="btn-circle btn-sm" responsive />
                @endif
            </x-slot:trigger>

            <x-menu-item wire:click.stop="logout" title="Logout" />
            </x-dropdown>
    </x-slot:actions>
</x-nav>
