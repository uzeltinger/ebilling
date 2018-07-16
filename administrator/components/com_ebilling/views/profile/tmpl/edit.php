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
		if (task == 'profile.cancel' || document.formvalidator.isValid(document.id('item-form')))
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
					<?php echo $this->getForm()->getLabel('name'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('name'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('RazonSocial'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('RazonSocial'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('CondIva'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('CondIva'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('DocTipo'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('DocTipo'); ?>
				</div>
			</div>    
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('DocNro'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('DocNro'); ?>
				</div>
			</div> 
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('CondVta'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('CondVta'); ?>
				</div>
			</div>           
		
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('Direccion'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('Direccion'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('CodPostal'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('CodPostal'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('Ciudad'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('Ciudad'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('Provincia'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('Provincia'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('Pais'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('Pais'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('email'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('email'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('phone'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('phone'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('whatsapp'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('whatsapp'); ?>
				</div>
			</div>
            <div class="control-group">
				<div class="control-label">
					<?php echo $this->getForm()->getLabel('mobile'); ?>
				</div>
				<div class="controls">
					<?php echo $this->getForm()->getInput('mobile'); ?>
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
