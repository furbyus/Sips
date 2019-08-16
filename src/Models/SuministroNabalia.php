<?php

namespace Furbyus\Sips\Models;

/**
 * Description of SuministroNabalia
 *
 * @author Francesc Aguilar Martinez
 */
class SuministroNabalia extends Suministro {

    function __construct($sipsObject, $timezone = 'europe/madrid') {
        parent::__construct($sipsObject, $timezone);

        $this->_fuente = 'Nabalia';

        $sano = $this->_sanitizeNabaliaObject($sipsObject);
        $this->_setLecturas($sano);
        $datos = $sano->datos;
        $potencias = $this->_mapVars((object) $datos);
        $this->_setCups($datos->{$this->_mapNabalia['cups']});
        $this->_provincia = new PairCodeName($this->getVar($datos, $this->_mapNabalia['provincia:nombre']));
        $this->_tarifa = new Tarifa($datos->{$this->_mapNabalia['tarifa:nombre']});
	//RAMON PUSO ESTO
	$this->_comercializadora = new Comercializadora($datos->{$this->_mapNabalia['comercializadora:codigo']},$datos->{$this->_mapNabalia['comercializadora:nombre']});
        //-----------------
		$this->_potenciaContador = new Potencias($potencias);
        $this->_titular = new Titular(null, null, null, null, null, $datos->{$this->_mapNabalia['titular:nombreCompleto']});
        $this->_normalizeVars();
    }

    private function _setLecturas($sipsObject) {
        $lecturasTipos = [
            'Act' => $sipsObject->LecturasActiva,
            'Reac' => $sipsObject->LecturasReactiva,
            'Pot' => $sipsObject->LecturasMaximetro,
        ];
        $l = 0;
        foreach ($lecturasTipos as $tipoEnergia => $lecturas) {
            foreach ($lecturas as $lectura) {
                $this->_setLectura($lectura, $tipoEnergia);
                $this->{"_lecturas$tipoEnergia"}[] = $l;
                $l++;
            }
        }
    }

    private function _sanitizeNabaliaObject($sipsObject) {
        $obj = new \stdClass();
        $obj->datos = json_decode($sipsObject->datos)[0];
        $obj->LecturasActiva = json_decode($sipsObject->LecturasActiva);
        $obj->LecturasReactiva = json_decode($sipsObject->LecturasReactiva);
        $obj->TotalesActiva = json_decode($sipsObject->TotalesActiva);
        $obj->TotalesReactiva = json_decode($sipsObject->TotalesReactiva);
        $obj->LecturasMaximetro = json_decode($sipsObject->LecturasMaximetro);

        foreach ($obj as $k => $v) { //object in $obj (datos,LecturasActiva...)
            if ($k == 'datos') {
                $obj->datos->fum = $this->_parseNabaliaDate($v->fum, 'c');
                $obj->datos->fucc = $this->_parseNabaliaDate($v->fucc, 'c');
                $obj->datos->ful = $this->_parseNabaliaDate($v->ful, 'c');
                $obj->datos->fal_sum = $this->_parseNabaliaDate($v->fal_sum, 'c');
                continue;
            }
            if ($k === 'LecturasActiva' || $k === 'LecturasReactiva' || $k === 'LecturasMaximetro') {
                foreach ($v as $i => $lectura) {//lectura en lecturas (0,1,2,3,4...)
                    $obj->{$k}[$i] = $this->_sanitizeNabaliaLectura($lectura);
                }
                continue;
            }
            if ($k === 'TotalesActiva' || $k === 'TotalesReactiva' || $k === 'TotalesMaximetro') {
                foreach ($v as $i => $total) {//total en totales(0,1,2,3,4...)
                    $obj->{$k}[$i] = $this->_sanitizeNabaliaTotal($total);
                }
                continue;
            }
        }
        return $obj;
    }

    private function _sanitizeNabaliaTotal($total) {
        $total->tot = (int) $total->tot;
        return $total;
    }

    private function _sanitizeNabaliaLectura($lectura) {
        foreach ($lectura as $paramName => $paramValue) {//param en lectura
            if ($paramName != 'fec' && $paramName != 'tip') {
                $lectura->{$paramName} = (int) $paramValue;
            }
            if ($paramName == 'fec') {
                $date = $this->_parseNabaliaDate($paramValue);
                $lectura->fec = $date->format('c');
            }
        }
        return $lectura;
    }

    private function _parseNabaliaDate($original, $format = '') {
        $datestr = substr($original, 1, strlen($original) - 2);
        $date = \DateTime::createFromFormat("d#m#Y H:i:s", $datestr, $this->timeZone);
        if (!$date) {
            return null;
        }
        return ($format !== '') ? $date->format($format) : $date;
    }

    private function _setLectura($lectura, $tipoEnergia) {
        $map = $this->{"_map$this->_fuente" . "Lectura"};
        $plantilla = $map['consumos'];
        $cs = [];
        for ($i = 1; $i <= 6; $i++) {
            $cs[] = $lectura->{str_replace("%n%", $i, $plantilla)};
        }
        $this->_lecturas[] = new Lectura($lectura->{$map['tipoLectura']}, $tipoEnergia, $lectura->{$map['fecha']}, new Consumos($cs));
        return true;
    }

}
