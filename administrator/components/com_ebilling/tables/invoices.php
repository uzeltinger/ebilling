<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class EbillingTableinvoices extends JTable
{
		function __construct(&$db)
		{
			parent::__construct( '#__ebilling_invoices', 'id', $db );
		}
}
?>