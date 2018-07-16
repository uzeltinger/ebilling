<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class EbillingTablepayments extends JTable
{    

   function __construct(&$db)
  {
    parent::__construct( '#__ebilling_payments', 'id', $db );
  }
    function check()
	{			
		return true;
	}
}
?>