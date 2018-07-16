<?php
/**
* @copyright	Copyright(C) 2008-2010 Fabio Esteban Uzeltinger
* @license 		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @email		admin@com-property.com
**/
defined( '_JEXEC' ) or die( 'Restricted access' );

class EbillingViewProducts extends JViewLegacy
	{
	protected $items;
	protected $pagination;
	protected $state;
	
	
	public function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			EbillingHelper::addSubmenu('products');
		}
		$app = JFactory::getApplication();	
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->filterForm    = $this->get('FilterForm');

		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();			
		}
		
		$canDo	= EbillingHelper::getActions();
		if (!$canDo->get('core.admin')) {
		$msg = JText::_('USER ERROR AUTHENTICATION FAILED').' : '. $this->Profile->name;
			$app->Redirect(JRoute::_('index.php?option=com_ebilling', false));	
		}
		
		parent::display($tpl);
	}
		
		
	
		
	
	protected function addToolbar()
	{
		$state	= $this->get('State');		
		JToolBarHelper::title(JText::_('Productos'), 'products.png');
			JToolBarHelper::custom('product.add', 'new.png', 'new_f2.png','JTOOLBAR_NEW', false);
		
			JToolBarHelper::custom('product.edit', 'edit.png', 'edit_f2.png','JTOOLBAR_EDIT', true);		
		
			JToolBarHelper::divider();
			
			JToolBarHelper::custom('products.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('products.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('', 'products.delete','JTOOLBAR_TRASH');
			//JToolBarHelper::custom('countries.delete','remove.png','remove_f2.png','JTOOLBAR_REMOVE', true);
		
	}
		
	protected function getSortFields()
	{
		return array(
			'ordering'     => JText::_('JGRID_HEADING_ORDERING'),
			'a.published'        => JText::_('JSTATUS'),
			'a.name'        => JText::_('JGLOBAL_TITLE'),
			'a.id'           => JText::_('JGRID_HEADING_ID')
		);
	}
	
}