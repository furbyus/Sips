<?php
namespace Furbyus\Sips\Traits;

Trait ReadOnly {

public function __get($name) {//Metodo para cojer las variables mediante $suministro->variable
    if (isset($this->{"_$name"})) {
        return $this->{"_$name"};
    } else {
        return false;
    }
}

public function __set($k, $v) {
    return false; //TODO si hay que poder asignar un valor a las propiedades protected, se debe controlar aqui.
}

}