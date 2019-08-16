<?php

namespace Furbyus\Sips\Models;

/**
 * PairCodeName
 *
 * @author Francesc Aguilar Martinez
 */
class PairCodeName {

    use \Furbyus\Sips\Traits\ReadOnly;

    protected $_nombre; // \String ej: "2.0A" o "Nabalia Energia"
    protected $_codigo; // \String ej: "1016"

    function __construct($name = '', $code = '') {
        if ($name !== '') {
            $this->_nombre = $name;
        }
        if ($code !== '') {
            $this->_codigo = $code;
        }
    }

}
