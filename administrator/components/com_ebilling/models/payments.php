<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class EbillingModelPayments extends JModelList
{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',				
				'ordering', 'a.ordering',
				'published', 'a.published',
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout', 'default')) {
			$this->context .= '.'.$layout;
		}

		$search = $app->getUserStateFromRequest($this->context.'.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);

		$published = $app->getUserStateFromRequest($this->context.'.published', 'filter_published', '');
		$this->setState('filter.published', $published);			

		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.access');
		$id.= ':' . $this->getState('filter.published');
		
		return parent::getStoreId($id);
	}
	
	protected function getListQuery($resolveFKs = true)
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
				
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*')
		);
		$query->from('#__ebilling_payments AS a');		
		$query->select('i.id as facturaId, i.CbteNro, i.PtoVta')
		->join('LEFT', $db->quoteName('#__ebilling_invoices', 'i') . ' ON a.invoice_id = i.id');
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.published = 0 OR a.published = 1)');
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else  {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.CbteNro LIKE '.$search.' OR a.aRazonSocial LIKE '.$search.')');
			}
		}
		

		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
}
