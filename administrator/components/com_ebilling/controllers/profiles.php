<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class EbillingControllerProfiles extends JControllerAdmin
{
		
	function &getModel($name = 'Profile', $prefix = 'EbillingModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}