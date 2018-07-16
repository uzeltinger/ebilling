<?php
/**
 * @package	com_ebilling
 * @copyright	Copyright (C) 2010 Fabio Esteban Uzeltinger, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.switcher');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$this->hiddenFieldsets = array();
$this->hiddenFieldsets[0] = 'basic-limited';
$this->configFieldsets = array();
$this->configFieldsets[0] = 'editorConfig';

// Create shortcut to parameters.
$params = $this->state->get('params');

$app = JFactory::getApplication();
$input = $app->input;
$assoc = JLanguageAssociations::isEnabled();

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
$params = json_decode($params);
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'invoice.cancel' || document.formvalidator.isValid(document.id('item-form')))
		{
			<?php //echo $this->form->getField('articletext')->save(); ?>
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
	function ProfileSelected(){
		//alert('ProfileSelected');
	}
</script>


<script type="text/javascript">
function crearRenglon(renglones_){
	var renglonHtml = '';
	renglonHtml = renglonHtml + '<tr id="renglon_'+renglones_+'">';
	renglonHtml = renglonHtml + '	<td>';
	renglonHtml = renglonHtml + '		<input type="hidden" class="detalleIdRenglon" name="detalleIdRenglon" value="'+renglones_+'">';
	renglonHtml = renglonHtml + '		<input type="text" name="detalleCodigo[]" id="detalleCodigo_'+renglones_+'" maxlength="4" class="form-control detalleCodigo">';
	renglonHtml = renglonHtml + '	</td>';
	renglonHtml = renglonHtml + '	<td width="25%" id="producto_elegido_renglon_'+renglones_+'">';
	renglonHtml = renglonHtml + '		<?php echo str_replace("\n","",$this->productsListSelect);?>';
	renglonHtml = renglonHtml + '	</td>';
	renglonHtml = renglonHtml + '	<td width="25%" id="producto_texto_renglon_'+renglones_+'">';
	renglonHtml = renglonHtml + '		<textarea name="detalleProductoTexto[]" id="detalleProductoTexto_'+renglones_+'" class="form-control detalleProductoTexto" rows="1"></textarea>';
	renglonHtml = renglonHtml + '	</td>';
	renglonHtml = renglonHtml + '	<td>';
	renglonHtml = renglonHtml + '		<input type="text" name="detalleCantidad[]" id="detalleCantidad_'+renglones_+'" class="form-control detalleCantidad" onkeyup="calcularSubtotalDetalle();" onchange="calcularSubtotalDetalle();" value="1">';
	renglonHtml = renglonHtml + '	</td>';
	renglonHtml = renglonHtml + '	<td>';
	renglonHtml = renglonHtml + '		<input type="text" name="detallePrecio[]"  id="detallePrecio_'+renglones_+'" class="form-control detallePrecio" onkeyup="calcularSubtotalDetalle();" onchange="calcularSubtotalDetalle();" value="">';
	renglonHtml = renglonHtml + '	</td>';
	renglonHtml = renglonHtml + '	<td>';
	renglonHtml = renglonHtml + '		<input type="text" name="detalleDescuentoPorcentaje[]"  id="detalleDescuentoPorcentaje_'+renglones_+'" class="form-control detalleDescuentoPorcentaje" onkeyup="calcularSubtotalDetalle();" onchange="calcularSubtotalDetalle();" value="">';
	renglonHtml = renglonHtml + '	</td>';
	renglonHtml = renglonHtml + '	<td>';
	renglonHtml = renglonHtml + '		<input type="text" name="detalleDescuento[]"  id="detalleDescuento_'+renglones_+'" class="form-control detalleDescuento" onkeyup="calcularSubtotalDetalle();" onchange="calcularSubtotalDetalle();" value="">';
	renglonHtml = renglonHtml + '	</td>';
	renglonHtml = renglonHtml + '	<td>';
	renglonHtml = renglonHtml + '		<input type="hidden" class="detalleDescuento" name="detalleDescuento[]" id="detalleDescuento_'+renglones_+'" value="0">';
	renglonHtml = renglonHtml + '		<input type="text" name="detalleSubtotal[]" id="detalleSubtotal_'+renglones_+'" class="form-control detalleSubtotal" readonly="readonly">';
	renglonHtml = renglonHtml + '	</td>';
	renglonHtml = renglonHtml + '	<td class="tdBotonEliminar">';
	renglonHtml = renglonHtml + '<input type="button" name="Eliminar" value="x" class="eliminarRenglon btn btn-xs btn-danger" data-renglon-id="'+renglones_+'" onclick="eliminarRenglon('+renglones_+')">';
	renglonHtml = renglonHtml + '	</td>';
	renglonHtml = renglonHtml + '</tr>';
	return renglonHtml;
	}
	var productos = [];
	
	<?php
	foreach ($this->Products as $key => $value) {
		$array[$key]['codigo'] = $value->codigo;
		$array[$key]['detalle'] = $value->detalle;
		$array[$key]['precio'] = $value->precio;
		echo 'productos['.$value->id.']=[];';
		echo "\n\t";
		echo 'productos['.$value->id.']["codigo"] = "'.$value->codigo.'";';
		echo 'productos['.$value->id.']["detalle"] = "'.$value->detalle.'";';
		echo 'productos['.$value->id.']["precio"] = "'.$value->precio.'";';
		echo "\n\t";
	}
	if($this->InvoiceProducts){			
		echo 'var InvoiceProducts = [];';echo "\n\t";
		foreach ($this->InvoiceProducts as $key => $value) {
			/*$array[$value->id]['codigo'] = $value->codigo;
			$array[$value->id]['detalle'] = $value->detalle;
			$array[$value->id]['precio'] = $value->precio;*/
			echo 'InvoiceProducts['.$key.']=[];';
			echo "\n\t";
			echo 'InvoiceProducts['.$key.']["codigo"] = "'.$value->codigo.'";';
			echo 'InvoiceProducts['.$key.']["detalle"] = "'.$value->detalle.'";';
			echo 'InvoiceProducts['.$key.']["detalle_texto"] = "'.$value->detalle_texto.'";';
			echo 'InvoiceProducts['.$key.']["cantidad"] = "'.$value->cantidad.'";';
			echo 'InvoiceProducts['.$key.']["precio"] = "'.$value->precio.'";';
			echo 'InvoiceProducts['.$key.']["descuentoPorcentaje"] = "'.$value->descuentoPorcentaje.'";';
			echo 'InvoiceProducts['.$key.']["descuento"] = "'.$value->descuento.'";';
			echo 'InvoiceProducts['.$key.']["importe"] = "'.$value->importe.'";';
			echo 'InvoiceProducts['.$key.']["product_id"] = "'.$value->product_id.'";';			
			echo "\n\t";
		}
	}else{
		echo 'var InvoiceProducts = null;';echo "\n\t";
	}
	if($this->item->CAE){
		echo 'var invoiceWithCAE = true;';echo "\n\t";
	}else{
		echo 'var invoiceWithCAE = false;';echo "\n\t";
	}
	?>
</script>

<div class="ebilling-form">
	<form action="<?php JRoute::_('index.php?option=com_ebilling'); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data" >
    
    
	<div class="form-horizontal mitad-left span9">    
	   <div class="row-fluid form-horizontal-desktop">				   
			   <div class="control-group span12">
				   <div class="control-label">
					   <?php echo $this->getForm()->getLabel('profile_id'); ?>
				   </div>
				   <div class="controls">
					   <?php echo $this->getForm()->getInput('profile_id'); ?>
				   </div>
			   </div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			   <div class="control-group span12">
				   <div class="control-label">
					   <?php echo $this->getForm()->getLabel('CbteNro'); ?>
				   </div>
				   <div class="controls">
					   <?php echo $this->getForm()->getInput('CbteNro'); ?>
				   </div>
			   </div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			   <div class="control-group span12">
				   <div class="control-label">
					   <?php echo $this->getForm()->getLabel('PtoVta'); ?>
				   </div>
				   <div class="controls">
					   <?php echo $this->getForm()->getInput('PtoVta'); ?>
				   </div>
			   </div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			   <div class="control-group span12">
				   <div class="control-label">
					   <?php echo $this->getForm()->getLabel('CbteTipo'); ?>
				   </div>
				   <div class="controls">
					   <?php echo $this->getForm()->getInput('CbteTipo'); ?>
				   </div>
			   </div>			
		</div>
		
		<div class="row-fluid form-horizontal-desktop">
			   <div class="control-group span12">
				   <div class="control-label">
					   <?php echo $this->getForm()->getLabel('CbteFch'); ?>
				   </div>
				   <div class="controls">
					   <?php echo $this->getForm()->getInput('CbteFch'); ?>
				   </div>
			   </div>
			   			
		</div>
		<div class="row-fluid form-horizontal-desktop">

				<div class="control-group span12">
				   <div class="control-label">
					   <?php echo $this->getForm()->getLabel('Concepto'); ?>
				   </div>
				   <div class="controls">
					   <?php echo $this->getForm()->getInput('Concepto'); ?>
				   </div>
			   	</div>
		</div>
		<div class="row-fluid form-horizontal-desktop">	
			   <div class="control-group span12">
				   <div class="control-label">
					   <?php echo $this->getForm()->getLabel('FchServDesde'); ?>
				   </div>
				   <div class="controls">
					   <?php echo $this->getForm()->getInput('FchServDesde'); ?>
				   </div>
			   </div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			   <div class="control-group span12">
				   <div class="control-label">
					   <?php echo $this->getForm()->getLabel('FchServHasta'); ?>
				   </div>
				   <div class="controls">
					   <?php echo $this->getForm()->getInput('FchServHasta'); ?>
				   </div>
			   </div>	
		</div>
		<div class="row-fluid form-horizontal-desktop">		
			   <div class="control-group span12">
				   <div class="control-label">
					   <?php echo $this->getForm()->getLabel('FchVtoPago'); ?>
				   </div>
				   <div class="controls">
					   <?php echo $this->getForm()->getInput('FchVtoPago'); ?>
				   </div>
			   </div>		
		</div>
		
	</div>
	<div class="form-horizontal mitad-right span3">
		<div class="row-fluid form-vertical">		
			<div class="control-group span12">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('recurrent'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('recurrent'); ?>
				</div>
			</div>		
		</div>
	</div>


<div style="clear; both;"> </div>


	<div class="form-vertical">    
	<div class="span12">Detalle de Factura</div>	

		<div class="row-fluid" id="detalle-factura-renglon">

<table id="detallesRenglones" cellpadding="5" cellspacing="5">
	<tr>
		<th width="10%">Código</th>
		<th colspan="2" width="40%">Producto/Servicio</th>		
		<th width="10%">Cant.</th>
		<th width="10%">Prec. Unitario</th>
		<th width="5%">% Dto.</th>
		<th width="10%">Descuento.</th>
		<th width="10%">Subtotal</th>
		<th width="5%">Eliminar</th>
	</tr>
	<!--
	<tr>
		<td>
			<input type="text" name="detalleCodigoArticulo[]" maxlength="4" class="form-control">
		</td>
		<td>
			<?php echo $this->productsListSelect;?>
		</td>
		<td>
			<input type="text" name="detalleCantidad[]" id="detalle_cantidad_1" class="form-control" onkeyup="calcularSubtotalDetalle();" onchange="calcularSubtotalDetalle();" value="1">
		</td>
		<td>
			<input type="text" name="detallePrecio[]"  id="detallePrecio_1" class="form-control" onkeyup="calcularSubtotalDetalle();" onchange="calcularSubtotalDetalle();" value="">
		</td>
		<td>
			<input type="text" name="detalleSubtotal[]" id="detalleSubtotal_1" class="form-control" readonly="readonly">
		</td>
	</tr>
	-->
</table>

		</div>

		<input type="button" name="Agregar" value="+" class="agregarRenglon btn btn-xs btn-success">
		
		

<!--
	   <div class="row-fluid" id="detalle-factura-renglon">	   				   
			<div class="span1">
				<div class="control-label">Código</div>
				<div class="controls">
					<input type="text" name="detalleCodigoArticulo" maxlength="4" class="form-control">
					<input type="hidden" name="detalleNroLinea">
				</div>
			</div>
			<div class="span4">
				<div class="control-label">Producto/Servicio</div>
				<div class="controls">
					<?php echo $this->productsListSelect;?>
				</div>
			</div>
			<div class="span1">
				<div class="control-label">Cant.</div>
				<div class="controls">
					<input type="text" name="detalleCantidad" maxlength="19" id="detalle_cantidad1" class="form-control" onkeyup="calcularSubtotalDetalle(1);" onchange="calcularSubtotalDetalle(1);" value="1">
				</div>
			</div>
			<div class="span2">
				<div class="control-label">Prec. Unitario</div>
				<div class="controls">
					<input type="text" name="detallePrecio" maxlength="19" id="detallePrecio" class="form-control" onkeyup="calcularSubtotalDetalle(1);" onchange="calcularSubtotalDetalle(1);" value="0">
				</div>
			</div>
			<div class="span2">
				<div class="control-label">Subtotal</div>
				<div class="controls">
					<input type="text" name="detalleSubtotal2" maxlength="19" id="detalleSubtotal2" class="form-control" readonly="readonly">
				</div>
			</div>
			<div class="span1">
				<div class="control-label">Eliminar</div>
				<div class="controls">
					<input type="button" name="Eliminar" value="X" class="eliminarRenglon btn btn-xs btn-danger" style="width:31px;text-align:center;color:#FF0000;font-size:10px;" onclick="eliminarRenglon(this);" data-renglon-id="1">
				</div>
			</div>
			<div class="span12">
				<input type="button" name="Agregar" value="+" class="btn btn-xs btn-success" style="width:31px;text-align:center;color:green;font-size:10px;" onclick="agregarRenglon();">
			</div>	
		</div>			   
	</div>
-->
<div class=""><p><br></p></div>
	<div class="row-fluid">
		<div class="span7"></div>
			<div class="span2">Subtotal:</div>
			<div class="span3">
				<input type="text" name="invoiceSubtotal" id="invoiceSubtotal" class="form-control" readonly="readonly" style="text-align:right;">
		</div>
	</div>		
	<div class="form-vertical">
		<div class="row-fluid">
			<div class="span7"></div>
			<div class="span2">Impuestos:</div>
			<div class="span3">
				<input type="text" name="invoiceImpuestos" id="invoiceImpuestos" class="form-control" readonly="readonly" style="text-align:right;">
			</div>
		</div>
	</div>
	<div class="form-vertical">
		<div class="row-fluid">
			<div class="span7"></div>
			<div class="span2">Importe Total:</div>
			<div class="span3">
				<input type="text" name="invoiceTotal" id="invoiceTotal" class="form-control" readonly="readonly" style="text-align:right;">
			</div>
		</div>
	</div>

	


	<input type="hidden" name="invoiceId" value="<?php echo $this->getForm()->getValue('id'); ?>"/>
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
</div>


<?php
$doc = JFactory::getDocument();
$doc->addScript('components/com_ebilling/assets/js/ebilling.js');
?>