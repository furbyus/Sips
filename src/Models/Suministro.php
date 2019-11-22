<?php

namespace Furbyus\Sips\Models;

use Furbyus\Sips\Lib\AES;
use Furbyus\Sips\Interfaces\iSuministro;
/* Used classes, not needed to be included with composer autoload...
use Furbyus\Sips\Model\Tarifa;
use Furbyus\Sips\Model\Potencias;
use Furbyus\Sips\Model\Titular;
use Furbyus\Sips\Model\Direccion;
use Furbyus\Sips\Model\PairCodeName;
*/


/**
 * Objeto para guardar la información devuelta por algún SIPS 
 * como Nabalia, Nemon, CIAS...
 * 
 * Todas las propiedades del objeto, dada su naturaleza, son de sólo lectura
 * Excepto propiedades de configuración como timeZone
 * 
 * @author Francesc Aguilar Martínez
 */
class Suministro implements iSuministro {

    use \Furbyus\Sips\Traits\ReadOnly;
    use \Furbyus\Sips\Traits\SipsMaps;
    /*
     * General
     */
    /*
     * @property DateTimeZone $timeZone <p>la zona horaria para crear las fechas en formato \DateTime de php </p>
     */
    public $timeZone;                   // \DateTimeZone la zona horaria para crear las fechas en formato \DateTime de php

    protected $_fuente;                 // \String ej: Nemon, Nabalia
    protected $_energyType;             // \String ej: G, E
    protected $_cups;                   // \String(20-22) ej:"ES0031406171161013JV0F"
    protected $_cups20;                 // \String(20) ej:"ES0031406171161013JV"
    protected $_cups22;                 // \String(22) ej:"ES0031406171161013JV0F"
    protected $_titular;                // \Furbyus\Sips\Model\Titular
    protected $_distribuidora;          // \Furbyus\Sips\Model\Distribuidora
    protected $_comercializadora;       // \Furbyus\Sips\Model\Comercializadora
    protected $_tarifa;                 // \Furbyus\Sips\Model\Tarifa
    protected $_alta;                   // \DateTime alta del suministro
    protected $_ultimoMovimiento;       // \DateTime 
    protected $_ultimoCambioComercializadora; // \DateTime 
    protected $_ultimaLectura;          // \DateTime
    protected $_lecturas;               // \array(de \Furbyus\Sips\Model\Lectura) n lecturas
    protected $_totales;                // \array(de \Furbyus\Sips\Model\Totales) n totales
    protected $_lecturasAct;            // \array indices de $this->_lecturas
    protected $_lecturasReac;           // \array indices de $this->_lecturas
    protected $_lecturasPot;            // \array indices de $this->_lecturas
    protected $_totalesAct;             // \array indices de $this->_totales
    protected $_totalesReac;            // \array indices de $this->_totales
    protected $_totalesPot;             // \array indices de $this->_totales
    protected $_actualizado;            // \DateTime Fecha de actualización de los datos (excepto lecturas)

    /*
     * Relativas al uso
     */
    protected $_consumoAnual;           // \Furbyus\Sips\Model\Consumos Total, P1-P6 año en curso
    protected $_consumoAnualAnterior;   // \Furbyus\Sips\Model\Consumos Total, P1-P6 año anterior
    protected $_ratioConsumo;           // \Furbyus\Sips\Model\Consumos P1-P6 (se modifica la unidad, por pu (per unit))

    /*
     * Relativas a la ubicación
     */
    protected $_direccion;              // String
    protected $_extrasDireccion;        // \Furbyus\Sips\Model\Direccion 
    protected $_localidad;              // \Furbyus\Sips\Model\PairCodeName
    protected $_codigoPostal;           // String
    protected $_provincia;              // \Furbyus\Sips\Model\PairCodeName
    protected $_lat;                    // String
    protected $_lng;                    // String

    /*
     * Relativas a la instalación física
     */
    protected $_propietarioEquipoMedida; // \String ej: "EMPRESA DISTRIBUIDORA", "TITULAR"
    protected $_propietarioIcp;         // \String ej:"EMPRESA DISTRIBUIDORA", "TITULAR"
    protected $_telegestion;            // \Furbyus\Sips\Model\EstadoTelegestion
    protected $_tension;                // \String ej: "1X230"
    protected $_potenciaMaximaBiestable; // \Float ej:3.4
    protected $_potenciaMaximaInstalada; // \Float ej:3.4
    protected $_tipoPuntoMedida;        // \Integer 1,2,3,4 o 5 según Art.7 Real Decreto 1110/2007-> https://www.boe.es/buscar/act.php?id=BOE-A-2007-16478#a7
    protected $_potenciaContador;       // \Furbyus\Sips\Model\Potencias P1-P6
    protected $_fasesEquipoMedida;      // \String ej: "M", "T" (solo Nemon)
    protected $_indicativoIcp;          // \String ej: "ICP INSTALADO" (solo Nemon)

    /*
     * Relativas a la situación personal del titular
     * o bien a la relación entre comercializadora y titular.
     * ej: se ha cortado la luz previamente, autoconsumo, etc
     */
    protected $_cortes;                 // \Integer ej: 0,1,2,3 (numero de veces que se ha cortado)
    protected $_impago;                 // \Integer ej: 0,1
    protected $_importeImpagos;         // \Float (solo Nabalia)
    protected $_fianza;                 // \Float (solo Nemon) 
    protected $_primeraVivienda;        // \Boolean (solo Nemon)
    protected $_personaContacto;        // \String (solo Nemon)
    protected $_cargoPersonaContacto;   // \String (solo Nemon)
    protected $_perfilConsumo;          // \String ej: "PA" (solo Nemon)
    protected $_autoconsumo;            // \String ej: "Sin autoconsumo"  (solo Nemon)
    protected $_bonoSocial;             // \String ej: "0","1"
    protected $_gas;                     // Información extra que lleva el objeto suministro de GAS.

    /*
     * Otras
     */
    protected $_derechoExtension;       //solo Nemon
    protected $_derechoAccesoLlano;     //solo Nemon
    protected $_derechoAccesoValle;     //solo Nemon
    protected $_trimestreCambioContador; //solo Nemon
    protected $_lecturasNemon;          //solo Nemon
    protected $_fechaLimite;            // \DateTime solo Nemon
    protected $_precioOptim;            //solo Nemon
    protected $_precioReactiva;         //solo Nemon
    protected $_precioReactivaOptim;    //solo Nemon
    protected $_energyGwh;              //solo Nemon 
    protected $_sumsTotal;              //solo Nemon

    public function __construct($sipsObject, $timezone = 'europe/madrid') {
        $this->timeZone = new \DateTimeZone($timezone);
        $this->_energyType = 'E'; //Default value
    }

    protected function _setCups($cup) {
        if (strlen($cup) < 19) {
            return false;
        }
        $of = '0F';
        $this->_cups = $cup;
        if (strlen($this->_cups) > 20) {
            $this->_cups22 = $cup;
            $this->_cups20 = substr($cup, 0, 19);
        } else {
            $this->_cups20 = $cup;
            $this->_cups22 = "$cup$of";
        }
    }

    public function format($format = 'Default') {
        $format = strtoupper(substr($format, 0, 1)) . strtolower(substr($format, 1)); //Por si queremos pasar el nombre sin la primera en mayúsculas (nemon===Nemon===NEMON)
        $toret = new \stdClass();
        $map = $this->{"_map$format"};
        $datos = new \stdClass();
        if (isset($map) && is_array($map)) {
            foreach ($map as $from => $to) {
                if (strpos($from, ':') > -1) {
                    continue;
                }
                $datos->{$to} = $this->{"_$from"};
            }
        }

        switch ($format) {
            case 'Nabalia':
                $toret->Error = false; //TODO Controlar esto, quizas guardando en $this->_sipsError por ejemplo...
                $toret->datos = $datos;
                if (isset($this->_potenciaContador)) {
                    for ($i = 1; $i < 7; $i++) {
                        $toret->datos->{"pc$i"} = $this->_potenciaContador->{"p$i"};
                    }
                }
                $toret->datos->ape_tit = $this->_titular->nombreCompleto;
                $toret->datos->pro_sum = $this->_provincia->nombre;
                //RAMON PUSO ESTO
                $toret->datos->codree = $this->_comercializadora->codigo;
                $toret->datos->com_nom = $this->_comercializadora->nombre;
                //-----------------
                if ($this->_energyType == "E") {
                    $toret->datos->tar = $this->_tarifa->nombre;
                    $act = $this->_getLecturasNabalia('Act');
                    $reac = $this->_getLecturasNabalia('Reac');
                    $pot = $this->_getLecturasNabalia('Pot');

                    $toret->LecturasActiva = $act->lecturas;
                    $toret->LecturasReactiva = $reac->lecturas;
                    $toret->LecturasMaximetro = $pot->lecturas;
                    $toret->TotalesActiva = $act->totales;
                    $toret->TotalesReactiva = $reac->totales;
                    $toret->TotalesMaximetro = $pot->totales;
                } else {
                    //TODO GAS
                }

                break;
            case 'Nemon':
                $toret->suministro = $datos->getReflectedObject();

                break;
            default:
                $toret = $this;
                break;
        }

        return $toret;
    }

    protected function _getLecturasNabalia($te) {
        $lecs = [];
        $tots = [];
        for ($n = 1; $n < 7; $n++) {
            $p = new \stdClass();
            $p->per = "P$n";
            $p->tot = 0;
            $tots[] = $p;
        }
        if (isset($this->{"_lecturas$te"}) && !empty($this->{"_lecturas$te"}) && count($this->{"_lecturas$te"}) > 0) {
            foreach ($this->{"_lecturas$te"} as $i) {
                $lectura = new \stdClass();
                $lectura->fec = $this->_lecturas[$i]->fecha;    //TODO re-formatear para coincidir formatos de fecha con nabalia, o ejor así que es fácil montar un Datetime a partir de este String???
                $lectura->tip = ($this->_lecturas[$i]->tipoLectura == 'R') ? 'REAL' : 'ESTIMADA';
                for ($n = 1; $n < 7; $n++) {
                    $cs = $this->_lecturas[$i]->consumos;
                    $lectura->{"P$n"} = $cs->{"p$n"};
                    $tots[$n - 1]->tot += $cs->{"p$n"};
                }
                $lecs[] = $lectura;
            }
        }

        $toret = new \stdClass();
        if ($this->_fuente === 'Nemon') {
            $toret->lecturas = array_reverse($lecs);
        } else {
            $toret->lecturas = $lecs;
        }
        $toret->totales = $tots;
        return $toret;
    }

    protected function _mapVars($data) {
        foreach ($this->{"_map$this->_fuente"} as $to => $from) { //Coje el array _mapNabalia, _mapNemon etc... y lo itera, cogiendo los datos en el objeto $data para mapear-los a la nomenclatura unificada
            if (strpos($to, ':') > -1) {
                continue;
            }
            if (is_array($from)) {
                foreach ($from as $from_or) {
                    if (isset($data->{$from_or}) && !empty($data->{$from_or})) {
                        $this->{"_$to"} = $data->{$from_or};
                    }
                }
                continue;
            }
            if (isset($data->{$from}) && !empty($data->{$from})) {
                $this->{"_$to"} = $data->{$from};
            }
        }
        return ($this->_energyType == 'E') ? $this->_getPotencias($data) : true;
    }

    protected function getVar($data, $var) {
        if (!is_array($var)) {
            return isset($data->{$var}) ? $data->{$var} : NULL;
        }
        foreach ($var as $val) {
            if (isset($data->{$val})) {
                return $data->{$val};
            }
        }
        return NULL;
    }

    protected function _getPotencias($data) {
        $potencias = [];
        for ($i = 0; $i < 6; $i++) {
            $potencias[$i] = $data->{str_replace('%n%', $i + 1, $this->{"_map$this->_fuente"}['replace:potencias'])};
        }
        return $potencias;
    }

    protected function _normalizeVars() {
        $this->_tipoPuntoMedida = $this->_tipoPM($this->_tipoPuntoMedida);

        foreach ($this->_lecturas as $lectura) {
            $lectura->setTipoLectura($this->_tipoLectura($lectura->tipoLectura));
        }
    }

    protected static function _tipoPM($str) {
        $roman_to_arab = [
            'I' => '1',
            'II' => '2',
            'III' => '3',
            'IV' => '4',
            'V' => '5'
        ];
        $tmpl = 'Punto de medida tipo ';
        $ix = strpos($str, $tmpl);
        if ($ix > -1) {
            $queda = substr($str, $ix + strlen($tmpl));
            $queda = trim($queda);
            if (in_array($queda, $roman_to_arab)) {
                foreach ($roman_to_arab as $roman => $arab) {
                    $queda = str_replace($roman, $arab, $queda);
                }
            }
            $str = $queda;
        }
        return (int) trim(str_replace('0', '', $str));
    }

    protected static function _tipoLectura($str) {
        return ($str === 'R' || $str === 'REAL' || $str === 'Real' || $str === 'r' || $str === 'real') ? 'R' : 'E';
    }

    protected static function _tipoPersona($str) {
        return ($str === 'F' || $str === 'f' || $str === 'Fisica') ? 'F' : 'J';
    }

}


