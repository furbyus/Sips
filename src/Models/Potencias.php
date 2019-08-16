<?php

namespace Furbyus\Sips\Models;

/**
 * Potencias
 *
 * @author Francesc Aguilar Martinez
 */
class Potencias {

    use \Furbyus\Sips\Traits\ReadOnly;

    protected $_unidad = 'KW';
    protected $_total = 0;
    protected $_p1 = 0;
    protected $_p2 = 0;
    protected $_p3 = 0;
    protected $_p4 = 0;
    protected $_p5 = 0;
    protected $_p6 = 0;

    public function __construct(array $periodos, $total = 0, $unidad = '') {
        $setPs = $this->_setPeriodos($periodos);

        if ($total === 0) {
            $this->_total = $this->suma_total();
        } else {
            $this->_total = $total;
        }
        if ($unidad !== '') {
            $this->_unidad = $unidad;
        }
        return $setPs ? $this : false;
    }

    private function _setPeriodos($periodos) {
        if (count($periodos) <= 0) {
            return false;
        }
        $tipo = 'int';
        $c = -1;
        foreach ($periodos as $ix => $periodo) {
            $tipoActual = $this->ixType($ix);
            $c++;
            if ($c === 0) {
                $tipo = $tipoActual;
                $this->_setPeriodo($ix, $tipo, $periodo);
                continue;
            }
            if ($tipo !== $tipoActual) {
                return false;
            }
            $this->_setPeriodo($ix, $tipo, $periodo);
        }
        return true;
    }

    private function _setPeriodo($k, $t, $v) {
        if ($t === 'int' && $k < 6 && $k >= 0) {
            $this->{"_p" . ($k + 1)} = $v;
        } else {
            if ($k == 'p1' || $k == 'p2' || $k == 'p3' || $k == 'p4' || $k == 'p5' || $k == 'p6') {
                $this->{"_" . $k} = $v;
            }
        }
    }

    private function ixType($ix) {
        if (is_string($ix) && 'p' === substr($ix, 0, 1)) {
            return 'str';
        }
        if (is_int($ix)) {
            return 'int';
        }
        return false;
    }

    private function suma_total() {
        $t = $this;
        return $t->_p1 + $t->_p2 + $t->_p3 + $t->_p4 + $t->_p5 + $t->_p6;
    }

} 
