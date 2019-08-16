<?php

namespace Furbyus\Sips\Models;

//use Furbyus\Sips\Model\Consumos;

/**
 * Lectura
 *
 * @author Francesc Aguilar Martinez
 */
class Lectura {
use \Furbyus\Sips\Traits\ReadOnly;
    protected $_tipoLectura;      // \String Tipo de lectura, real o estimada ej: "R","E"
    protected $_fecha;            // \DateTime fecha de lectura
    protected $_fechaInicio;      // \Datetime SOLO GAS!! fecha de inicio (en Nemon "Fec_Ini_Consumo")
    protected $_consumos;         // \Furbyus\Sips\Model\Consumos P1-P6 
    protected $_tipoEnergia;      // \String Representa el tipo de energia medido ej: "Activa","Reactiva","Potencia"

    public function __construct($tipoLectura = 'E', $tipoEnergia = 'Activa', $fecha = '', Consumos $consumos = null) {
        $this->_tipoLectura = $tipoLectura;
        $this->_tipoEnergia = $tipoEnergia;
        if ($fecha !== '') {
            $this->_fecha = $fecha;
        }
        if ($consumos === null) {
            $this->_consumos = new Consumos([]);
        } else {
            $this->_consumos = $consumos;
        }
    }
    public function setTipoLectura($tipo){
        $this->_tipoLectura = $tipo;
    }

}
