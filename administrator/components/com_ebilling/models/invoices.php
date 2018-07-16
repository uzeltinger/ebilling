<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class EbillingModelInvoices extends JModelList
{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'a.CbteNro',
				'a.CbteTipo',
				'a.deDocTipo',
				'a.CAE',
				'a.aRazonSocial',
				'a.state', 
				'profile_id', 
				'CbteTipo'.
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
		
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $search);		
				
		$access = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);

		$published = $app->getUserStateFromRequest($this->context.'.published', 'filter_published', '');
		$this->setState('filter.published', $published);	

		$this->setState('filter.profile_id', $this->getUserStateFromRequest($this->context . '.filter.profile_id', 'profile_id', '', 'cmd'));
		
		$this->setState('filter.CbteTipo', $this->getUserStateFromRequest($this->context . '.filter.CbteTipo', 'CbteTipo', '', 'cmd'));

		$this->setState('filter.invoiceState', $this->getUserStateFromRequest($this->context . '.filter.invoiceState', 'invoiceState', '', 'cmd'));
		// List state information.invoiceState
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
		$query->from('#__ebilling_invoices AS a');
		/*$query->select('max(is.state) as state')
			->join('LEFT', $db->quoteName('#__ebilling_invoice_state', 'is') . ' ON is.invoice_id = a.id');*/
	
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.published = 0 OR a.published = 1)');
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->quoteName('a.id') . ' = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(a.CbteNro LIKE '.$search.' OR a.CAE LIKE '.$search.'  OR a.aRazonSocial LIKE '.$search.')');
			}
		}

		// Filter by client.
		$profile_id = $this->getState('filter.profile_id');
		if (is_numeric($profile_id))
		{
			$query->where($db->quoteName('a.profile_id') . ' = ' . (int) $profile_id);
		}

		$CbteTipo = $this->getState('filter.CbteTipo');
		if (is_numeric($CbteTipo))
		{
			$query->where($db->quoteName('a.CbteTipo') . ' = ' . (int) $CbteTipo);
		}

		$invoiceState = $this->getState('filter.invoiceState');
		if (is_numeric($invoiceState))
		{
			if($invoiceState==99){
				$query->where($db->quoteName('a.recurrent') . ' = 1');
			}else{
				$query->where($db->quoteName('a.state') . ' = ' . (int) $invoiceState);			
			}
			
		}

		//$query->having('a.id = m');
		//$query->group('a.id');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));
		//$query->order($db->escape('is.id DESC'));

		//echo nl2br(str_replace('#__','mdnso_',$query));
		return $query;
	}
}
