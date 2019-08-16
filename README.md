# furbyus/sips
An information gatherer and adapter from some providers like Nemon

## Advise!
This package needs an authorisation information like secret keys and/or user tokens to work with the providers. Make sure you have access to your provider before testing it!


To make it work with Laravel, you may publish the config file "php artisan vendor:publish". This config file will be in the path "config/sips.php" and will has the format below:

```PHP
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
```
Then, you can overwrite the default values or simply define an environment var in .env file like this:

+ SIPS_NEMON_TOKEN='jklmn'
+ SIPS_NEMON_ENDPOINT='https://another.endpoint.to.connect.nemon.com/api/'
