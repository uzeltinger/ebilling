<?php
/**
 * @package	com_ebilling
 * @copyright	Copyright (C) 2010 Fabio Esteban Uzeltinger, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class ebillingViewPayment extends JViewLegacy
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
		
		$canDo	= ebillingHelper::getActions();
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

		$this->addToolbar();		
		
		parent::display($tpl);
	}	
	
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
		$canDo	= ebillingHelper::getActions();

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		
		JToolBarHelper::title($isNew ? JText::_('Nuevo') : JText::_('Editar').' : '.$this->item->detalle, 'invoices.png');
		
		JToolBarHelper::apply('payment.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('payment.save', 'JTOOLBAR_SAVE');
			if ($canDo->get('core.admin')) {
			JToolBarHelper::addNew('payment.save2new', 'JTOOLBAR_SAVE_AND_NEW');
			}
			if (empty($this->item->id))  {
			JToolBarHelper::cancel('payment.cancel', 'JTOOLBAR_CANCEL');
		} else {
			JToolBarHelper::cancel('payment.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
