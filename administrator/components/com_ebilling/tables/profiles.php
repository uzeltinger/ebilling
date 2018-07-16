<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class EbillingTableprofiles extends JTable
{    

   function __construct(&$db)
  {
    parent::__construct( '#__ebilling_profiles', 'id', $db );
  }
    function check()
	{
		// check for http on webpage		
		if(empty($this->alias)) {
			$this->alias = $this->name;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		if(trim(str_replace('-','',$this->alias)) == '') {
			$datenow =& JFactory::getDate();
			$this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		}		
		return true;
	}		
}
?>