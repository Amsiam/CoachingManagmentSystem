<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('group.classs');

    $component->assertSee('');
});
