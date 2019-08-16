<?php

namespace Furbyus\Sips\Models;

/**
 * ActividadEmpresarial
 *
 * @author Francesc Aguilar Martinez
 */
class ActividadEmpresarial {

    private $_codigo;
    private $_descripcion;

    public function __construct($codigo = '', $descripcion = '') {
        $this->_codigo = $codigo;
        $this->_descripcion = $descripcion;
    }

}
