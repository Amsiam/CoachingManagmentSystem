<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('student.list');

    $component->assertSee('');
});
