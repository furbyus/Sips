<?php

namespace Furbyus\Sips\Traits;

/**
 * SipsMaps
 *
 * @author Francesc Aguilar Martinez
 */
Trait SipsMaps {

    protected $_mapNabalia = [
        'energyType' => 'energyType',
        'fuente' => 'fuente',
        'cups' => 'cup',
        'localidad' => 'pob_sum',
        'codigoPostal' => 'cpo_sum',
        'direccion' => 'dir_sum',
        'potenciaMaximaBiestable' => 'pm_bie',
        'tension' => 'ten_car',
        'ultimoMovimiento' => 'fum',
        'ultimoCambioComercializadora' => 'fucc',
        'ultimaLectura' => 'ful',
        'alta' => 'fal_sum',
        'propietarioIcp' => 'pipc', //WARNING, quizas este equivocado, pero creo que deberiamos avisar a la gente de Nabalia de que no tiene ningun sentido el nombre de este campo, en cualquier caso deberia ser "picp" y no "pipc"
        'propietarioEquipoMedida' => 'pem',
        'tipoPuntoMedida' => 'tip_pm',
        'impago' => 'imp_nd',
        'cortes' => 'cor_nd',
        'importeImpagos' => 'impimp',
        'comercializadora:codigo' => 'codree',
	    'comercializadora:nombre' => 'com_nom',
        'tarifa:nombre' => 'tar',
        'titular:nombreCompleto' => 'ape_tit',
        'provincia:nombre' => 'pro_sum',
        'replace:potencias' => 'pc%n%'
    ];
    protected $_mapNemon = [
        'energyType' => 'energyType',
        'fuente' => 'fuente',
        'cups' => 'CUPS',
        'localidad' => 'Localidad_Suministro',
        'codigoPostal' => 'Cod_Postal_Suministro',
        'direccion' => 'Direccion_Suministro',
        'potenciaMaximaBiestable' => 'Pot_Max_BIE',
        'potenciaMaximaInstalada' => 'Pot_Max_Puesta',
        'tension' => 'Tension',
        'indicativoIcp' => 'Indicativo_ICP',
        'perfilConsumo' => 'Perfil_Consumo',
        'derechoExtension' => 'Der_Extension',
        'derechoAccesoLlano' => 'Der_Acceso_Llano',
        'derechoAccesoValle' => 'Der_Acceso_Valle',
        'fechaLimite' => 'Fec_Lim_Exten',
        'fianza' => 'Fianza',
        'primeraVivienda' => 'Primera_vivienda',
        'fasesEquipoMedida' => 'Fases_Equipo_Medida',
        'autoconsumo' => 'Cod_Autoconsumo',
        'bonoSocial' => 'Bono_Social',
        'lecturasNemon' => 'lecturas',
        'lat' => 'lat',
        'lng' => 'lng',
        'personaContacto' => 'persona_contacto',
        'cargoPersonaContacto' => 'cargo_persona_contacto',
        'precioOptim' => 'optim_price',
        'precioReactiva' => 'react_price',
        'precioReactivaOptim' => 'op_re_price',
        'ultimoMovimiento' => ['Fec_Ult_Mov', 'Fec_Ultimo_Mov_Contrato'],
        'ultimoCambioComercializadora' => ['Fec_Ult_Camb_Comer', 'Fec_Ultimo_Cambio_Comer'],
        'ultimaLectura' => ['lastlectura', 'Fec_Ult_Lect'], //Para GAS, ya que no existe Fec_Ult_Lect -.-
        'alta' => 'Fec_Alta_Suministro',
        'propietarioIcp' => 'Propiedad_ICP',
        'propietarioEquipoMedida' => 'Propiedad_Equipo_Medida',
        'tipoPuntoMedida' => 'Tipo_PM',
        'impago' => 'Impago',
        'cortes' => 'Cortes', 
        //'importeImpagos' => 'Fianza',//TODO ESTO ES ASÍ??????? 
        'provincia:nombre' => 'Provincia_Suministro', 
        'provincia:codigo' => ['Cod_Provincia_Suministro', 'cod_prov'],
        'tarifa:nombre' => 'Tarifa',
        'tarifa:descripcion' => 'Des_Tarifa',
        'distribuidora:codigo' => 'Cod_Dist',
        'distribuidora:nombre' => 'Distribuidora',
        'comercializadora:codigo' => 'Cod_Comer',
        'comercializadora:nombre' => 'Comercialitzadora',
        'cnae:codigo' => 'cnae_code',
        'cnae:descripcion' => 'cnae_desc',
        'replace:potencias' => 'Pot_Cont_P%n%'
    ];
    protected $_mapNabaliaLectura = [
        'tipoLectura' => 'tip',
        'fecha' => 'fec',
        'consumos' => 'c%n%'
    ];
    protected $_mapNemonLectura = [
        'tipoLectura' => 'RE_M1',
        'fecha' => 'FLectM1',
        'consumos' => "C%te%P%n%M1",
        'consumosT' => "T%te%P%n%M1"
    ];

}
