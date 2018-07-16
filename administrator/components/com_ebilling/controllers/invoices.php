<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
use Joomla\Utilities\ArrayHelper; 
class EbillingControllerInvoices extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('recurrent_unpublish', 'recurrent_publish');
	}

	public function getModel($name = 'Invoice', $prefix = 'EbillingModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	public function recurrent_publish()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$ids    = $this->input->get('cid', array(), 'array');
		$values = array('recurrent_publish' => 1, 'recurrent_unpublish' => 0);
		$task   = $this->getTask();
		$value  = ArrayHelper::getValue($values, $task, 0, 'int');
		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('error no seleccionado'));
		}
		else
		{
			$model = $this->getModel();
			if (!$model->setRecurrent($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$ntext = 'Recurrente';
				}
				else
				{
					$ntext = 'No Recurrente';
				}
				$this->setMessage(JText::plural($ntext, count($ids)));
			}
		}
		$this->setRedirect('index.php?option=com_ebilling&view=invoices');
	}
	public function recurrir(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$cids    = $this->input->get('cid', array(), 'array');
		$model = $this->getModel('invoice');
		$generados = 0;
		if (count( $cids )) {
			foreach($cids as $cid) {
				$invoiceId = $model->duplicate($cid);
				if($invoiceId>0){
					if($model->obtenerCae($invoiceId)){
						$generados++;
					}
				}
			}
		}
		//print_r($ids);die();
		$this->setMessage(JText::_('Generados '.$generados.' recurrentes'));
		$this->setRedirect('index.php?option=com_ebilling&view=invoices');

	}
}
