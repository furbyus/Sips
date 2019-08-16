<?php

namespace Furbyus\Sips\Interfaces;

/**
 * Objeto para guardar la información devuelta por algún SIPS 
 * como Nabalia, Nemon, CIAS...
 * 
 * Todas las propiedades del objeto, dada su naturaleza, son de sólo lectura
 * Excepto propiedades de configuración como timeZone
 * 
 * @author Francesc Aguilar Martínez
 */
interface iSuministro {

    /**
     * General
     * 
     * @property DateTimeZone $timeZone (ReadWrite) la zona horaria para crear las fechas en formato \DateTime de php
     * @property string $fuente
     * @property String $fuente  ej: Nemon, Nabalia
     * @property String $energyType  ej: G, E
     * @property String $cups (20-22) ej:"ES0031406171161013JV0F"
     * @property String $cups20 (20) ej:"ES0031406171161013JV"
     * @property String $cups22 (22) ej:"ES0031406171161013JV0F"
     * @property Furbyus\sips\Model\Titular $titular 
     * @property Furbyus\sips\Model\Distribuidora $distribuidora 
     * @property Furbyus\sips\Model\Comercializadora $comercializadora 
     * @property Furbyus\sips\Model\Tarifa $tarifa 
     * @property DateTime $alta  alta del suministro
     * @property DateTime $ultimoMovimiento  
     * @property DateTime $ultimoCambioComercializadora  
     * @property DateTime $ultimaLectura 
     * @property array $lecturas (de \Furbyus\sips\Model\Lectura) n lecturas
     * @property array $totales (de \Furbyus\sips\Model\Totales) n totales
     * @property array $lecturasAct  indices de $this->_lecturas
     * @property array $lecturasReac  indices de $this->_lecturas
     * @property array $lecturasPot  indices de $this->_lecturas
     * @property array $totalesAct  indices de $this->_totales
     * @property array $totalesReac  indices de $this->_totales
     * @property array $totalesPot  indices de $this->_totales
     * @property DateTime $actualizado  Fecha de actualización de los datos (excepto lecturas)
     * 
     * 
     * Relativas al uso
     * 
     * @property Furbyus\sips\Model\Consumos $consumoAnual Total, P1-P6 año en curso
     * @property Furbyus\sips\Model\Consumos $consumoAnualAnterior Total, P1-P6 año anterior
     * @property Furbyus\sips\Model\Consumos $ratioConsumo P1-P6 (se modifica la unidad, por pu (per unit))
     * 
     * 
     * Relativas a la ubicación
     * 
     * @property String $direccion -
     * @property String $localidad -
     * @property String $codigoPostal -
     * @property String $provincia -
     * @property String $codigoProvincia -
     * @property String $lat -
     * @property String $lng -
     * 
     * 
     * Relativas a la instalación física
     * 
     * @property String $propietarioEquipoMedida ej: "EMPRESA DISTRIBUIDORA", "TITULAR"
     * @property String $propietarioIcp ej:"EMPRESA DISTRIBUIDORA", "TITULAR"
     * @property Furbyus\sips\Model\EstadoTelegestion $telegestion
     * @property String $tension ej: "1X230"
     * @property Float $potenciaMaximaBiestable ej:3.4
     * @property Float $potenciaMaximaInstalada ej:3.4
     * @property Integer $tipoPuntoMedida 1, 2, 3, 4 o 5 según Art.7 Real Decreto 1110/2007->https://www.boe.es/buscar/act.php?id=BOE-A-2007-16478#a7
     * @property Furbyus\sips\Model\Potencias $potenciaContador P1-P6
     * @property String $fasesEquipoMedida ej: "M", "T" (solo Nemon)
     * @property String $indicativoIcp ej: "ICP INSTALADO" (solo Nemon)
     * 
     * 
     * Relativas a la situación personal del titular
     * o bien a la relación entre comercializadora y titular.
     * ej: se ha cortado la luz previamente, autoconsumo, etc
     * 
     * @property Integer $cortes ej: 0, 1, 2, 3 (numero de veces que se ha cortado)
     * @property Integer $impago ej: 0, 1
     * @property Float $importeImpagos (solo Nabalia)
     * @property Float $fianza (solo Nemon)
     * @property Boolean $primeraVivienda (solo Nemon)
     * @property String $personaContacto (solo Nemon)
     * @property String $cargoPersonaContacto (solo Nemon)
     * @property String $perfilConsumo ej: "PA" (solo Nemon)
     * @property String $autoconsumo ej: "Sin autoconsumo" (solo Nemon)
     * @property String $bonoSocial ej: "0", "1"
     * 
     * 
     * Otras
     * 
     * @property undefined $derechoExtension solo Nemon
     * @property undefined $derechoAccesoLlano solo Nemon
     * @property undefined $derechoAccesoValle solo Nemon
     * @property undefined $trimestreCambioContador solo Nemon
     * @property undefined $lecturasNemon solo Nemon
     * @property DateTime $fechaLimite solo Nemon
     * @property undefined $precioOptim solo Nemon
     * @property undefined $precioReactiva solo Nemon
     * @property undefined $precioReactivaOptim solo Nemon
     * @property undefined $energyGwh solo Nemon
     * @property undefined $sumsTotal solo Nemon
     */
    /*
     * Constructor para instanciar el objeto
     * @param object $sipsObject <p>El objeto devuelto por el proveedor de información</p>
     * @param string $timezone <p>La zona horaria para el manejo de las DateTime</p>
     * @return object <p>object implementing iSuministro interface</p>
     */

    public function __construct($sipsObject, $timezone = 'europe/madrid');

    /*
     * format nos retorna un objeto de tipo Furbyus\sips\Model\Suministro o un objeto en el formato del proveedor especificado. Usa el método $this->_suministro->format($format)
     * @param string $format <p>Nombre de uno de los proveedores disponibles, se usa para devolver la información con el formato de ese proveedor. Ejemplo "Nabalia", "Nemon", "CIAS"</p>
     * @return object <p>Objeto de tipo \Furbyus\sips\Model\Suministro o un \stdClass con las propiedades típicas del proveedor especificado en $format</p>
     */

    public function format($format = 'Default');
}
