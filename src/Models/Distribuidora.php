<?php

namespace Furbyus\Sips\Models;

/**
 * Distribuidora
 *
 * @author Francesc Aguilar Martinez
 */
class Distribuidora {
    use \Furbyus\Sips\Traits\ReadOnly;
    private $_codigo;
    private $_nombre;

    public function __construct($codigo = '0000', $nombre = '') {
        $this->_codigo = $codigo;
        $this->_nombre = $nombre;
    }

}
