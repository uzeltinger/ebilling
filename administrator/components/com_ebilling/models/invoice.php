<?php
defined('_JEXEC') or die;
		include JPATH_COMPONENT . '/afip/src/Afip.php';
        
class EbillingModelInvoice extends JModelAdmin
{	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->afip = new Afip(array('CUIT' => 20220531695));
	}
	public function getTable($type = 'Invoices', $prefix = 'EbillingTable', $config = array())
	{
	$t=JTable::getInstance($type, $prefix, $config);
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');
		JForm::addFieldPath('JPATH_ADMINISTRATOR/components/com_ebilling/models/fields');

		$form = $this->loadForm('com_ebilling.invoice', 'invoice', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}		

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_ebilling.edit.invoice.data', array());
	
		if (empty($data)) {
			$data = $this->getItem();
		}
		//print_r($data );
		if(!$data->CbteNro){
			$data->CbteNro = $this->getLastInvoice()+1; 			
		}
		if (!$this->getState('invoice.id') || $this->getState('invoice.id') == 0)
			{
			$date = JFactory::getDate();
			$data->CbteFch	= $date->toSql();
			$data->FchServDesde	= $date->toSql();
			$data->FchServHasta	= $date->toSql();
			$data->FchVtoPago	= $date->toSql();				
			}
		return $data;
	}	
	
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__ebilling_invoices');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
			//$table->modified	= $date->toSql();
			//$table->modified_by	= $user->get('id');
		}
	}	
	
	protected function getReorderConditions($table = null)
	{
		//$condition = array();
		//$condition[] = 'catid = '.(int) $table->catid;
		//return $condition;
	}

		
	
	public function getInvoiceProducts($invoice_id=0)
		{
			if($invoice_id==0){
				$invoice_id=$this->getState('invoice.id');
			}
		if ($invoice_id!=0)
			{
			$db 	= JFactory::getDBO(); 
			$query = 'SELECT id, codigo, detalle, detalle_texto, cantidad, precio, descuentoPorcentaje, descuento, importe, product_id FROM #__ebilling_invoices_items WHERE invoice_id = '.$invoice_id.' order by ordering asc';			
			$db->setQuery($query);        
			$products = $db->loadObjectList();		
			return $products;	
			}			
		}
	public function getProducts()
		{
			$db 	= JFactory::getDBO(); 
			$query = 'SELECT id, codigo, detalle, precio FROM #__ebilling_products';
			$db->setQuery($query);        
			$products = $db->loadObjectList();		
			return $products;				
		}
	public function getProductsList()
		{
			$db 	= JFactory::getDBO(); 
			$query = 'SELECT id as value, detalle as text FROM #__ebilling_products';	
			$db->setQuery($query);        
			$products = $db->loadObjectList();		
			return $products;				
		}
		
	public function getDetalleTexto($id,$texto)
		{
		if ($id != 0)
			{
			$db 	= JFactory::getDBO(); 
			$query = 'SELECT detalle FROM #__ebilling_products WHERE id = '.$id;			
			$db->setQuery($query);        
			$detalle = $db->loadResult();		
			return $detalle;	
			}else{
				return $texto;
			}			
		}

	public function saveCae($newData){
		$row = $this->getTable();

      // Bind the form fields to the hello table
      if (!$row->bind($newData)) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }

      // Make sure the hello record is valid
      if (!$row->check()) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }

      // Store the web link table to the database
      if (!$row->store()) {
         $this->setError( $row->getErrorMsg() );
         return false;
      }
	  $this->saveInvoiceState($row->id,10);
	  $this->savePdf($row->id);
	  $this->sendEmail($row->id);
      return true;
	}

	public function save($data)
		{
		$db = JFactory::getDbo();
		$input = JFactory::getApplication()->input;
		$lastInvoiceAutorized = $this->getLastInvoiceAutorized($data['PtoVta'],$data['CbteTipo']);
		//$lastInvoiceAutorized = 1;
		$data['CbteNro'] = $lastInvoiceAutorized->CbteNro+1;
		$CbteTipoNombre = EbillingHelper::getNombreComprobanteById($data['CbteTipo']);
		$deData = EbillingHelper::getDeData();
		$profileData = $this->getProfile($data['profile_id']);
		
		$data['deRazonSocial'] = $deData['deRazonSocial'];
		$data['deDireccion'] = $deData['deDireccion'];		
		$data['deCondIva'] = $deData['deCondIva'];
		$data['deDocTipo'] = $deData['deDocTipo'];
		$data['deDocNro'] = $deData['deDocNro'];
		$data['deIibbNumero'] = $deData['deIibbNumero'];
		$data['deInicioActividad'] = $deData['deInicioActividad'];

		$data['CbteDesde'] = $data['CbteNro'];
		$data['CbteHasta'] = $data['CbteNro'];
		
		$data['aDocTipo'] = $profileData->DocTipo;
		$data['aDocNum'] = $profileData->DocNro;
		$data['aCondIva'] = $profileData->CondIva;
		$data['aCondVta'] = $profileData->CondVta;
		$data['aRazonSocial'] = $profileData->RazonSocial;
		$data['aDireccion'] = $profileData->Direccion;
		$data['aCodPostal'] = $profileData->CodPostal;
		$data['aCiudad'] = $profileData->Ciudad;
		$data['aProvincia'] = $profileData->Provincia;
		$data['aPais'] = $profileData->Pais;
		
		//CantReg=1
		
		//echo '<pre>';print_r( $profileData );
		$invoiceId = $input->get('invoiceId');
		$detalleCodigo = $input->get('detalleCodigo');
		$detalleProducto = $input->get('detalleProducto');
		$detalleProductoTexto = $input->get('detalleProductoTexto');
		$detalleCantidad = $input->get('detalleCantidad');
		$detallePrecio = $input->get('detallePrecio');
		$detalleDescuentoPorcentaje = $input->get('detalleDescuentoPorcentaje');
		$detalleDescuento = $input->get('detalleDescuento');
		$detalleSubtotal = $input->get('detalleSubtotal');
		$invoiceSubtotal = $input->get('invoiceSubtotal');
		$invoiceTotal = $input->get('invoiceTotal');

		$data['subtotal'] = $invoiceSubtotal;
		$data['total'] = $invoiceTotal;

		echo '<pre>';print_r($detallePrecio);
		//echo '<pre>';print_r($data);		
		
		$return = parent::save($data);

		if($invoiceId>0){

		}else{
			$invoiceId= $db->insertid();
			$this->saveInvoiceState($invoiceId,1);
		}
//echo $this->getDetalleTexto($detalleProducto[0],$detalleProductoTexto[0]);die();
		if($detallePrecio[0]!='' ){
			
			try
			{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
					->delete($db->quoteName('#__ebilling_invoices_items'))
					->where($db->quoteName('invoice_id') . ' = ' . (int) $invoiceId);
				$db->setQuery($query);
				$db->execute();

			$tuples = array();
			$order = 0;

			foreach ($detallePrecio as $k => $v)
			{
				echo $this->getDetalleTexto($detalleProducto[$k],$detalleProductoTexto[$k]);
				if(!$detalleProducto[$k]){$detalleProducto[$k]=0;}
				if(!$detalleCodigo[$k]){$detalleCodigo[$k]='""';}
				if(!$detalleDescuentoPorcentaje[$k]){$detalleDescuentoPorcentaje[$k]=0;}
				if(!$detalleDescuento[$k]){$detalleDescuento[$k]=0;}
				//id	invoice_id	codigo	detalle	cantidad	precio	descuento	importe	ordering	product_id
				$order++;
				$tuples[] = '(null, ' . 
					$invoiceId . ', ' . 
					$detalleCodigo[$k] . ', ' . 
					$detalleProducto[$k] . ', "' . 
					$this->getDetalleTexto($detalleProducto[$k],$detalleProductoTexto[$k]) . '", ' . 
					$detalleCantidad[$k] . ', ' . 
					$detallePrecio[$k] . ', ' . 
					$detalleDescuentoPorcentaje[$k] . ', ' . 
					$detalleDescuento[$k] . ', ' . 
					$detalleSubtotal[$k] . ', ' . 
					($order++) . ', ' . 					
					$detalleProducto[$k] . ')';
			}

			$db->setQuery('INSERT INTO #__ebilling_invoices_items VALUES ' . implode(', ', $tuples));
			echo 'INSERT INTO #__ebilling_invoices_items VALUES ' . implode(', ', $tuples);
			$db->execute();			
			}
			catch (RuntimeException $e)
			{
				print_r($e->getMessage());die();
				//return false;
			}
			
		}
		
		return $return;
		}

		public function saveDuplicate($newData){
			$row = $this->getTable();
		  // Bind the form fields to the hello table
		  	if (!$row->bind($newData)) {
				echo  $this->setError($this->_db->getErrorMsg());
			 	return false;
		  	}
	
		  // Make sure the hello record is valid
		  if (!$row->check()) {
			echo   $this->setError($this->_db->getErrorMsg());
			 return false;
		  }
	
		  // Store the web link table to the database
		  if (!$row->store()) {
			echo   $this->setError( $row->getErrorMsg() );
			 return false;
		  }
		 
		  $this->saveInvoiceState($row->id,2);
		  
		  return $row->id;
		}

		function duplicate($invoice_id){	
			$db = JFactory::getDbo();		
			$params = JComponentHelper::getParams( 'com_ebilling' );	
			//get the current data
			$query = ' SELECT i.* FROM #__ebilling_invoices as i '.
					 ' WHERE i.id = '.$invoice_id;
			$db->setQuery( $query );
			$data = $db->loadAssoc();
			$data['id'] = 0;
			$data['CAE'] = '';
			$data['CAEFchVto'] = '';
			$data['recurrent'] = 0;
			$lastInvoiceAutorized = $this->getLastInvoiceAutorized($data['PtoVta'],$data['CbteTipo']);
			$data['CbteNro'] = $lastInvoiceAutorized->CbteNro+1;

			$return = $this->saveDuplicate($data);
			$invoiceId = $return;
			
			$query = 	' SELECT * FROM #__ebilling_invoices_items '.
						' WHERE invoice_id = ' . $invoice_id .
						' ORDER BY ordering ';
			$db->setQuery( $query );
			$items = $db->loadAssocList();

			$tuples = array();
			$order = 0;

			foreach ($items as $k => $v)
			{
				//id	invoice_id	codigo	detalle	cantidad	precio	descuento	importe	ordering	product_id
				$order++;
				$tuples[] = '(null, ' . 
					$invoiceId . ', "' . 
					$v['codigo'] . '", "' . 
					$v['detalle'] . '", "' . 
					$v['detalle_texto'] . '", ' . 
					$v['cantidad'] . ', ' . 
					$v['precio'] . ', ' . 
					$v['descuentoPorcentaje'] . ', ' . 
					$v['descuento'] . ', ' . 
					$v['importe'] . ', ' . 
					($order++) . ', "' . 					
					$v['product_id'] . '")';
			}
			$db->setQuery('INSERT INTO #__ebilling_invoices_items VALUES ' . implode(', ', $tuples));
			$db->execute();	
			return $invoiceId;
			//print_r('INSERT INTO #__ebilling_invoices_items VALUES ' . implode(', ', $tuples)); die();
		//	return $this->store($invoice, $items, $payments);	
		}


	public function getProfile($profileId=null)
		{
			if($profileId){
				$db 	= JFactory::getDBO(); 
				$user = JFactory::getUser();	
				$query = 'SELECT * FROM #__ebilling_profiles WHERE id = '.$profileId;		
				$db->setQuery($query);        
				$profile = $db->loadObject();		
				return $profile;
			}
			return null;		
		}
	public function getInvoice($invoiceId=null)
		{
		if($invoiceId)
			{
				$db 	= JFactory::getDBO(); 
				$user = JFactory::getUser();	
				$query = 'SELECT * FROM #__ebilling_invoices WHERE id = '.$invoiceId;		
				$db->setQuery($query);        
				$invoice = $db->loadObject();		
				return $invoice;
			}
		return null;		
		}
	public function getLastInvoice()
		{		
			$db 	= JFactory::getDBO();
			$query = 'SELECT MAX(CbteNro) FROM #__ebilling_invoices';		
			$db->setQuery($query);        
			$CbteNro = $db->loadResult();		
			return $CbteNro;					
		}
		
	public function getLastInvoiceAutorized($sales_point,$type){		
		$lastVoucher = $this->afip->ElectronicBilling->GetLastVoucher($sales_point, $type);
		return $lastVoucher;
	}
		
	public function setRecurrent(&$pks, $value = 1)
	{        
		$table = $this->getTable();
		$pks   = (array) $pks;
		foreach ($pks as $i => $pk)
		{			
			if ($table->load($pk))
			{
				$table->recurrent = $value;
				$table->checked_out = 0;
				$table->checked_out_time = $this->_db->getNullDate();
				$table->check();
				if (!$table->store())
				{
					$this->setError($table->getError());
				}
				//echo '<pre>';				print_r($table);die();
			}
		}
		return true;
	}
	
	public function obtenerCae($invoiceId)
    {        
        $invoice = $this->getInvoice($invoiceId);
        
        $afip = $this->afip;
        $sales_point = $invoice->PtoVta;// 	Sales point to ask for last voucher  
        $type = $invoice->CbteTipo;// 		Voucher type to ask for last voucher
        $lastVoucher = $afip->ElectronicBilling->GetLastVoucher($sales_point, $type);
        $ImpTotal = $invoice->total;
        $ImpIVA = $invoice->total-$invoice->subtotal;
        $ImpNeto = $invoice->subtotal;
        $data = array(
            'CantReg' 		=> 1, // Cantidad de comprobantes a registrar
            'PtoVta' 		=> $invoice->PtoVta, // Punto de venta
            'CbteTipo' 		=> $invoice->CbteTipo, // Tipo de comprobante (ver tipos disponibles) 
            'Concepto' 		=> $invoice->Concepto, // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
            'DocTipo' 		=> $invoice->aDocTipo, // Tipo de documento del comprador (ver tipos disponibles)
            'DocNro' 		=> (float)$invoice->aDocNum, // Numero de documento del comprador
            'CbteDesde' 	=> $invoice->CbteNro, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
            'CbteHasta' 	=> $invoice->CbteNro, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
            'CbteFch' 		=> str_replace('-','',$invoice->CbteFch), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
            'ImpTotal' 		=> $ImpTotal, // Importe total del comprobante
            'ImpTotConc' 	=> 0, // Importe neto no gravado
            'ImpNeto' 		=> $ImpNeto, // Importe neto gravado
            'ImpOpEx' 		=> 0, // Importe exento de IVA
            'ImpIVA' 		=> $ImpIVA, //Importe total de IVA
            'ImpTrib' 		=> 0, //Importe total de tributos
            'FchServDesde' 	=> str_replace('-','',$invoice->FchServDesde), // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            'FchServHasta' 	=> str_replace('-','',$invoice->FchServHasta), // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            'FchVtoPago' 	=> str_replace('-','',$invoice->FchVtoPago), // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
            'MonId' 		=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
            'MonCotiz' 		=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
            /*'CbtesAsoc' 	=> array( // (Opcional) Comprobantes asociados
                array(
                    'Tipo' 		=> 6, // Tipo de comprobante (ver tipos disponibles) 
                    'PtoVta' 	=> 1, // Punto de venta
                    'Nro' 		=> 1, // Numero de comprobante
                    'Cuit' 		=> 20111111112 // (Opcional) Cuit del emisor del comprobante
                    )
                ),
            'Tributos' 		=> array( // (Opcional) Tributos asociados al comprobante
                array(
                    'Id' 		=>  99, // Id del tipo de tributo (ver tipos disponibles) 
                    'Desc' 		=> 'Ingresos Brutos', // (Opcional) Descripcion
                    'BaseImp' 	=> 150, // Base imponible para el tributo
                    'Alic' 		=> 5.2, // Alícuota
                    'Importe' 	=> 7.8 // Importe del tributo
                )
            ), */
            'Iva' 			=> array( // (Opcional) Alícuotas asociadas al comprobante
                array(
                    'Id' 		=> 5, // Id del tipo de IVA (ver tipos disponibles) 
                    'BaseImp' 	=> $ImpNeto, // Base imponible
                    'Importe' 	=> $ImpIVA // Importe 
                )
            ), 
            /*
            'Opcionales' 	=> array( // (Opcional) Campos auxiliares
                array(
                    'Id' 		=> 17, // Codigo de tipo de opcion (ver tipos disponibles) 
                    'Valor' 	=> 2 // Valor 
                )
            ), */
            /*
            'Compradores' 	=> array( // (Opcional) Detalles de los clientes del comprobante 
                array(
                    'DocTipo' 		=> 80, // Tipo de documento (ver tipos disponibles) 
                    'DocNro' 		=> 30714343919, // Numero de documento
                    'Porcentaje' 	=> 100 // Porcentaje de titularidad del comprador
                )
            )*/
        );
        
        $voucher = $afip->ElectronicBilling->CreateVoucher($data);
        
        if($voucher['CAE']){
            $newData['id']=$invoiceId;
            $newData['CAE']=$voucher['CAE'];
            $newData['CAEFchVto']=$voucher['CAEFchVto'];
            $saveCae = $this->saveCae($newData);
			//$this->setMessage('CAE guardado');
			return true;
        }else{
            //$this->setError('ERROR : CAE NO guardado');
			//$this->setMessage('ERROR : CAE NO guardado');
			return false;
        }
        
    }

	public function saveInvoiceState($invoice_id, $state){
		$user = JFactory::getUser();
		$dataToStore['id'] = 0;
		$dataToStore['invoice_id'] = $invoice_id;		
		$dataToStore['state'] = $state;
		$dataToStore['created'] = JFactory::getDate()->toSql();
		$dataToStore['created_by'] =$user->get('id');
		$row = $this->getTable('InvoiceState');
		if (!$row->bind($dataToStore)) {
			echo $this->_db->getErrorMsg(); die();
			//return false;
		}
		if (!$row->store()) {
			echo $this->_db->getErrorMsg(); die();
			//return false;
		}		
		$db = JFactory::getDbo();		
		$db->setQuery('UPDATE #__ebilling_invoices SET `state` = '.$state.' WHERE `id` = ' . $invoice_id);
		$db->execute();	

	}

	public function sendEmail($invoice_id){
		$app = JFactory::getApplication();	
		
		$mailfrom = $app->get('mailfrom');
		$fromname = $app->get('fromname');
		$sitename = $app->get('sitename');
		$mailSubject = $app->get('sitename');
		

		$invoice = $this->getInvoice($invoice_id);
		$profile = $this->getProfile($invoice->profile_id);

		$FacturaNumero = EbillingHelper::getFacturaNumero($invoice->CbteNro,$invoice->PtoVta);
		$pdfName = $FacturaNumero . '.pdf';
		$pdfLink = JUri::root(). 'pdfs/' . $invoice->aDocNum . '/' . $FacturaNumero . '.pdf';

		$path = JPath::clean(JPATH_SITE . '/pdfs/' . $invoice->aDocNum );
		$filePdf = $path . '/' . $FacturaNumero . '.pdf';
	//echo $filePdf; die();
		
		
		$mailBody = '<b>Estimado/a ' . $profile->name . ':</b>';
		$mailBody .= '<br>Le informamos que está a disposición la última versión de su Factura.';
		$mailBody .= '<br>Hemos adjuntado una copia en este mismo correo';
		$mailBody .= '<br>También puede visualizarla online  <a href="'.$pdfLink.'">' . $pdfName . '</a>';
		$mailBody .= '<br>Gracias por utilizar nuestros servicios.';

		$mail = JFactory::getMailer();
		$mail->isHtml(true);
		$mail->addRecipient($profile->email);
		$mail->addBcc('arrobatouch@gmail.com');
		$mail->addCc('fabiouz@gmail.com');
		$mail->addReplyTo($mailfrom, $fromname);
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject('Factura ' . $FacturaNumero);
		$mail->setBody($mailBody);
		$mail->addAttachment($filePdf);
		$sent = $mail->Send();
		if($sent)
			{
				echo 'email enviado';
				$this->saveInvoiceState($invoice_id,11);
			}else{
				echo 'error email';
			}
	}


	public function getPdfData($invoice_id=0)
		{
		if($invoice_id==0){
			$invoice_id=$this->getState('invoice.id');
		}
		
		$item = $this->getInvoice($invoice_id);
		$profile = $this->getProfile($item->profile_id);
		$InvoiceProducts = $this->getInvoiceProducts($invoice_id);
		$imagePath = JPATH_SITE."/images/logodiportalfactura.png";

		$id = $item->id;
		$CAE = $item->CAE;
		$CAEFchVto = JFactory::getDate($item->CAEFchVto)->format('d/m/Y');
		$CbteNro = $item->CbteNro;
		$deRazonSocial = $item->deRazonSocial;
		$deDireccion = $item->deDireccion;
		$deCondIva = $item->deCondIva;
		$deDocTipo = $item->deDocTipo;
		$deDocNro = $item->deDocNro;
		$deIibbNumero = $item->deIibbNumero;
		$deInicioActividad = $item->deInicioActividad;
		$CbteTipo = $item->CbteTipo;
		$CbteTipoNombreLetra = EbillingHelper::getNombreComprobanteById($CbteTipo);
		$CbteTipoNombre = $CbteTipoNombreLetra['nombre'];
		$CbteTipoLetra = $CbteTipoNombreLetra['letra'];
		$PtoVta = $item->PtoVta;
		$CbteFch = JFactory::getDate($item->CbteFch)->format('d/m/Y');
		$Concepto = $item->Concepto;
		$FchServDesde = JFactory::getDate($item->FchServDesde)->format('d/m/Y');
		$FchServHasta = JFactory::getDate($item->FchServHasta)->format('d/m/Y');
		$FchVtoPago = JFactory::getDate($item->FchVtoPago)->format('d/m/Y');
		$aDocTipo = $item->aDocTipo;
		$aDocNum = $item->aDocNum;
		$aCondIva = $item->aCondIva;
		$aCondVta = $item->aCondVta;
		$aRazonSocial = $item->aRazonSocial;
		$aDireccion = $item->aDireccion;
		$aCodPostal = $item->aCodPostal;
		$aCiudad = $item->aCiudad;
		$aProvincia = $item->aProvincia;
		$aPais = $item->aPais;
		$total = $item->total;
		$subtotal = $item->subtotal;

		$FacturaNumero = EbillingHelper::getFacturaNumero($CbteNro,$PtoVta);
		$aDireccionCompleta = $aDireccion.'. '.$aCiudad.', '.$aProvincia;
		$aCondIva = EbillingHelper::getCondicionIva($aCondIva);
		$aDocTipo = EbillingHelper::getDocumentoTipo($aDocTipo);
		$digitoVerificador = 'x';
		$fecha = JFactory::getDate($item->CAEFchVto)->format('Ymd');
		$barCode = $deDocNro.$deDocTipo.'000'.$PtoVta.$CAE.$fecha;
		//$barCode = '202027239510103336823115822634020180615';
		$barCodeString = str_split($barCode);
		$posicion = 1;
		$pares = 0;
		$impares = 0;
		$paresList = '';
		$imparesList = '';
		for ($i=0; $i < count($barCodeString); $i++) {
			if($posicion%2==0){
				$pares += $barCodeString[$i];
				$paresList .=  $barCodeString[$i] . ' + ';
			}else{
				$impares += $barCodeString[$i];
				$imparesList .=  $barCodeString[$i] . ' + ';
			}
			$posicion++;
		}
		$etapaDos = $impares*3;
		$etapaCuatro = $etapaDos + $pares;
		for ($i=0; $i < 10; $i++) { 
			$resultado = $etapaCuatro+$i;
			if($resultado%10==0){
				$digitoVerificador = $i;
			}    
		}
		$barCode .= $digitoVerificador;

		$renglones = '';
		$detalles = $InvoiceProducts;
		foreach ($detalles as $detalle) {
			$renglones .= '<tr class="renglon">';
			$renglones .= '<td align="left">'.$detalle->codigo.'</td>';
			$renglones .= '<td align="left">'.$detalle->detalle_texto.'</td>';
			$renglones .= '<td align="right">'.$detalle->cantidad.'</td>';
			$renglones .= '<td align="right">'.$detalle->precio.'</td>';
			$renglones .= '<td align="right">'.$detalle->descuento.'</td>';
			$renglones .= '<td align="right">'.$detalle->importe.'</td>';
			$renglones .= '</tr>';    
		}
		$renglones .= '<tr class="renglonvacio"><td></td></tr>
				<tr class="renglonvacio"><td></td></tr>
				<tr class="renglonvacio"><td></td></tr>
				<tr class="renglonvacio"><td></td></tr>
				<tr class="renglonvacio"><td></td></tr>
				<tr class="renglonvacio"><td></td></tr>
				<tr class="renglonvacio"><td></td></tr>
				<tr class="renglonvacio"><td></td></tr>
				<tr class="renglonvacio"><td></td></tr>';

		$iva25='';
		$iva05='';
		$iva105='';
		$iva21=$total-$subtotal;
		$iva27='';
		$impuestos = $total-$subtotal;

$html = <<<EOF
<style>
    body{
        font-family: sans-serif, Arial, Helvetica;
        font-size: 8pt;
    }
    table.cabecera img {
        width: 100%;
	}
	table.cabecera .centro {
		padding-top: 20px;
	}
    table.cabecera .centro p{
        margin: 10px 0;
    }
    span.letra{
		margin: 0;
		padding:0;
		font-size: 40pt;
		line-height: 20pt;
	}
	.comprador tr td{
		padding: 20px;
	}
    .detalle .cabecera{
        width: 100%;
        background: #E0F4FF;
        border: 1px solid #B7E6FB;
        border-radius: 10px;
    }
    .detalle .renglon td{
        border-bottom: 1px solid #B7E6FB;
    }
    .totales {
        width: 100%;
        background: #E0F4FF;
        border: 1px solid #B7E6FB;
        border-radius: 10px;
    }
	</style>
	<table width="100%" class="cabecera" cellpadding="4" cellspacing="6">
        <tr>
            <td width="40%" align="left">
                <img width="290" height="68" src="$imagePath" />
                <br>
                <h4>RIVERAS DANIEL ALBERTO</h4>
                <p>Jose Olaya 2134 Dpto:4, Ramos Mejía, Buenos Aires, Argentina.</p>
            </td>
			<td width="20%" align="center" class="centro">
			<p><br></p>
                <p><span class="letra">$CbteTipoLetra</span></p>
                <p>001</p>
                <p>Responsable Inscripto</p>
            </td>
            <td width="40%" align="left" valign="top">
                <h2>$CbteTipoNombre</h2>
                <table>
                    <tr>
                        <td><b>Número:</b></td><td>$FacturaNumero</td>
                    </tr>
                    <tr>
                        <td><b>Fecha:</b></td><td>$CbteFch</td>
                    </tr>
                    <tr>
                        <td><b>CUIT:</b></td><td>20-20272395-1</td>
                    </tr>
                    <tr>
                        <td><b>Ing. Brutos:</b></td><td>20-20272395-1</td>
                    </tr>
                    <tr>
                        <td><b>Inicio Act.:</b></td><td>01/07/2018</td>
                    </tr>
                </table>
            </td>                 
        </tr>
    </table>
    <hr>
    <table width="100%" class="comprador" cellpadding="0" cellspacing="0">
        <tr>
            <td width="65%" align="left" valign="top">
				<table width="100%" class="cabecera" cellpadding="5" cellspacing="0">
                    <tr>
                        <td width="15%"><b>Sr. (es):</b></td>
                        <td width="85%">$aRazonSocial</td>
                    </tr>
                    <tr>
                        <td width="15%"><b>Domicilio:</b></td>
                        <td width="85%">$aDireccionCompleta</td>
                    </tr>
                </table>
            </td>
            <td width="35%" align="left" valign="top">
				<table width="100%" class="cabecera" cellpadding="5" cellspacing="0">
                    <tr>
                        <td width="30%"><b>$aDocTipo:</b></td>
                        <td width="70%">$aDocNum</td>
                    </tr>
                    <tr>
                        <td width="30%"><b>Cond. IVA:</b></td>
                        <td width="70%">$aCondIva</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <hr>
    <table width="100%" class="varios" cellpadding="4" cellspacing="6">
        <tr>
            <td><b>Moneda:</b> Pesos Argentinos</td>
            <td align="center"><b>Cond. Venta:</b> Cuenta Corriente</td>
            <td align="right"><b>Fecha Vto.:</b>$FchVtoPago</td>
        </tr>
    </table>
    
    <table width="100%" class="detalle" cellpadding="10" cellspacing="0">
        <tr class="cabecera" bgcolor="#E0F4FF">
            <th width="10%" align="left">Cód.</th>
            <th width="40%" align="left">Artículo</th>
            <th width="10%" align="right">Cant.</th>
            <th width="15%" align="right">Precio</th>
            <th width="10%" align="right">Dto.</th>
            <th width="15%" align="right">Importe</th>
        </tr>
        $renglones        
    </table>
    <hr>
    <table width="100%" class="impuestos" cellpadding="4" cellspacing="0">        
        <tr class="renglon">
            <td width="20%" align="center"><b>IVA 2.5:</b>$iva25</td>
            <td width="20%" align="center"><b>IVA 5:</b>$iva05</td>
            <td width="20%" align="center"><b>IVA 10.5:</b>$iva105</td>
            <td width="20%" align="center"><b>IVA 21:</b>$iva21</td>
            <td width="20%" align="center"><b>IVA 27:</b>$iva27</td>
        </tr>
    </table>
    <table width="100%" class="codigobarras" cellpadding="4" cellspacing="6">
        <tr>
            <td width="100%" align="left" valign="top"></td>
        </tr>
    </table>
    <table width="100%" class="totales" cellpadding="4" cellspacing="0">
        <tr bgcolor="#E0F4FF">
            <td width="35%" align="left"><b>Bruto:</b> <span>$subtotal</span></td>
            <td width="30%" align="center"><b>Impuestos:</b> <span>$impuestos</span></td>
            <td width="35%" align="right"><b>Total: $</b> <span><b>$total</b></span></td>
        </tr>        
    </table>
    <table width="100%" class="varios" cellpadding="10" cellspacing="6">
        <tr>
            <td width="65%" align="left" valign="top"></td>
        </tr>
    </table>
    <table width="100%" class="codigobarras" cellpadding="4" cellspacing="6">
        <tr>
            <td width="100%" align="left" valign="top"></td>
        </tr>
    </table>
    <table width="100%" class="cae" cellpadding="10" cellspacing="0">
        <tr>
            <td width="30%" align="left"><b>Comprobante Autorizado</b></td>
            <td width="10%" align="left"><b>CAE:</b></td>
            <td width="20%" align="left"><span>$CAE</span></td>            
            <td width="20%" align="left"><b>Fecha Vto. CAE:</b></td>
            <td width="20%" align="left"><span>$CAEFchVto</span></td>            
        </tr>
    </table>
EOF;
	$data['html'] = $html;
	$data['barCode'] = $barCode;
	
    jimport('joomla.filesystem.folder');
    $path = JPath::clean(JPATH_SITE . '/pdfs/' . $aDocNum );
    if (!is_dir($path))
        {
        JFolder::create($path);
		}
		
	$data['filePdf'] = $path . '/' . $FacturaNumero . '.pdf';

	return $data;
	}

	public function savePdf($invoice_id){		
		$data = $this->getPdfData($invoice_id);
		// Include the main TCPDF library (search for installation path).
		require_once(JPATH_SITE.'/tcpdf/config/tcpdf_config.php');
		require_once(JPATH_SITE.'/tcpdf/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Arrobatouch');
		$pdf->SetTitle('Factura');
		$pdf->SetSubject('Factura Subject');
		$pdf->SetKeywords('Factura, electrónica');
		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		// ---------------------------------------------------------
		$pdf->SetFont('helvetica', '', 9);
		$pdf->AddPage();
		// output the HTML content
		$pdf->writeHTML($data['html'], true, false, true, false, '');
		// define barcode style
		$style = array(
			'position' => 'C',
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => '',
			'border' => true,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 8,
			'stretchtext' => 4
		);
		$pdf->write1DBarcode($data['barCode'], 'I25', '', '', '', 18, 0.4, $style, 'N');
		$pdf->lastPage();	
		$pdf->Output($data['filePdf'], 'F');
		//$pdf->Output('example_061.pdf', 'I');die();
	}

}