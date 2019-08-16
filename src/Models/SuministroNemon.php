<?php

namespace Furbyus\Sips\Models;

/**
 * SuministroNemon
 *
 * @author Francesc Aguilar Martinez
 */
class SuministroNemon extends Suministro {

    function __construct($sipsObject, $timezone = 'europe/madrid') {
        parent::__construct($sipsObject, $timezone);
        $this->_fuente = 'Nemon';
        $dataArray = $sipsObject->suministros[0]; //TODO cojer el primero, o cojer varios????
        $data = (object) $dataArray;
        $this->_setCups($data->{$this->_mapNemon['cups']});
        if (isset($data->Calibre_Contador)) {
            $this->_loadGas($data);
        } else {
            $this->_loadElec($data);
            $this->_setLecturas($sipsObject->lecturas);
            $this->_normalizeVars();
        }
    }

    private function _loadElec($data) {
        $map = $this->_mapNemon;
        $potencias = $this->_mapVars($data);
        $this->_potenciaContador = new Potencias($potencias);
        $this->_distribuidora = new Distribuidora($this->getVar($data, $map['distribuidora:codigo']), $this->getVar($data, $map['distribuidora:nombre']));
        $this->_tarifa = new Tarifa($this->getVar($data, $map['tarifa:nombre']), $this->getVar($data, $map['tarifa:descripcion']));
        $this->_comercializadora = new Comercializadora($this->getVar($data, $map['comercializadora:codigo']), $this->getVar($data, $map['comercializadora:nombre']));
        $cnae = new ActividadEmpresarial($this->getVar($data, $map['cnae:codigo']), $this->getVar($data, $map['cnae:descripcion']));
        $this->_provincia = new PairCodeName($this->getVar($data,$map['provincia:nombre']));
        $this->_cups20 = $data->CUPS20 ?: $this->_cups20;
        $this->_cups22 = $data->CUPS22 ?: $this->_cups22;

        $this->_municipio = new PairCodeName($data->Localidad_Suministro);
        $this->_titular = new Titular($data->Persona, $data->CIF_Titular, $data->Nombre_Titular, $this->Apellido_Titular, '', $data->NombreCompleto_Titular, $cnae);
        $this->_titular->conDatosContacto($data->Telefono_Titular, $data->Direccion_Titular, $data->Cod_Postal_Titular, new PairCodeName($data->Municipio_Titular), new PairCodeName($data->Provincia_Titular));
        $this->_telegestion = new EstadoTelegestion($data->Cod_Telegestionado_Activo, $data->Telegestionado_Activo);
        $this->_consumoAnual = new Consumos([$data->kWhAnual_p1, $data->kWhAnual_p2, $data->kWhAnual_p3, $data->kWhAnual_p4, $data->kWhAnual_p5, $data->kWhAnual_p6], $data->kWhAnual);
        $this->_ratioConsumo = new Consumos([$data->kWhAnual_pctge_p1, $data->kWhAnual_pctge_p2, $data->kWhAnual_pctge_p3, $data->kWhAnual_pctge_p4, $data->kWhAnual_pctge_p5, $data->kWhAnual_pctge_p6], null, 'pu');
        $this->_consumoAnualAnterior = new Consumos([$data->LastTotalYearkWh_p1, $data->LastTotalYearkWh_p2, $data->LastTotalYearkWh_p3, $data->LastTotalYearkWh_p4, $data->LastTotalYearkWh_p5, $data->LastTotalYearkWh_p6]);
    }

    private function _loadGas($data) {
        $map = $this->_mapNemon;
        //d("Loading GAS from Nemon ...");
        $this->_energyType = 'G';
        $this->_mapVars($data);
        $this->_distribuidora = new Distribuidora($this->getVar($data,$map['distribuidora:codigo']), $this->getVar($data, $map['distribuidora:nombre']));
        $this->_municipio = new PairCodeName($data->Localidad_Suministro, $data->Cod_Localidad_Suministro);

        $this->_extrasDireccion = new Direccion($data->Tipo_Via_Suministro
                , $data->Via_Suministro
                , $data->Num_Finca_Suministro
                , $data->Portal_Suministro
                , $data->Escalera_Suministro
                , $data->Piso_Suministro, $data->Puerta_Suministro);
        $cnae = new ActividadEmpresarial($data->CNAE);
        $dir_tit = new Direccion($data->Tipo_Via_Titular, $data->Via_Titular, $data->Num_Finca_Titular, $data->Portal_Titular, $data->Escalera_Titular, $data->Piso_Titular, $data->Puerta_Titular);
        $tit = new Titular(null, null, $data->Nombre_Titular, $data->Aprellido_1_Titular, $data->Aprellido_2_Titular, $data->Nombre_Completo_Titular, $cnae);
        $tit->conDatosContacto('', $data->Direccion_Completa_Titular, $data->Cod_Postal_Titular, new PairCodeName($data->Localidad_Titular, $data->Cod_Localidad_Titular), new PairCodeName($data->Provincia_Titular, $data->Cod_Provincia_Titular), $dir_tit);
        $this->_titular = $tit;
        $this->_gas = new GasInfo($data->Cod_Presion, $data->Caudal_Max_Diario_Wh, $data->Caudal_Max_Horario_Wh, $data->DerechoTUR, $data->Fec_Ultima_Inspeccion, $data->Cod_Resultado_Inspeccion, $data->Cod_Contador, $data->Calibre_Contador, $data->Tipo_Corrector, $data->Cod_Acces_Contador, $data->Con_Planta_Sat, $data->PCTD, $data->Presion_Media, $data->kWhAnual, $data->kWhAnual_p1, $data->kWhAnual_p2);
    }

    private function _setLecturas($lecturas) {
        $this->_lecturas = [];
        $l = 0;
        $tiposEnergia = ['Act', 'Reac', 'Pot'];
        foreach ($lecturas as $lectura) {
            foreach ($tiposEnergia as $tipo) {
                $this->_setLectura($lectura, $tipo);
                $this->{"_lecturas$tipo"}[] = $l;
                $l++;
            }

            /*
             * En un principio, pensava que los campos TActP1M1, TReacP1M1 y TPotP1M1 eran los consumos (lecturas) acumulados durante el año actual, pero o bien son los totales del año anterior o no se entiende, porque se repiten en cada lectura
             * Además de repetir-se, hay un patrón que se repite, como minimo en el cups ES0031405543280001EF0F, ya que siempre son (P1 a P6)->(61,62,63,64,65,66) tanto Tact Como TPot (TReac estan a cero...)
             * 
              $this->_setTotal($lectura, 'Act');
              $this->_totalesAct[] = $l;
              $l++;
              $this->_setTotal($lectura, 'Reac');
              $this->_totalesReac[] = $l;
              $l++;
              $this->_setTotal($lectura, 'Pot');
              $this->_totalesPot[] = $l;
              $l++;
             * 
             */
        }
    }

    private function _setLectura($lectura, $tipoEnergia) {
        $map = $this->{"_map$this->_fuente" . "Lectura"};
        $plantilla = $map['consumos'];
        $cs = [];
        for ($i = 1; $i <= 6; $i++) {
            $cs[] = $lectura->{str_replace("%n%", $i, str_replace("%te%", $tipoEnergia, $plantilla))};
        }
        $this->_lecturas[] = new Lectura($lectura->{$map['tipoLectura']}, $tipoEnergia, $lectura->{$map['fecha']}, new Consumos($cs));
        return true;
    }

    private function _setTotal($lectura, $tipoEnergia) {
        $map = $this->{"_map$this->_fuente" . "Lectura"};
        $plantilla = $map['consumosT'];
        $cs = [];
        for ($i = 1; $i <= 6; $i++) {
            $cs[] = $lectura->{str_replace("%n%", $i, str_replace("%te%", $tipoEnergia, $plantilla))};
        }
        $this->_lecturas[] = new Totales($lectura->{$map['tipoLectura']}, $tipoEnergia, $lectura->{$map['fecha']}, new Consumos($cs));
        return true;
    }

}
