<?php
include '../../vendor/autoload.php';
use Furbyus\Sips\SipsProvider;

/*
 * Variable declarations
 *
 */
$cups_id = (isset($_REQUEST['cups']) && !empty($_REQUEST['cups'])) ? $_REQUEST['cups'] : null;
$nemon_token = (isset($_REQUEST['token']) && !empty($_REQUEST['token'])) ? $_REQUEST['token'] : null;
$config = [
    'Nemon' => [
        'token' => $nemon_token,
        'endpoint' => 'https://url.to.nemon.example',
    ],
    'other_source' => [
        'procedencia' => "abcdefghi",
        'endpoint' => "",
        'secret' => "",
        'salt' => "",
    ],
];

/*
 *   OOPhp example instantiation:
 */

$iCups = new SipsProvider($cups_id, $config, 'screen');
$iCups->logChannel = 'screen'; //Set the log channel (only screen implemented yet)
$iCups->usarFuente('nemon'); //Use specific source, if necessary we can pass a new config on second parameter
if (!$iCups->requestData()) { //retrieveData Solicita los datos al SIPS y los guarda en el Objeto
    //Error al obtener la info...
    print('Data not gathered...');
   
}
$data = $iCups->getData();
dump($data->comercializadora->nombre);
dd('Fin');

/*
 *   Function helper example instantiation:
 */

$iCups = furSips($cups_id, $config, 'screen');

if (!$iCups->requestData()) {
    //Error al obtener la info...
    print('Data not gathered...');
    dd($iCups);
}

$result = $iCups->getData();

//Dump the object
dump($result);
