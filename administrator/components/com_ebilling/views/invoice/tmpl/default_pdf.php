<?php
/**
 * @package	com_ebilling
 * @copyright	Copyright (C) 2010 Fabio Esteban Uzeltinger, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$imagePath = JPATH_SITE."/images/logodiportalfactura.png";

$id = $this->item->id;
$CAE = $this->item->CAE;
$CAEFchVto = JFactory::getDate($this->item->CAEFchVto)->format('d/m/Y');
$CbteNro = $this->item->CbteNro;
$deRazonSocial = $this->item->deRazonSocial;
$deDireccion = $this->item->deDireccion;
$deCondIva = $this->item->deCondIva;
$deDocTipo = $this->item->deDocTipo;
$deDocNro = $this->item->deDocNro;
$deIibbNumero = $this->item->deIibbNumero;
$deInicioActividad = $this->item->deInicioActividad;
$CbteTipo = $this->item->CbteTipo;
$CbteTipoNombreLetra = EbillingHelper::getNombreComprobanteById($CbteTipo);
$CbteTipoNombre = $CbteTipoNombreLetra['nombre'];
$CbteTipoLetra = $CbteTipoNombreLetra['letra'];
$PtoVta = $this->item->PtoVta;
$CbteFch = JFactory::getDate($this->item->CbteFch)->format('d/m/Y');
$Concepto = $this->item->Concepto;
$FchServDesde = JFactory::getDate($this->item->FchServDesde)->format('d/m/Y');
$FchServHasta = JFactory::getDate($this->item->FchServHasta)->format('d/m/Y');
$FchVtoPago = JFactory::getDate($this->item->FchVtoPago)->format('d/m/Y');
$aDocTipo = $this->item->aDocTipo;
$aDocNum = $this->item->aDocNum;
$aCondIva = $this->item->aCondIva;
$aCondVta = $this->item->aCondVta;
$aRazonSocial = $this->item->aRazonSocial;
$aDireccion = $this->item->aDireccion;
$aCodPostal = $this->item->aCodPostal;
$aCiudad = $this->item->aCiudad;
$aProvincia = $this->item->aProvincia;
$aPais = $this->item->aPais;
$total = $this->item->total;
$subtotal = $this->item->subtotal;

$FacturaNumero = EbillingHelper::getFacturaNumero($CbteNro,$PtoVta);
$aDireccionCompleta = $aDireccion.'. '.$aCiudad.', '.$aProvincia;
$aCondIva = EbillingHelper::getCondicionIva($aCondIva);
$aDocTipo = EbillingHelper::getDocumentoTipo($aDocTipo);
$digitoVerificador = 'x';
$fecha = JFactory::getDate($this->item->CAEFchVto)->format('Ymd');
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
/*
$detalles[0] = new JObject();
$detalles[1] = new JObject();
$detalles[0]->codigo = '001';
$detalles[0]->detalle = 'Servicios Informáticos';
$detalles[0]->cantidad = '1';
$detalles[0]->precio = '1250';
$detalles[0]->descuento = '0';
$detalles[0]->importe = '1250';
$detalles[1]->codigo = '005';
$detalles[1]->detalle = 'Publicación Diportal';
$detalles[1]->cantidad = '1';
$detalles[1]->precio = '3500';
$detalles[1]->descuento = '0';
$detalles[1]->importe = '3500';
*/
$renglones = '';
$detalles = $this->InvoiceProducts;
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

//aaaammdd
$iva25='';
$iva05='';
$iva105='';
$iva21=$total-$subtotal;
$iva27='';
$impuestos = $total-$subtotal;
/*
$this->item->id] => 7
$this->item->CAE] => 68275658888359
$this->item->CAEFchVto] => 2018-07-13
$this->item->CbteNro] => 8
$this->item->deRazonSocial] => RIVERAS DANIEL ALBERTO
$this->item->deDireccion] => Jose Olaya 2134 Piso:PB Dpto:D4 - Ramos Mejia, Buenos Aires
$this->item->deCondIva] => Responsable Inscripto
$this->item->deDocTipo] => 0
$this->item->deDocNro] => 20202723951
$this->item->deIibbNumero] => 20202723951
$this->item->deInicioActividad] => 0000-00-00
$this->item->CbteTipo] => 1
$this->item->PtoVta] => 2
$this->item->CbteFch] => 2018-07-03
$this->item->Concepto] => 2
$this->item->FchServDesde] => 2018-07-03
$this->item->FchServHasta] => 2018-07-03
$this->item->FchVtoPago] => 2018-07-03
$this->item->aDocTipo] => 80
$this->item->aDocNum] => 30714343919
$this->item->aCondIva] => 0
$this->item->aCondVta] => 1
$this->item->aRazonSocial] => Uzeltinger
$this->item->aDireccion] => Saenz Peña 1820
$this->item->aCodPostal] => 8000
$this->item->aCiudad] => Bahía Blanca
$this->item->aProvincia] => Buenos Aires
$this->item->aPais] => Argentina
$this->item->total] => 121.00
$this->item->subtotal] => 100.00
echo '<pre>';
print_r($this->item	);

die();
*/



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

// set font
$pdf->SetFont('helvetica', '', 9);

// add a page
$pdf->AddPage();

/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

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



// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


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

// Interleaved 2 of 5 + CHECKSUM
$pdf->write1DBarcode($barCode, 'I25', '', '', '', 18, 0.4, $style, 'N');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

if(JRequest::getInt('save')==1){
    jimport('joomla.filesystem.folder');
    $path = JPath::clean(JPATH_SITE . '/pdfs/' . $aDocNum );
    if (!is_dir($path))
        {
        JFolder::create($path);
        }
    $filePdf = $path . '/' . $FacturaNumero . '.pdf';
    $pdf->Output($filePdf, 'F');
}else{
    //Close and output PDF document
    $pdf->Output('example_061.pdf', 'I');
}


die();