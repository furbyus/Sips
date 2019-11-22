# furbyus/sips
An information gatherer and adapter from some energetic source point providers. 
This package are intended to request and standarize the resultant data in a same format for each provider that can handle (List below).

##Installation
Using composer: #composer require furbyus/sips

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
        'another_privider' => [
            'provider-specific-config-1' => env('SIPS_NABALIA_CONFIG_1',"abcdefghi"),
            'provider-specific-config-2' => env('SIPS_NABALIA_CONFIG_2','https://endpoint.to.connect.nemon.com/api/'),
            'provider-specific-config-3' => env('SIPS_NABALIA_CONFIG_3','secret'),
        ],
    ]
];
```
Then, you can overwrite the default values or simply define an environment var in .env file like this:

+ SIPS_NEMON_TOKEN='jklmn'
+ SIPS_NEMON_ENDPOINT='https://another.endpoint.to.connect.nemon.com/api/'
This connection information will be provided by the provider company.




Providers supported:
+ Nemon 
