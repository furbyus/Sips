<?php
namespace Furbyus\Sips\Models;

/**
 * EstadoTelegestion
 *
 * @author Francesc Aguilar Martinez
 */
class EstadoTelegestion {

    private $_codigo;  // \String ej: "1"
    private $_estado;        // \String ej: "Telegestión operativa con Curva de Carga Horaria"
    public function __construct($codigo='',$estado='') {
        $this->_codigo = $codigo;
        $this->_estado = $estado;
    }

}
