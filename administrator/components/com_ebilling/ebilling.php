<?php
/**
 * @version		$Id: ebilling.php 1 2010-2018 este8an $
 * @package		Joomla.Administrator
 * @subpackage	com_ebilling
 * @copyright	Copyright (C) 2008 - 2018 Fabio Esteban Uzeltinger.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_ebilling')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
$doc = JFactory::getDocument();
$doc->addStyleSheet('components/com_ebilling/assets/css/ebilling.css');
JLoader::register('EbillingHelper', __DIR__ . '/helpers/helper.php');
$controller = JControllerLegacy::getInstance('Ebilling');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();