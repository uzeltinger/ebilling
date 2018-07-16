var renglones = 1;

function eliminarRenglon(renglonId){
    renglones--;	
	console.log("renglonId",renglonId);
	jQuery('#renglon_'+renglonId).remove();	
    calcularSubtotalDetalle();
}
function calcularSubtotalDetalle(){
    var r = document.getElementsByClassName("detalleIdRenglon");
    var r = jQuery(".detalleIdRenglon");
    var i;
    let invoiceSubtotal=0;
    for (i = 0; i < r.length; i++) {
        r[i].style.backgroundColor = "red";
        let detalleIdRenglon= jQuery(".detalleIdRenglon").eq(i).val();
        let detalleCantidad = jQuery(".detalleCantidad").eq(i).val();
        let detallePrecio = jQuery(".detallePrecio").eq(i).val();
        let detalleDescuentoPorcentaje = jQuery(".detalleDescuentoPorcentaje").eq(i).val();
        let importe = detalleCantidad * detallePrecio;
        let detalleDescuento = (detalleDescuentoPorcentaje*importe)/100;
        console.log("detalleDescuentoPorcentaje",detalleDescuentoPorcentaje);
        console.log("detalleDescuentoPorcentaje*100",detalleDescuentoPorcentaje*100);
        console.log("detalleDescuento",detalleDescuento);
        console.log("importe",importe);
        jQuery(".detalleDescuento").eq(i).val(detalleDescuento);
        //let detalleDescuento = jQuery(".detalleDescuento").eq(i).val();
        let detalleSubtotal = jQuery(".detalleSubtotal").eq(i).val();


        let subTotalRenglon = (importe) - detalleDescuento;
        jQuery(".detalleSubtotal").eq(i).val(subTotalRenglon);        
        invoiceSubtotal = invoiceSubtotal + subTotalRenglon;
    }
    jQuery("#invoiceSubtotal").val(invoiceSubtotal);
    let invoiceImpuestos = invoiceSubtotal * 0.21;
    invoiceImpuestos = Math.round(invoiceImpuestos * 100) / 100;
    let invoiceTotal = invoiceSubtotal + invoiceImpuestos;
    jQuery("#invoiceImpuestos").val(invoiceImpuestos);    
    jQuery("#invoiceTotal").val(invoiceTotal);
}

function productoSeleccionado(e,productSelected){
    if(productSelected>0){
        var parentTdId = jQuery( e ).parent().get( 0 ).id;
        renglonId = parentTdId.replace('producto_elegido_renglon_','');
        let productoSeleccionado = productos[productSelected];
        console.log("productos",productos);
        console.log("productSelected",productSelected);
        console.log("productoSeleccionado",productoSeleccionado);
        jQuery("#detalleCodigo_"+renglonId).val(productoSeleccionado["codigo"]);
        jQuery("#detalleCantidad_"+renglonId).val(1);
        jQuery("#detallePrecio_"+renglonId).val(productoSeleccionado["precio"]);
        jQuery("#detalleCodigo_"+renglonId).val(productoSeleccionado["codigo"]);
    }else{
        jQuery("#detalleCodigo_"+renglonId).val('');
        jQuery("#detalleCantidad_"+renglonId).val('');
        jQuery("#detallePrecio_"+renglonId).val('');    
        jQuery("#detalleCodigo_"+renglonId).val(''); 
    }
    calcularSubtotalDetalle();
} 

jQuery(document).ready(function($)
{       

	jQuery(".eliminarRenglon").click(function(renglonId){
		renglones--;		
        console.log("renglonId",renglonId);	
        calcularSubtotalDetalle();
		jQuery('#renglon_'+renglonId).remove();
	});

	jQuery(".agregarRenglon").click(function(){ 
		renglones++;
		console.log("renglones",renglones);
		jQuery('#detallesRenglones tr:last').after(crearRenglon(renglones));
		calcularSubtotalDetalle();
	});	
	jQuery(document).ready(function(){
        jQuery('#detallesRenglones tr:last').after(crearRenglon(renglones));
        if(InvoiceProducts != null){
            renglonId=1;

            InvoiceProducts.forEach(element => {
                console.log("element",element)
            });

            for ( var i = 0; i < InvoiceProducts.length; i++ ) {
                //codigo, detalle, cantidad, precio, descuento, importe
                let productoSeleccionado = InvoiceProducts[i];
                jQuery("#detalleCodigo_"+renglonId).val(productoSeleccionado["codigo"]); 
                //jQuery("#detalleProducto_"+renglonId).val(productoSeleccionado["product_id"]);                 
                jQuery("#detalleProductoTexto_"+renglonId).val(productoSeleccionado["detalle_texto"]);
                jQuery("#detalleCantidad_"+renglonId).val(productoSeleccionado["cantidad"]);
                jQuery("#detallePrecio_"+renglonId).val(productoSeleccionado["precio"]);                 
                jQuery("#detalleDescuentoPorcentaje_"+renglonId).val(productoSeleccionado["descuentoPorcentaje"]);
                jQuery("#detalleDescuento_"+renglonId).val(productoSeleccionado["descuento"]);
                jQuery("#detalleImporte_"+renglonId).val(productoSeleccionado["importe"]); 
                jQuery("#producto_elegido_renglon_"+renglonId).children().val(productoSeleccionado["product_id"]);
                // jQuery(".detalleIdRenglon").eq(i).val(productoSeleccionado["product_id"]);
                console.log("productoSeleccionado[product_id]",productoSeleccionado["product_id"]);
                console.log("productoSeleccionado[detalleDescuentoPorcentaje]",productoSeleccionado["descuentoPorcentaje"]);
                if(renglonId==InvoiceProducts.length){

                }else{
                    renglonId++;
                    renglones++;
                    jQuery('#detallesRenglones tr:last').after(crearRenglon(renglones));		            
                }                      
            }
            calcularSubtotalDetalle();
             
            console.log("InvoiceProducts",InvoiceProducts);
        }
        if(invoiceWithCAE){
            console.log("invoiceWithCAEinvoiceWithCAEinvoiceWithCAEinvoiceWithCAEinvoiceWithCAE");
            jQuery(".detalleCodigo").prop( "disabled", true );
            jQuery(".detalleProducto").prop( "disabled", true );
            jQuery(".detalleCantidad").prop( "disabled", true );
            jQuery(".detallePrecio").prop( "disabled", true );
            jQuery(".detalleImporte").prop( "disabled", true );
            jQuery(".agregarRenglon").prop( "disabled", true );
            jQuery(".agregarRenglon").hide();
            jQuery(".eliminarRenglon").hide();
            
        }
    });

});