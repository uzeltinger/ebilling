<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class EbillingControllerInvoice extends JControllerForm
{
    public function obtenerCae()
    {
        $cids = $this->input->getVar( 'cid', array(0), 'default', 'array' );
        //print_r($cids); die();
        if (count( $cids ) && $cids[0]!=0) {
            $invoiceId = $cids[0];
        }else{
            $input = JFactory::getApplication()->input;
            $jform = $input->post->get('jform', array(), 'array');
            $invoiceId = $input->get('invoiceId');
        }        
                
        $model = $this->getModel();
        $invoice = $model->getInvoice($invoiceId);
        //echo '<pre>';print_r($invoice);die();

        include JPATH_COMPONENT . '/afip/src/Afip.php';
        $afip = new Afip(array('CUIT' => 20220531695));
        $sales_point = $invoice->PtoVta;// 	Sales point to ask for last voucher  
        $type = $invoice->CbteTipo;// 		Voucher type to ask for last voucher
        $lastVoucher = $afip->ElectronicBilling->GetLastVoucher($sales_point, $type);
        //echo '<pre>';print_r($lastVoucher);die();
        //$invoice->aDocNum = (float)$invoice->aDocNum;
        //if($invoice->aDocNum === 30714343919){echo 'igual';die();}
        $ImpTotal = $invoice->total;
        $ImpIVA = $invoice->total-$invoice->subtotal;
        $ImpNeto = $invoice->subtotal;
        //$ImpTotal = 100;
        //$ImpIVA = 21;

        /*$ImpTotal = 3763.10;
        $ImpIVA = 653.10;
        $ImpNeto = 3110.00;*/

        /*echo '<br>ImpTotal .'.$ImpTotal;
        echo '<br>ImpIVA .'.$ImpIVA;
        echo '<br>ImpNeto .'.$ImpNeto;*/
        //die();
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
        
        //echo '<pre>';print_r($data);die();

        $voucher = $afip->ElectronicBilling->CreateVoucher($data);
        
        if($voucher['CAE']){
            $newData['id']=$invoiceId;
            $newData['CAE']=$voucher['CAE'];
            $newData['CAEFchVto']=$voucher['CAEFchVto'];
            $saveCae = $model->saveCae($newData);
            //$this->setMessage('CAE guardado');
        }else{
            $this->setError('ERROR : CAE NO guardado');
            $this->setMessage('ERROR : CAE NO guardado');
        }


        if (count( $cids )) {
            $this->setRedirect(JRoute::_('index.php?option=com_ebilling&view=invoices', false));
        }else{
            $this->setRedirect(JRoute::_('index.php?option=com_ebilling&view=invoice&layout=edit&id='.$invoiceId, false));

        }
        

        //Array ( [CAE] => 68275658834648 [CAEFchVto] => 2018-07-13 )
/*
        echo '<pre>';
        print_r($input);

        require('0');
        die();
        */

    }

    function duplicate()
	{
        $cids = $this->input->getVar( 'cid', array(0), 'default', 'array' );
        //print_r($cids);die();
		$model = $this->getModel('invoice');
		if (count( $cids )) {
			foreach($cids as $cid) {
				$model->duplicate($cid);
			}
		}
        $msg = JText::_( 'Factura Duplicada' );        
		$this->setRedirect( 'index.php?option=com_ebilling&view=invoices', $msg );
    }

    function sendEmail()
	{
        $cids = $this->input->getVar( 'cid', array(0), 'default', 'array' );
        //print_r($cids);die();
        $model = $this->getModel('invoice');        
		if (count( $cids )) {
			foreach($cids as $cid) {
                $model->savePdf($cid);
				$model->sendEmail($cid);
			}
		}
        $msg = JText::_( 'Factura Enviada' );        
		$this->setRedirect( 'index.php?option=com_ebilling&view=invoices', $msg );
    }    

    public function getPdfUrl(){
        echo 'https://ebilling.local/pdfs/27220531695/0002-00000037.pdf';die();
    }
    
}