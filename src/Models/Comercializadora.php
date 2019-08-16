<?php

namespace Furbyus\Sips\Models;

/**
 * Comercializadora
 *
 * @author Francesc Aguilar Martinez
 */
class Comercializadora {
    use \Furbyus\Sips\Traits\ReadOnly;
    private $_codigo;
    private $_nombre;

    public function __construct($codigo = null, $nombre = null) {
        $this->_codigo = $codigo;
        $this->_nombre = $nombre;
    }

}
