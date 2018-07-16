<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class EbillingControllerProfile extends JControllerForm
{
	public function __construct($config = array())
	{
		parent::__construct($config);
		$data	= JRequest::getVar('jform', array(), 'post', 'array');
		if($data['id'])
		{
		//$this->SaveImages();
		}		
	}	
}