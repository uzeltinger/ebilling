<?php
/**
 * @package	com_ebilling
 * @copyright	Copyright (C) 2010 Fabio Esteban Uzeltinger, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class EbillingViewInvoice extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{

		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->Profile = $this->get('Profile');	
		$this->InvoiceProducts = $this->get('InvoiceProducts');
		
		$canDo	= EbillingHelper::getActions();
		if ($canDo->get('core.admin')) 
		{
			$this->iAmAdmin=true;
			}else{
			$this->iAmAdmin=false;		
		}		

		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

        $document = JFactory::getDocument();
        //$document->setName('mypdf');	
		
		parent::display('pdf');
	}	

	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
		$canDo	= EbillingHelper::getActions();

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		
		if($this->item->CAE){
			JToolBarHelper::title('Este comprobante ya tiene CAE, no puede editarse Nº: ' .$this->item->CbteNro. '  CAE: ' .$this->item->CAE , 'invoice.png');
			JToolBarHelper::cancel('invoice.cancel', 'JTOOLBAR_CLOSE');
		}else{
			JToolBarHelper::title($isNew ? JText::_('Nueva') : JText::_('Editar').' : '.$this->item->CbteNro, 'invoice.png');
		
		
		
			JToolBarHelper::apply('invoice.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('invoice.save', 'JTOOLBAR_SAVE');
				if ($canDo->get('core.admin')) {
				JToolBarHelper::addNew('invoice.save2new', 'JTOOLBAR_SAVE_AND_NEW');
				}
				if (empty($this->item->id))  {
					JToolBarHelper::cancel('invoice.cancel', 'JTOOLBAR_CANCEL');
				} else {
					JToolBarHelper::cancel('invoice.cancel', 'JTOOLBAR_CLOSE');
				}

			JToolBarHelper::divider();
				
			JToolBarHelper::custom('invoice.obtenerCae', 'publish.png', 'publish_f2.png','Obtener CAE', false);


		}
	}
}
