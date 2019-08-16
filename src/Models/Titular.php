<?php

namespace Furbyus\Sips\Models;

/* Used classes, not needed to be included with composer autoload...
use Furbyus\Sips\Model\ActividadEmpresarial;
use Furbyus\Sips\Model\PairCodeName;
*/

/**
 * Titular
 * 
 * Toda la información que podemos tener del titular de un suministro.
 *
 * @author Francesc Aguilar Martinez
 */
class Titular {

    use \Furbyus\Sips\Traits\ReadOnly;

    private $_tipoPersona;          // \String fisica o juridica, gob... ej: "F","J"(solo Nemon) 
    private $_tipoTitular;          // ?(solo Nemon)
    private $_cif;                  // \String(9) DNI/NIF/NIE ej: "44444444K" (solo Nemon)
    private $_nombre;               // \String ej: "Francesc" (solo Nemon)
    private $_apellido;             // \String ej: "Francesc" (solo Nemon)
    private $_apellido2;            // \String ej: "Aguilar", "Aguilar Martinez" 
    private $_nombreCompleto;       // \String ej: "Francesc Aguilar Martinez" (solo Nemon)
    private $_direccion;            // \String ej: "Rda Sant pere 19" (solo Nemon)
    private $_extrasDireccion;      // \Furbyus\Sips\Model\Direccion ej: "Rda Sant pere 19" (solo Nemon)
    private $_municipio;            // \Furbyus\Sips\Model\PairCodeName ej: $_municipio->nombre="Badalona" (solo Nemon)
    private $_codigoPostal;         // \String ej: "08001" (solo Nemon)
    private $_provincia;            // \String ej: "Barcelona" (solo Nemon)
    private $_telefono;             // \String ej: "666666666" (solo Nemon)
    private $_actividadEconomica;   // \Furbyus\Sips\Model\ActividadEmpresarial  (solo Nemon)

    public function __construct($tipoPersona = 'F', $cif = '', $nombre = '', $apellido = '', $apellido2 = '', $nombreCompleto = '', ActividadEmpresarial $actividad = null) {
        $this->_tipoPersona = $tipoPersona;
        $this->_cif = $cif;
        $this->_nombre = $nombre;
        $this->_apellido = $apellido;
        $this->_apellido2 = $apellido2;
        $this->_nombreCompleto = $nombreCompleto;
        $this->_actividadEconomica = $actividad;
    }

    public function conDatosContacto($telefono = '', $direccion = '', $codigoPostal = '', PairCodeName $municipio = NULL, PairCodeName $provincia = NULL, Direccion $extrasDireccion = NULL) {
        $this->_telefono = $telefono;
        $this->_direccion = $direccion;
        $this->_municipio = $municipio;
        $this->_provincia = $provincia;
        $this->_codigoPostal = $codigoPostal;
        if (isset($extrasDireccion)) {
            $this->_extrasDireccion = $extrasDireccion;
        }
    }

}
