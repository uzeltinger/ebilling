<?php
/**
 * @package	com_ebilling
 * @copyright	Copyright (C) 2010 Fabio Esteban Uzeltinger, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.switcher');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$this->hiddenFieldsets = array();
$this->hiddenFieldsets[0] = 'basic-limited';
$this->configFieldsets = array();
$this->configFieldsets[0] = 'editorConfig';

// Create shortcut to parameters.
$params = $this->state->get('params');

$app = JFactory::getApplication();
$input = $app->input;
$assoc = JLanguageAssociations::isEnabled();

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
$params = json_decode($params);

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'product.cancel' || document.formvalidator.isValid(document.id('item-form')))
		{
			<?php //echo $this->form->getField('articletext')->save(); ?>
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_ebilling'); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data" >
    
    
 <div class="form-horizontal">    
    <div class="row-fluid form-horizontal-desktop">
		<div class="span12">			
                
		<div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('detalle'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('detalle'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('codigo'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('codigo'); ?>
				</div>
			</div>    
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('precio'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('precio'); ?>
				</div>
			</div>  
            
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('published'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('published'); ?>
				</div>
			</div>
            

            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('id'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('id'); ?>
				</div>
			</div>
           
                
					
		</div>		
	</div>
</div>   
    
            
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
	<?php echo JHtml::_('form.token'); ?>


<div class="clr"></div>
</form>


</div>
<div class="clr"></div>
