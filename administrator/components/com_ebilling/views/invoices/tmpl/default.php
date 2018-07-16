<?php
/**
* @copyright	Copyright(C) 2008-2018 Fabio Esteban Uzeltinger
* @license 		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @email		admin@com-ebilling.com
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$saveOrder	= $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_ebilling&task=invoices.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
$assoc		= JLanguageAssociations::isEnabled();

?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_ebilling&view=invoices'); ?>" method="post" name="adminForm" id="adminForm">

	<div id="j-main-container">
		<?php
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
        <table class="table table-striped" id="articleList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<th width="1%" class="hidden-phone">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="20%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'Numero', 'a.CbteNro', $listDirn, $listOrder); ?>
						</th>
						<th width="20%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'Tipo', 'a.CbteTipo', $listDirn, $listOrder); ?>
						</th>
						<th width="20%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'CAE', 'a.CAE', $listDirn, $listOrder); ?>
						</th>
						<th width="30%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'Razón Social', 'a.aRazonSocial', $listDirn, $listOrder); ?>
						</th>
						<th width="30%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'Estado', 'a.state', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'Recur', 'a.recurrent', $listDirn, $listOrder); ?>
						</th>
                        <th width="10%" style="min-width:55px" class="nowrap center">
							PDF
						</th>
                        <th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>                
                </thead>
                <tbody>             
                                
                <?php 
				//print_r($this->items);
				
				foreach($this->items as $i => $item): 

$ordering	= ($listOrder == 'ordering');



$row = &$this->items[$i];
$link 	= JRoute::_( 'index.php?option=com_ebilling&task=invoice.edit&id='.(int) $item->id);	
$linkPdf 	= JRoute::_( 'index.php?option=com_ebilling&view=invoice&format=pdf&id='.(int) $item->id);	

		?>
        <tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
			<td class="order nowrap center hidden-phone">  
            
            <?php
							$canChange  = 1;
							$iconClass = '';
							if (!$canChange)
							{
								$iconClass = ' inactive';
							}
							elseif (!$saveOrder)
							{
								$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
							}
							?>
                            
               	<span class="sortable-handler<?php echo $iconClass ?>">
					<i class="icon-menu"></i>
				</span>
            <?php if ($canChange && $saveOrder) : ?>
				<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
			<?php endif; ?>  
               
			</td>
			<td width="5">
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
			<td>	
			<?php 
			$FacturaNumero = EbillingHelper::getFacturaNumero($row->CbteNro,$row->PtoVta);			
			?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Editar' );?>::<?php echo $row->CbteNro; ?>">
					<a href="<?php echo $link  ?>">
						<?php echo $FacturaNumero; ?>
					</a>
				</span>				
			</td>
			<td align="center">
				<?php 
				$CbteTipoNombreLetra = EbillingHelper::getNombreComprobanteById($row->CbteTipo);
				$CbteTipoNombre = $CbteTipoNombreLetra['nombre'];
				$CbteTipoLetra = $CbteTipoNombreLetra['letra'];
				echo $CbteTipoNombre.' '.$CbteTipoLetra;
				?>
			</td>
			<td align="center">
				<?php echo $row->CAE; ?>
			</td>
			<td align="center">
				<?php echo $row->aRazonSocial; ?>
			</td>
			<td align="center">
				<?php echo JText::_('COM_EBILLINT_INVOICE_STATE_'.$row->state); ?>
			</td>
            <td class="center hidden-phone">
				<?php echo JHtml::_('invoice.recurrented', $item->recurrent, $i, $canChange); ?>
				<?php //echo JHtml::_('jgrid.published', $item->published, $i, 'invoices.', $canChange, 'cb'); ?>

			</td>
			<td align="center">
				<a target="_blank" href="<?php echo $linkPdf  ?>">
						PDF
					</a>
			</td>
            
            <td align="center">
				<?php echo $row->id; ?>
			</td>
        </tr>
<?php endforeach; ?>
                
                </tbody>
        </table>     
        
        <?php echo $this->pagination->getListFooter(); ?>
		<?php //Load the batch processing form. ?>
		<?php //echo $this->loadTemplate('batch'); ?>        
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
</form>
