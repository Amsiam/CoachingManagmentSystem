<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('reports.income');

    $component->assertSee('');
});
