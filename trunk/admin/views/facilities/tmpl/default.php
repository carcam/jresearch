<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch">
	<table>
		<tbody>
			<tr>
				<td style="text-align:left; width:100%;"><?=JText::_('Filter'); ?>
					<input type="text" name="filter_search" id="search" value="<?=$this->lists['search'] ?>" class="text_area" onchange="document.adminForm.submit();" />
					<button onclick="this.form.submit();"><?=JText::_('Go'); ?></button>
					<button onclick="document.adminForm.filter_search.value='';this.form.submit();"><?=JText::_('Reset'); ?></button>
				</td>
				<td nowrap="nowrap">
					<?=$this->lists['state'];?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="adminlist" cellspacing="1">
		<thead>
		<tr>		
			<th width="1%">#</th>
			<th width="20" align="center">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?=count($this->items);?>);" />
			</th>
			<th align="center">
				<?=JText::_('JRESEARCH_FACILITY');?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?=JHTML::_('grid.sort','Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="8%" nowrap="nowrap">
				<?=JHTML::_('grid.sort', 'Order by', 'ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				<?=JHTML::_('grid.order', $this->items ); ?>
			</th>
			<th>
				<?=JHTML::_('grid.sort',   JText::_('JRESEARCH_RESEARCH_AREA'), 'id_research_area', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
		</thead>		
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
			<?php 
			$n = count($this->items);
			for($i=0; $i<$n; $i++)
			{
				$k = $i % 2;
				$checked 	= JHTML::_('grid.checkedout', $this->items[$i], $i ); 
				$published  = JHTML::_('grid.published', $this->items[$i], $i );
				$researchArea = $this->area->getItem((int) $this->items[$i]->id_research_area);
			?>
				<tr class="<?="row$k"; ?>">
					<td>
						<?=$this->page->getRowOffset( $i ); ?>
					</td>
					<td width="1%">
						<?=$checked; ?>
					</td>
					<td width="30%">
						<a href="index.php?option=com_jresearch&controller=facilities&task=edit&cid[]=<?=$this->items[$i]->id; ?>">
							<?=$this->items[$i]->name; ?>
						</a>
					</td>
					<td align="center">
						<?=$published; ?>
					</td>
					<td class="order" nowrap="nowrap">
						<span>
							<?=$this->page->orderUpIcon( $i, $this->items[$i]->ordering > 1, 'orderup', 'Move Up', $this->ordering); ?>
						</span>
						<span>
							<?=$this->page->orderDownIcon( $i, $n, $this->items[$i]->ordering < ($this->items[$i]->getNextOrder()-1), 'orderdown', 'Move Down', $this->ordering ); ?>
						</span>
						<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?=$this->items[$i]->ordering; ?>" <?=$disabled ?> class="text_area" style="text-align: center" />
					</td>
					<td align="center">
						<?=$researchArea->name;?>
					</td>
				</tr>
			<?php
			} //endfor
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />  
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="facilities"  />
	<input type="hidden" name="hidemainmenu" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>