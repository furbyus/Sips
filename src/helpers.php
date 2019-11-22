<?php
use Furbyus\Sips\SipsProvider;

if (!function_exists('register_fn')) {
    function register_fn($fn_name, $fn)
    {
        global $$fn_name;
        $$fn_name = $fn;
    }
}
/*
 *   Example declarations of test functions sum_test, the two ways...
 */
register_fn('sum_test', function ($v1, $v2) {
    return $v1 + $v2;
});
if (!function_exists('sum_test')) {
    function sum_test($v1, $v2)
    {
        return $v1 + $v2;
    }
}

/*
 *   Helper functions
 */

/*
 *   Function $o2a (Object to Array)
 */
register_fn('o2a', function ($data) {
    return (array) $data;
});
/*
 *   Function $a2o (Array to Object)
 */
register_fn('a2o', function ($data) {
    return (object) $data;
});
if (!function_exists('d')) {
    function d($v1)
    {
        return dump($v1);
    }
}

if (!function_exists('furSips')) {
    function furSips($cups = null, $config = null, $logChannel = null)
    {
        if(is_null($config) && defined('config')){
            $config = config('sips');
        }
        return new SipsProvider($cups, $config, $logChannel);
    }
}
