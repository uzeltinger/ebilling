<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class EbillingTableInvoiceState extends JTable
{
	function __construct(&$db)
  {
	parent::__construct( '#__ebilling_invoice_state', 'id', $db );
	}    
}
?>