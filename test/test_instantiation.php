<?php
include '../vendor/autoload.php';
use Furbyus\Sips\SipsProvider;

/*
 * Variable declarations
 *
 */
$cups_id = (isset($_REQUEST['cups']) && !empty($_REQUEST['cups'])) ? $_REQUEST['cups'] : null;
$config = [
    'Nemon' => [
        'token' => 'abcdefghi',
        'endpoint' => 'https://endpoint.to.connect.nemon.com/api/',
    ],
    'Nabalia' => [
        'procedencia' => "abcdefghi",
        'endpoint' => "",
        'secret' => "",
        'salt' => "",
    ],
];

/*
 *   OOPhp example instantiation:
 */

/*
$iCups = new SipsProvider($cups_id);
$iCups->logChannel = 'screen';          //Set the log channel (only screen implemented yet)
$iCups->usarFuente('nemon', $config);   //Use specific source, if necessary we can pass a new config on second parameter
if (!$iCups->requestData()) {           //retrieveData Solicita los datos al SIPS y los guarda en el Objeto
//Error al obtener la info...
print('Data not gathered...');
dd($iCups);
}
 */

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
