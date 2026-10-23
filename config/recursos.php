<?php

return [
    'tipos' => [

        'audio' => [
            'extensiones' => ['mp3', 'wav', 'm4a'],
            'max_kb' => 51200, // 50 MB
        ],

        'imagen' => [
            'extensiones' => ['jpg', 'jpeg', 'png', 'webp'],
            'max_kb' => 10240, // 10 MB
            'imagen' => true,
        ],

        'video' => [
            'extensiones' => ['mp4', 'webm'],
            'max_kb' => 204800, // 200 MB
        ],

    ],
];
