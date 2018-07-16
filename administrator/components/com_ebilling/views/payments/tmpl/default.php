<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

?>

<?php
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

	$saveOrderingUrl = 'index.php?option=com_ebilling&task=payments.saveOrderAjax&tmpl=component';
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


<form action="<?php echo JRoute::_('index.php?option=com_ebilling&view=payments'); ?>" method="post" name="adminForm" id="adminForm">

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
						<th width="60%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'Factura', 'a.id', $listDirn, $listOrder); ?>
						</th>						
						<th width="10%" style="min-width:55px" class="nowrap right">
							<?php echo JHtml::_('searchtools.sort', 'Importe', 'a.valor', $listDirn, $listOrder); ?>
						</th>
                        <th width="5%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'Pago total', 'a.published', $listDirn, $listOrder); ?>
						</th>
                        <th width="5%" class="nowrap hidden-phone">
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
					$link 		= JRoute::_( 'index.php?option=com_ebilling&task=payment.edit&id='.(int) $item->id);	
					$FacturaNumero = EbillingHelper::getFacturaNumero($row->CbteNro,$row->PtoVta);
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
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Editar' );?>::<?php echo $FacturaNumero; ?>">
					<a href="<?php echo $link  ?>"><?php echo $FacturaNumero; ?></a>
				</span>				
			</td>			
			<td align="center">
				<?php echo $row->valor; ?>
			</td>            
			<td align="center">
				<?php echo JHtml::_('payment.setTotal', $item->total, $i, $canChange); ?>
				<?php //echo JHtml::_('jgrid.published', $item->total, $i, 'payments.', 1);?>
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
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
</form>
