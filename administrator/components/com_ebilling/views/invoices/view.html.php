<?php
/**
* @copyright	Copyright(C) 2008-2018 Fabio Esteban Uzeltinger
* @license 		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @email		admin@com-Ebilling.com
**/
defined( '_JEXEC' ) or die( 'Restricted access' );

class EbillingViewInvoices extends JViewLegacy
	{
	protected $items;
	protected $pagination;
	protected $state;
	
	
	public function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			EbillingHelper::addSubmenu('invoices');
		}
		
		$canDo	= EbillingHelper::getActions();		
		if (!$canDo->get('core.admin')) {
		$app =& JFactory::getApplication();
		$msg = JText::_('USER ERROR AUTHENTICATION FAILED').' : '. $this->Profile->name;
		$app->Redirect(JRoute::_('index.php?option=com_ebilling', $msg));	
		}
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->filterForm    = $this->get('FilterForm');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();			
		}
		//require_once JPATH_COMPONENT .'/models/fields/bannerclient.php';
		parent::display($tpl);
	}
		
		
	
		
	
	protected function addToolbar()
	{
		$state	= $this->get('State');		
		JToolBarHelper::title(JText::_('Facturas'), 'invoices.png');
			JToolBarHelper::custom('invoice.add', 'new.png', 'new_f2.png','JTOOLBAR_NEW', false);
		
			JToolBarHelper::custom('invoice.edit', 'edit.png', 'edit_f2.png','JTOOLBAR_EDIT', true);		
		
			JToolBarHelper::divider();
			
			JToolBarHelper::custom('invoices.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('invoices.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			
			JToolbarHelper::custom( 'invoice.duplicate', 'copy', 'publish_f2.png', 'Duplicar' );
			JToolbarHelper::custom( 'invoice.sendEmail', 'mail', 'publish_f2.png', 'Enviar' );
			JToolBarHelper::custom('invoice.obtenerCae', 'publish.png', 'publish_f2.png','Obtener CAE', false);
		
			$invoiceState = $this->state->get('filter.invoiceState');
			//echo '$invoiceState: '.$invoiceState;
			if (is_numeric($invoiceState))
			{
				if($invoiceState==99){
					JToolbarHelper::custom( 'invoices.recurrir', 'folder-plus', 'publish_f2.png', 'recurrir' );
				}
			}

			JToolBarHelper::divider();
			JToolBarHelper::deleteList('', 'invoices.delete','JTOOLBAR_TRASH');
			
		
	}
	protected function getSortFields()
	{
		return array(
			'a.ordering'     => JText::_('JGRID_HEADING_ORDERING'),
			'a.published'        => JText::_('JSTATUS'),
			'a.name'        => JText::_('JGLOBAL_TITLE'),
			'a.id'           => JText::_('JGRID_HEADING_ID')
		);
	}		
	
}