<?php
/**
 * @version		$Id: user.php 17993 2010-07-01 05:15:46Z chdemko $
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Field to select a user id from a modal list.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since		1.6productprofile
 */
class JFormFieldModalInvoicelist extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'ModalInvoicelist';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$link = 'index.php?option=com_ebilling&amp;view=invoices&layout=modal&amp;tmpl=component&amp;field='.$this->id;

		// Initialize some field attributes.
		$attr = $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

		// Initialize JavaScript field attributes.
		$onchange = (string) $this->element['onchange'];

		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal_'.$this->id);

		// Build the script.
		$script = array();
		$script[] = '	function jSelectUser_'.$this->id.'(id, CbteNro) {';
		$script[] = '		var old_id = document.getElementById("'.$this->id.'_id").value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById("'.$this->id.'_id").value = id;';
		$script[] = '			document.getElementById("'.$this->id.'").value = CbteNro;';
		//$script[] = '			'.$onchange;
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '		InvoiceSelected(id);';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

	if ($this->value) {
		$db			= JFactory::getDBO();
		$query = ' SELECT s.CbteNro, s.PtoVta'
				. ' FROM #__ebilling_invoices AS s '
				. ' WHERE s.id = '.$this->value;
		$db->setQuery($query);  
		$invoice = $db->loadObject();
		$FacturaNumero = EbillingHelper::getFacturaNumero($invoice->CbteNro,$invoice->PtoVta);
	}else{
		$FacturaNumero = '';
	}
	//print_r($state_name);require('0');		
	
		// Create a dummy text field with the user name.
		$html[] = '<div class="fltlft">';
		$html[] = ' <input type="text" id="'.$this->id.'" name="'.$this->id.'"' .
					' value="'.htmlspecialchars($FacturaNumero, ENT_COMPAT, 'UTF-8').'"' .
					' disabled="disabled"'.$attr.' />';
		$html[] = '</div>';

		// Create the user select button.
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '		<a class="modal_'.$this->id.'" title="'.JText::_('Factura').'"' .
							' href="'.($this->element['readonly'] ? '' : $link).'"' .
							' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
		$html[] = '			'.JText::_('Factura').'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';

		// Create the real field, hidden, that stored the user id.
		$html[] = '<input type="hidden" id="'.$this->id.'_id" name="'.$this->name.'" value="'.(int) $this->value.'" />';

		return implode("\n", $html);
	}
}