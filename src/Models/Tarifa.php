<?php

namespace Furbyus\Sips\Models;

/**
 * Tarifa
 *
 * @author Francesc Aguilar Martinez
 */
class Tarifa extends PairCodeName {

    private $_descripcion; // \String ej: "B.T. GENERAL HASTA 10 KW, PEAJ"

    function __construct($name = '', $code = '', $description = '') {
        parent::__construct($name, $code);
        if ($description !== '') {
            $this->_descripcion = $description;
        }
    }

}
