<?php
/**
 * @version		$Id: ebilling.php 1 2010-2018 este8an $
 * @package		Joomla.Administrator
 * @subpackage	com_ebilling
 * @copyright	Copyright (C) 2008 - 2018 Fabio Esteban Uzeltinger.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class EbillingController extends JControllerLegacy
{		
	protected $default_view = 'invoices';	
	public function display($cachable = false, $urlparams = false)
	{
		parent::display();		
		return $this;
	}
}