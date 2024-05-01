<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('academics.courses');

    $component->assertSee('');
});
