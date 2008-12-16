<?php
/**
 * @package JResearch
 * @subpackage Cooperations
 * Default view for listing cooperations
 */
// no direct access
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
					<?php echo $this->lists['state'];?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="adminlist" cellspacing="1">
		<thead>
		<tr>		
			<th width="1%">#</th>
			<th align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?=count($this->items); ?>);" /></th>
			<th align="center"><?=JHTML::_('grid.sort', JText::_('JRESEARCH_TEAM'), 'name', @$this->lists['order_Dir'], @$this->lists['order'] );?></th>
			<th width="1%" nowrap="nowrap"><?=JHTML::_('grid.sort','Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			<th>
				<?=JText::_('JRESEARCH_TEAM_LEADER'); ?>
			</th>
		</tr>
		</thead>		
		<tfoot>
			<tr>
				<td colspan="9">
					<?=$this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
		<?php 
			$n = count($this->items);
			for($i=0; $i<$n; $i++){
					$k = $i % 2;
					$checked 	= JHTML::_('grid.checkedout', $this->items[$i], $i ); 
					$published  = JHTML::_('grid.published', $this->items[$i], $i );
					$leader = $this->member->getItem($this->items[$i]->id_leader);
			?>
				<tr class="<?="row$k"; ?>">
					<td><?=$this->page->getRowOffset( $i ); ?></td>
					<td width="1%"><?=$checked; ?></td>
					<td width="30%">
						<a href="index.php?option=com_jresearch&controller=teams&task=edit&cid[]=<?=$this->items[$i]->id; ?>">
							<?=$this->items[$i]->name; ?>
						</a>
					</td>
					<td align="center"><?=$published; ?></td>
					<td align="center"><?=$leader->username?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" /> 
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="teams"  />
	<input type="hidden" name="hidemainmenu" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>