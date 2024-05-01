<?php

return [
    /**
     * Default is empty.
     *    prefix => ''
     *              <x-button />
     *              <x-card />
     *
     * Renaming all components:
     *    prefix => 'mary-'
     *               <x-button />
     *               <x-card />
     *
     * Make sure to clear view cache after renaming
     *    php artisan view:clear
     *
     */
    'prefix' => '',

    /**
     * Components settings
     */
    'components' => [
        'spotlight' => [
            'class' => 'App\Support\Spotlight',
        ]
    ]
];

