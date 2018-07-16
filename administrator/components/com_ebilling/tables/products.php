<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class EbillingTableproducts extends JTable
{    

   function __construct(&$db)
  {
    parent::__construct( '#__ebilling_products', 'id', $db );
  }
    function check()
	{			
		return true;
	}
}
?>