<?php
defined('_JEXEC') or die;

class EbillingModelProduct extends JModelAdmin
{	
	
	public function getTable($type = 'Products', $prefix = 'EbillingTable', $config = array())
	{
	$t=JTable::getInstance($type, $prefix, $config);
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');
		JForm::addFieldPath('JPATH_ADMINISTRATOR/components/com_ebilling/models/fields');

		$form = $this->loadForm('com_ebilling.product', 'product', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}	
	

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_ebilling.edit.product.data', array());
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
				$db->setQuery('SELECT MAX(ordering) FROM #__ebilling_products');
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
}