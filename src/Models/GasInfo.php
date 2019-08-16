<?php

namespace Furbyus\Sips\Models;

/**
 * Description of GasInfo
 *
 * @author Francesc Aguilar Martinez
 */
class GasInfo {

    use \Furbyus\Sips\Traits\ReadOnly;

    private $_presion;
    private $_maxCaudalDia;
    private $_maxCaudalHora;
    private $_derechoTur;
    private $_ultimaInspeccion;
    private $_resultadoInspeccion;
    private $_numeroContador;
    private $_calibreContador;
    private $_tipoCorrector;
    private $_codigoAccesoContador;
    private $_plantaSatelite;
    private $_degradacionFotoCatalitica;
    private $_pctd;
    private $_presionMedia;
    private $_kWhAnual;
    private $_kWhAnualP1;
    private $_kWhAnualP2;

    public function __construct($presion, $maxCaudalDia, $maxCaudalHora, $derechoTur, $ultimaInspeccion, $resultadoInspeccion, $numeroContador, $calibreContador, $tipoCorrector, $codigoAccesoContador, $plantaSatelite, $degradacionFotoCatalitica, $presionMedia, $kWhAnual, $kWhAnualP1, $kWhAnualP2) {
        $this->_presion = $presion;
        $this->_maxCaudalDia = $maxCaudalDia;
        $this->_maxCaudalHora = $maxCaudalHora;
        $this->_derechoTur = $derechoTur;
        $this->_ultimaInspeccion = $ultimaInspeccion;
        $this->_resultadoInspeccion = $resultadoInspeccion;
        $this->_numeroContador = $numeroContador;
        $this->_calibreContador = $calibreContador;
        $this->_tipoCorrector = $tipoCorrector;
        $this->_codigoAccesoContador = $codigoAccesoContador;
        $this->_plantaSatelite = $plantaSatelite;
        $this->_degradacionFotoCatalitica = $degradacionFotoCatalitica;
        $this->_pctd = $degradacionFotoCatalitica;
        $this->_presionMedia = $presionMedia;
        $this->_kWhAnual = $kWhAnual;
        $this->_kWhAnualP1 = $kWhAnualP1;
        $this->_kWhAnualP2 = $kWhAnualP2;
        
    }

}
