<?php

return [
    'channels' => [
        'time-track' => [
            'driver' => 'daily',
            'path' => storage_path('logs/time-track.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ]
    ],

];
