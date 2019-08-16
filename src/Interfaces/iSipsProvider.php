<?php

namespace Furbyus\Sips\Interfaces;

/**
 *  iSipsProvider interface
 * 
 * @author Francesc Aguilar Martinez
 */
interface iSipsProvider {
    /*
     * @property string $cups <p>CUPS del suministro del que queremos la información</p>
     * @property array $sipsConfig <p>Configuraciones para los Proveedores disponibles y habilitados ej: accessKey </p>
     */

    /*
     * Constructor para instanciar el objeto
     * @param string $cups[optional] <p>El CUPS del suministro del que queremos información</p>
     * @return mixed <p>object implementing iSipsProvider interface</p>
     */

    public function __construct($cups = '00000000000000000000');

    /*
     * Si existe la fuente especificada como "fuente disponible", se habilita para pedir información a ésta
     * @param string $fuente <p>El nombre de la fuente que queremos habilitar</p>
     * @return boolean <p>Si se ha habilitado la fuente, retorna true. Si la fuente especificada no existe como "disponible" o hay algún otro error, retorna false</p>
     */

    public function habilitarFuente($fuente);

    /*
     * Si la fuente especificada esta marcada como "habilitada", la deshabilita.
     * @param string $fuente <p>El nombre de la fuente que queremos des-habilitar</p>
     * @return boolean <p>Retorna true si se ha deshabilitado, en cualquier otro caso, retorna false</p>
     */

    public function deshabilitarFuente($fuente);

    /*
     * Función para pedir información al/los SIPS y guardarla como objeto Suministro para más tarde poder recuperar-lo
     * @return boolean <p>retorna true si (cómo mínimo) una de las fuentes nos ha devuelto información relativa a ese suministro. Retorna false si no se ha podido encontrar información de ninguna de las fuentes Habilitadas, o si ha ocurrido algún error al conectar con los proveedores.</p>
     */

    public function requestData();
    /*
     * getData nos retorna un objeto de tipo Furbyus\sips\Model\Suministro o un objeto en el formato del proveedor especificado. Usa el método $this->_suministro->format($format)
     * @param string $format <p>Nombre de uno de los proveedores disponibles, se usa para devolver la información con el formato de ese proveedor. Ejemplo "Nabalia", "Nemon", "CIAS"</p>
     * @return object <p>Objeto de tipo \Furbyus\sips\Model\Suministro o un \stdClass con las propiedades típicas del proveedor especificado en $format</p>
     */

    public function getData($format = 'Default');
}
