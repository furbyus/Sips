<?php

return [
    'config'=>[
        'Nemon' => [
            'token' => env('SIPS_NEMON_TOKEN','abcdefghi'),
            'endpoint' =>  env('SIPS_NEMON_ENDPOINT','https://endpoint.to.connect.nemon.com/api/'),
        ],
        'Nabalia' => [
            'procedencia' => env('SIPS_NABALIA_TOKEN',"abcdefghi"),
            'endpoint' => env('SIPS_NABALIA_ENDPOINT','https://endpoint.to.connect.nemon.com/api/'),
            'secret' => env('SIPS_NABALIA_SECRET','secret'),
            'salt' => env('SIPS_NABALIA_SALT','yourSalt'),
        ],
    ]
];