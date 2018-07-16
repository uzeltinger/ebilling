<?php
defined('_JEXEC') or die;

class EbillingModelPayment extends JModelAdmin
{	
	
	public function getTable($type = 'Payments', $prefix = 'EbillingTable', $config = array())
	{
	$t=JTable::getInstance($type, $prefix, $config);
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');
		JForm::addFieldPath('JPATH_ADMINISTRATOR/components/com_ebilling/models/fields');

		$form = $this->loadForm('com_ebilling.payment', 'payment', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}	
	

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_ebilling.edit.payment.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	
	
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();		

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toMySQL();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__ebilling_payments');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
			//$table->modified	= $date->toMySQL();
			//$table->modified_by	= $user->get('id');
		}
	}	
	
	protected function getReorderConditions($table = null)
	{
		//$condition = array();
		//$condition[] = 'catid = '.(int) $table->catid;
		//return $condition;
	}

	public function setTotal(&$pks, $value = 1)
	{        
		$table = $this->getTable();
		$pks   = (array) $pks;
		foreach ($pks as $i => $pk)
		{			
			if ($table->load($pk))
			{
				$table->total = $value;
				$table->checked_out = 0;
				$table->checked_out_time = $this->_db->getNullDate();
				$table->check();
				if (!$table->store())
				{
					$this->setError($table->getError());
				}
				//echo '<pre>';				print_r($table);die();
			}
		}
		return true;
	}
}