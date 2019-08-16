<?php

namespace Furbyus\Sips\Models;

/**
 * Description of Direccion
 *
 * @author Francesc Aguilar Martinez
 */
class Direccion {

    use \Furbyus\Sips\Traits\ReadOnly;

    private $_tipoVia;
    private $_nombreVia;
    private $_numeroVia;
    private $_portal;
    private $_escalera;
    private $_piso;
    private $_puerta;

    public function __construct($tipo, $nombre, $numero, $portal = '', $escalera = '', $piso = '', $puerta = '') {
        $this->_tipoVia = $tipo;
        $this->_nombreVia = $nombre;
        $this->_numeroVia = $numero;
        $this->_portal = $portal;
        $this->_escalera = $escalera;
        $this->_piso = $piso;
        $this->_puerta = $puerta;
    }

}
