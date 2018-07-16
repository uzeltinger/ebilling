<?php
defined('_JEXEC') or die;

class EbillingModelInvoiceState extends JModelAdmin
{	
	
	public function getTable($type = 'InvoiceState', $prefix = 'EbillingTable', $config = array())
	{
	    $t=JTable::getInstance($type, $prefix, $config);
		return JTable::getInstance($type, $prefix, $config);
	}

	public function store($newData){
		$row = $this->getTable();
      // Bind the form fields to the hello table
      if (!$row->bind($newData)) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }
      // Make sure the hello record is valid
      if (!$row->check()) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }
      // Store the web link table to the database
      if (!$row->store()) {
         $this->setError( $row->getErrorMsg() );
         return false;
      }
      return true;
	}	
}