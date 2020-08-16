<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Environment Label Bar Enabled
    |--------------------------------------------------------------------------
    |
    | This options disables the environment bar.
    |
    */
    'enabled' => env('ENVIRONMENT_LABEL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Label
    |--------------------------------------------------------------------------
    |
    | This options decides how to display the label in the bar.
    |
    */
    'label' => 'Environment: ',

    /*
    |--------------------------------------------------------------------------
    | Environments
    |--------------------------------------------------------------------------
    |
    | This options configures how environments are to be displayed.
    |
    */
    'environments' => [
        'local' => [
            'show' => true,
            'name' => 'Local',
            'text_color' => '#fff',
            'background_color' => '#808e9b',
        ],
        'development' => [
            'show' => true,
            'name' => 'Development',
            'text_color' => '#fff',
            'background_color' => '#485460',
        ],
        'staging' => [
            'show' => true,
            'name' => 'Staging',
            'text_color' => '#fff',
            'background_color' => '#575fcf',
        ],
    ]
];
