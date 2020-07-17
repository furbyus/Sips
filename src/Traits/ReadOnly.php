<?php
namespace Furbyus\Sips\Traits;

trait ReadOnly
{

    public function __get($name)
    { //Metodo para cojer las variables mediante $suministro->variable
        if (isset($this->{"_$name"})) {
            $requested = $this->getReflectedProperty("_$name", $this);
        } else {
            if (isset($this->{"$name"})) {
                $requested = $this->getReflectedProperty("$name", $this);
            } else {
                return false;
            }

        }
        return $requested;
    }

    public function __set($k, $v)
    {
        return false; //TODO si hay que poder asignar un valor a las propiedades protected, se debe controlar aqui.
    }

    private function getReflectedProperty(String $propertyName, $targetObject)
    {
        $reflected = new \ReflectionObject($targetObject);
        $property = $reflected->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($targetObject);
    }

    private function getObjectReflected()
    {
        $reflected = new \stdClass();

        foreach ($this as $key => $value) {
            $reflected->{$key} = $this->getReflectedProperty($key, $this);
        }
        return $reflected;
    }
}
