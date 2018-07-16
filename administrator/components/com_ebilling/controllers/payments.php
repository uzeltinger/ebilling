<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
use Joomla\Utilities\ArrayHelper; 

class EbillingControllerPayments extends JControllerAdmin
{
	public function __construct($config = array())
	{
		parent::__construct($config);
		//$this->registerTask('total_unpublish', 'total_publish');
		$this->registerTask('total_unpublish','setTotal');
		$this->registerTask('total_publish','setTotal');
	}
	public function getModel($name = 'Payment', $prefix = 'EbillingModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	public function setTotal(){	
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$ids    = $this->input->get('cid', array(), 'array');
		$values = array('total_publish' => 1, 'total_unpublish' => 0);
		$task   = $this->getTask();
		$value  = ArrayHelper::getValue($values, $task, 0, 'int');
		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('error no seleccionado'));
		}
		else
		{
			$model = $this->getModel();
			if (!$model->setTotal($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$ntext = 'pagos totales Generados';
				}
				else
				{
					$ntext = 'pagos parciales Generados';
				}
				$this->setMessage(JText::plural($ntext, count($ids)));
			}
		}	

		$this->setRedirect('index.php?option=com_ebilling&view=payments');

	}
}