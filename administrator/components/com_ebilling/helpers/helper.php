<?php
/*
EbillingHelper
*/
defined('_JEXEC') or die;

class EbillingHelper
{
	public static $extention = 'com_ebilling';
	
	public static function addSubmenu($vName)
	{		
	$user = JFactory::getUser();
	$userId	= $user->get('id');	
	$manageProduct = $user->authorise('core.manage.product', 'com_ebilling');	
    $coreAdmin = $user->authorise('core.admin', 'com_ebilling');
    $vName = JRequest::getVar('view');

    JSubMenuHelper::addEntry(
        JText::_('Facturas'),
        'index.php?option=com_ebilling&view=invoices',
        $vName == 'invoices');	
    JSubMenuHelper::addEntry(
        JText::_('Perfiles'),
        'index.php?option=com_ebilling&view=profiles',
		$vName == 'profiles');
	JSubMenuHelper::addEntry(
        JText::_('Productos'),
        'index.php?option=com_ebilling&view=products',
        $vName == 'products');
    
    }


    public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.delete', 'core.edit', 'core.edit.state', 'core.manage.product'
		);
		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, 'com_ebilling'));
		}
		return $result;
	}		

	public static function getDocumentoTipo($docTipo){
		$documentos=[];
		$documentos[80]='CUIT';
		$documentos[87]='CDI';
		$documentos[89]='LE';
		$documentos[90]='LC';
		$documentos[91]='CI';
		$documentos[96]='DNI';
		$documentos[94]='Pasaporte';
		$documentos[00]='CI Policía Federal';
		$documentos[30]='Certificado de Migración';
		return $documentos[$docTipo];
	}

	public static function getCondicionIva($condIva){
		$condiciones=[];
		$condiciones['1']='IVA Responsable Inscripto';
		$condiciones['4']='IVA Sujeto Exento';
		$condiciones['5']='Consumidor Final';
		$condiciones['6']='Responsable Monotributo';
		$condiciones['8']='Proveedor del Exterior';
		$condiciones['9']='Cliente del Exterior';
		$condiciones['10']='IVA Liberado - Ley Nº 19.640';
		$condiciones['11']='IVA Responsable Inscripto - Agente de Percepción';
		$condiciones['13']='Monotributista Social';
		$condiciones['15']='IVA No Alcanzado';
		return $condiciones[$condIva];
	}
	
	public static function getFacturaNumero($CbteNro,$PtoVta){		
		$numlength = strlen((string)$CbteNro);		
		$ceros = 8 - $numlength;
		$prefix = '';
		for ($i=0; $i < $ceros; $i++) { 
			$prefix .= '0';
		}
		$FacturaNumero = '000'.$PtoVta.'-'.$prefix.$CbteNro;
		return $FacturaNumero;
	}
	
	public static function getDeData(){		
		$deData['deRazonSocial'] = 'RIVERAS DANIEL ALBERTO';
		$deData['deDireccion'] =  'Jose Olaya 2134 Piso:PB Dpto:D4 - Ramos Mejia, Buenos Aires';		
		$deData['deCondIva'] = 'Responsable Inscripto';
		$deData['deDocTipo'] = '80';
		$deData['deDocNro'] = '20202723951';
		$deData['deIibbNumero'] = '20202723951';
		$deData['deInicioActividad'] = '2008-07-01';
		return $deData;
	}
	public static function getNombreComprobanteById($CbteId=null){
		$CbteTipoArray['1']['generico'] = 'FACTURAS A';
		$CbteTipoArray['2']['generico'] = 'NOTAS DE DEBITO A';
		$CbteTipoArray['3']['generico'] = 'NOTAS DE CREDITO A';
		$CbteTipoArray['4']['generico'] = 'RECIBOS A';
		$CbteTipoArray['5']['generico'] = 'NOTAS DE VENTA AL CONTADO A';
		$CbteTipoArray['6']['generico'] = 'FACTURAS B';
		$CbteTipoArray['7']['generico'] = 'NOTAS DE DEBITO B';
		$CbteTipoArray['8']['generico'] = 'NOTAS DE CREDITO B';
		$CbteTipoArray['9']['generico'] = 'RECIBOS B';
		$CbteTipoArray['10']['generico'] = 'NOTAS DE VENTA AL CONTADO B';
		$CbteTipoArray['11']['generico'] = 'FACTURAS C';
		$CbteTipoArray['12']['generico'] = 'NOTAS DE DEBITO C';
		$CbteTipoArray['13']['generico'] = 'NOTAS DE CREDITO C';
		$CbteTipoArray['15']['generico'] = 'RECIBOS C';
		$CbteTipoArray['16']['generico'] = 'NOTAS DE VENTA AL CONTADO C';
		$CbteTipoArray['1']['nombre'] = 'FACTURA';
		$CbteTipoArray['2']['nombre'] = 'NOTA DE DEBITO';
		$CbteTipoArray['3']['nombre'] = 'NOTA DE CREDITO';
		$CbteTipoArray['4']['nombre'] = 'RECIBO';
		$CbteTipoArray['5']['nombre'] = 'NOTA DE VENTA AL CONTADO';
		$CbteTipoArray['6']['nombre'] = 'FACTURA';
		$CbteTipoArray['7']['nombre'] = 'NOTA DE DEBITO';
		$CbteTipoArray['8']['nombre'] = 'NOTA DE CREDITO';
		$CbteTipoArray['9']['nombre'] = 'RECIBO';
		$CbteTipoArray['10']['nombre'] = 'NOTA DE VENTA AL CONTADO';
		$CbteTipoArray['11']['nombre'] = 'FACTURA';
		$CbteTipoArray['12']['nombre'] = 'NOTA DE DEBITO';
		$CbteTipoArray['13']['nombre'] = 'NOTA DE CREDITO';
		$CbteTipoArray['15']['nombre'] = 'RECIBO';
		$CbteTipoArray['16']['nombre'] = 'NOTA DE VENTA AL CONTADO';
		$CbteTipoArray['1']['letra'] = 'A';
		$CbteTipoArray['2']['letra'] = 'A';
		$CbteTipoArray['3']['letra'] = 'A';
		$CbteTipoArray['4']['letra'] = 'A';
		$CbteTipoArray['5']['letra'] = 'A';
		$CbteTipoArray['6']['letra'] = 'B';
		$CbteTipoArray['7']['letra'] = 'B';
		$CbteTipoArray['8']['letra'] = 'B';
		$CbteTipoArray['9']['letra'] = 'B';
		$CbteTipoArray['10']['letra'] = 'B';
		$CbteTipoArray['11']['letra'] = 'C';
		$CbteTipoArray['12']['letra'] = 'C';
		$CbteTipoArray['13']['letra'] = 'C';
		$CbteTipoArray['15']['letra'] = 'C';
		$CbteTipoArray['16']['letra'] = 'C';
		if($CbteId){
			return $CbteTipoArray[$CbteId];
		}else{
			return $CbteTipoArray;
		}
	}

	public static function getStatesOptions()
	{
		$options = array();
			$options[0] = new JObject();	
			$options[0]->value = 1;
			$options[0]->text = 'Generadas';
			$options[1] = new JObject();
			$options[1]->value = 2;
			$options[1]->text = 'Duplicadas';
			$options[2] = new JObject();
			$options[2]->value = 10;
			$options[2]->text = 'Verificadas';
			$options[3] = new JObject();
			$options[3]->value = 11;
			$options[3]->text = 'Enviadas';
			$options[4] = new JObject();
			$options[4]->value = 99;
			$options[4]->text = 'Recurrentes Originales';

		array_unshift($options, JHtml::_('select.option', '', JText::_('Todas')));
		return $options;
	}

	public static function getComprobanteTipoOptions()
	{
		$options = array();
		$nombres = EbillingHelper::getNombreComprobanteById();
		$opciones = [];
		$i=0;
		foreach ($nombres as $key => $value) {
			$opciones[$i] = new JObject();
			$opciones[$i]->value = $key;
			$opciones[$i]->text = $value['generico'];
			$i++;
		}
		$options = $opciones;		
		array_unshift($options, JHtml::_('select.option', '', JText::_('Tipo de Comprobante')));
		return $options;
	}

	/**
	 * Get client list in text/value format for a select field
	 *
	 * @return  array
	 */
	public static function getClientOptions()
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id AS value, RazonSocial AS text')
			->from('#__ebilling_profiles AS a')
			->where('a.published = 1')
			->order('a.RazonSocial');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		array_unshift($options, JHtml::_('select.option', '', JText::_('Seleccione Cliente')));

		return $options;
	}
    public static function getSSLPage($url) {
		echo $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSLVERSION,3); 
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}

?>