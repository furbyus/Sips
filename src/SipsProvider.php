<?php

namespace Furbyus\Sips;

use Furbyus\Sips\Interfaces\iSipsProvider;
use Furbyus\Sips\Lib\AES;
use Furbyus\Sips\Models\SuministroNabalia;
use Furbyus\Sips\Models\SuministroNemon;
//this has to be changed because it uses mcrypt library which is deprecated in php > 7.0

/**
 * Description of SipsProvider
 *
 * @author Francesc AguilarMartinez
 */
class SipsProvider implements iSipsProvider
{
    /*
     * @property string $cups <p>CUPS del suministro del que queremos la información</p>
     */

    public $cups;
    /*
     * @property array $sipsConfig <p>Configuraciones para los Proveedores disponibles y habilitados ej: accessKey </p>
     */
    public $sipsConfig;
    /*
     * @property array $logChannel <p>Canal por el que se envia el log ej: screen, ttyn...</p>
     */
    public $logChannel;
    /*
     * @property array $_fuentesDisponibles <p>Proveedores disponibles para habilitar o deshabilitar</p>
     */
    private $_fuentesDisponibles = [
        'Nemon',
        'Nabalia',
    ];
    /*
     * @property array $_fuentesHabilitadas <p>Proveedores habilitados a los que pedir los datos</p>
     */
    private $_fuentesHabilitadas = [
        'Nemon',
        'Nabalia',
    ];
    /*
     * @property array $_prioridadFuentes <p>Orden de prioridad para pedir los datos. (referencia a $_fuentesHabilitadas)</p>
     */
    private $_prioridadFuentes = [
        0,
        1,
    ];
    private $_combinarFuentes = false; // \Bool Setear a true si queremos que se combinen los datos de todos los SIPS habilitados
    private $_suministro; // \Furbyus\sips\Model\Suministro || SuministroNabalia || SuministroNemon
    private $_rawData; // \object Objeto sin tratar, tal como lo ha decodificado json_decode()
    private $_responseData; // \String Texto de respuesta desde el Webservice del proveedor

    public function __construct($cups = '00000000000000000000', $config = [], $logChannel = null)
    {
        $this->cups = $cups;
        $this->logChannel = $logChannel;
        $this->log('Constructing SipsProvider');
        if (is_array($config) && $config != []) {
            foreach ($config as $sipsProvider => $sipsConfig) {
                $this->log($sipsProvider);
                $fuente = strtoupper(substr($sipsProvider, 0, 1)) . strtolower(substr($sipsProvider, 1));
                $this->addConfig([$sipsProvider => $sipsConfig]);
                if (!in_array($fuente, $this->_fuentesDisponibles)) {
                    return false;
                }
            }
            $this->log('Instantiation with config');
            $this->log($config);
            $this->sipsConfig = $config;
        }
    }

    public function habilitarFuente($fuente)
    {
        $fuente = strtoupper(substr($fuente, 0, 1)) . strtolower(substr($fuente, 1)); //Por si queremos pasar el nombre sin la primera en mayúsculas (nemon===Nemon===NEMON y al revés)
        if (in_array($fuente, $this->_fuentesDisponibles)) { //Queremos añadir una fuente de datos permitida
            if (!in_aray($fuente, $this->_fuentesHabilitadas)) {
                $this->_fuentesHabilitadas = array_merge($this->_fuentesHabilitadas, $fuente);
            }
            return true; //Ya existe, o ya se ha añadido la fuente
        }
        return false; //Hemos intentado añadir una fuente que no está disponible
    }

    public function deshabilitarFuente($fuente)
    {
        $fuente = strtoupper(substr($fuente, 0, 1)) . strtolower(substr($fuente, 1)); //Por si queremos pasar el nombre sin la primera en mayúsculas (nemon===Nemon===NEMON y al revés)

        if (in_array($fuente, $this->_fuentesHabilitadas)) {
            $key = 0;
            foreach ($this->_fuentesHabilitadas as $k => $v) {
                if ($fuente === $v) {
                    $key = $k;
                    break;
                }
            }
            $this->_fuentesHabilitadas = array_diff($this->_fuentesHabilitadas, [$fuente]);
            $this->_prioridadFuentes = array_diff($this->_prioridadFuentes, [$key]);
        }
    }

    public function setConfig()
    { //TODO
    }

    public function addConfig($a)
    {
        foreach ($a as $provider => $config) {
            $this->sipsConfig[$provider] = $config;
        }
    }

    public function usarFuente($fuente, $config = [])
    {
        $fuente = strtoupper(substr($fuente, 0, 1)) . strtolower(substr($fuente, 1)); //Por si queremos pasar el nombre sin la primera en mayúsculas (nemon===Nemon===NEMON y al revés)
        if (in_array($fuente, $this->_fuentesDisponibles)) {
            $this->_fuentesHabilitadas = [$fuente];
            $this->_prioridadFuentes = [0];
            if ($config != []) {
                $this->addConfig($config);
            } else {
                //return false;//Parametro $config requerido? tendria sentido, pero tambien tiene sentido poder setear-lo mas tarde...
            }
            return true;
        }
        return false;
    }

    public function requestData()
    {
        if (count($this->_fuentesHabilitadas) < 1) {
            return false;
        }
        foreach ($this->_prioridadFuentes as $fix) {
            if (!isset($this->_fuentesHabilitadas[$fix])) {
                return false;
            }
            $this->log('Requesting data from ' . $this->_fuentesHabilitadas[$fix]);
            $success = $this->{"_requestData" . $this->_fuentesHabilitadas[$fix]}();
            if ($this->_combinarFuentes) {
                //TODO hacer el continue y combinar resultados...
            } else {
                if (!$success) { //Si es FALSE, probamos la suguiente fuente
                    continue;
                }
                return true; //devolver true a la primera obtención de datos
            }
        }
        //Si no hemos encontrado un proveedor que pueda servir la información, retornamos false...
        return false;
    }

    public function getData($format = '')
    {
        $actual = isset($this->_suministro) ? $this->_suministro : false;
        if (!$actual) {

        }
        $this->log($actual->format('Default'));
        if ($format === '') {
            return $actual;
        }
        $formatted = isset($this->_suministro) ? $this->_suministro->format($format) : false;
        $this->log("Returning data as $format object format...");
        $this->log($formatted);
        return $formatted;
    }

    private function _requestDataNemon($type = 'elec')
    {
        if (!isset($this->sipsConfig['Nemon']['token'])) {
            return false;
        }
        $token = $this->sipsConfig['Nemon']['token'];
        $url = $this->sipsConfig['Nemon']['endpoint'];
        $data = new \stdClass();
        $data->token = $token;
        $data->request = 'detail';
        $data->cups = $this->cups;
        $data->typeenergy = $type;
        $postfields = ['token' => $token, 'module' => 'Sips', 'class' => 'SipsData', 'action' => 'getData', 'data' => json_encode($data)];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postfields,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $this->log($err);
            return false;
        } else {
            $this->_responseData = $response;
            $this->_rawData = json_decode($this->_responseData);
            if (isset($this->_rawData->error)) {
                return false;
            }
            if (count($this->_rawData->suministros) == 0 && $type == 'elec') { //Si no tenemos info de este CUPS en elec, se pide y retorna gas
                return $this->_requestDataNemon('gas');
            }
            //$this->log($this->_rawData);
            if (!isset($this->_rawData) || count($this->_rawData->suministros) <= 0) {
                return false;
            }
            $this->_suministro = new SuministroNemon($this->_rawData);
            return true;
        }
        return false;
    }

    private function _requestDataNabalia()
    {
        $cups_id = $this->cups;
        if (!isset($this->sipsConfig['Nabalia']) || !key_exists('procedencia', $this->sipsConfig['Nabalia'])) {
            return false;
        }
        $procedencia = $this->sipsConfig['Nabalia']['procedencia'];
        if ($cups_id == "00000000000000000000") {
            $cups_id = "ES0031405543280001EF"; //TODO hacer un error handling para esto, no puede ser que tengamos que setear un CUPS por defecto...
        }
        $post = [
            "procedencia" => $procedencia,
            "cups" => $cups_id, //ES0031405543280001EF"
        ];

        $send = $this->sipsConfig['Nabalia']['endpoint'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $send);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return false;
        }

        $this->_responseDataEncrypted = $response;
        $this->_responseData = $this->_decryptNabalia($response);
        $this->_rawData = (json_decode(utf8_encode($this->_responseData)));
        if (!isset($this->_rawData) || !isset($this->_rawData->datos) || count($this->_rawData->datos) <= 0) {
            return false;
        }
        //$this->log($this->_rawData);
        $this->_suministro = new SuministroNabalia($this->_rawData);
        return true;
    }

    private function _decryptNabalia($data)
    {
        return false;//in progress
        $secret = $this->sipsConfig['Nabalia']['secret'];
        $salt = $this->sipsConfig['Nabalia']['salt'];
        $keysize = 32;
        $ivsize = 16;
        $key = AES::pbkdf2('sha1', $secret, $salt, 1000, ($keysize + $ivsize), true);
        d($key);
        $raw = $data;
        $iv_size = openssl_cipher_iv_length('AES-128-CBC');
        $iv = substr($raw, 0, $iv_size);
        $data = substr($raw, $iv_size);
        d($data);
        $decrypted = openssl_decrypt(\base64_decode($data), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        d();

    }

    public function log($message)
    {

        if ($this->logChannel == 'screen') {
            d($message);
        }
    }

}
