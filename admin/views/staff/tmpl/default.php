<?php 
/**
 * @package JResearch
 * @subpackage Staff
 * Default view of the staff
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch">
	<table>
		<tbody>
			<tr>
				<td style="text-align:left; width:100%;"><?php echo JText::_('Filter'); ?>
					<input type="text" name="filter_search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" onchange="document.adminForm.submit();" />
					<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
					<button onclick="document.adminForm.filter_search.value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
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
			<th align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th class="title" width="40%"><?php echo JHTML::_('grid.sort',  'Name', 'lastname', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th align="center"><?=JHTML::_('grid.sort', JText::_('JRESEARCH_FORMER_MEMBER'), 'former_member', $this->lists['order_Dir'], $this->lists['order'] );?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Published', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="8%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'Order by', 'ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				<?php echo JHTML::_('grid.order', $this->items ); ?>
			</th>
			<th align="center" width="20%"><?php echo JText::_('JRESEARCH_POSITION'); ?></th>
			<th align="center"><?php echo JHTML::_('grid.sort',   JText::_('JRESEARCH_RESEARCH_AREA'), 'id_research_area', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th><?php echo JText::_('JRESEARCH_CONTACT'); ?></th>
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
			for($i=0; $i<$n; $i++){
					$k = $i % 2;
					$checked 	= JHTML::_('grid.checkedout', $this->items[$i], $i ); 
					$published  = JHTML::_('grid.published', $this->items[$i], $i );
					$researchArea = $this->areaModel->getItem($this->items[$i]->id_research_area);
		?>
			
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->page->getRowOffset( $i ); ?></td>
					<td width="1%"><?php echo $checked; ?></td>
					<td width="30%"><a href="index.php?option=com_jresearch&controller=staff&task=edit&cid[]=<?php echo $this->items[$i]->id; ?>"><?php echo $this->items[$i];  ?></a></td>
					<td align="center"><input type="checkbox" name="former_member" value="1" disabled="disabled" <?=(($this->items[$i]->former_member == 1) ? 'checked="checked"' : "")?> /></td>
					<td align="center"><?php echo $published; ?></td>
					<td class="order" nowrap="nowrap">
						<span><?php echo $this->page->orderUpIcon( $i, $this->items[$i]->ordering > 1, 'orderup', 'Move Up', $this->ordering); ?></span>
						<span><?php echo $this->page->orderDownIcon( $i, $n, $this->items[$i]->ordering < ($this->items[$i]->getNextOrder()-1), 'orderdown', 'Move Down', $this->ordering ); ?></span>
						<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $this->items[$i]->ordering; ?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
					</td>
					<td align="center"><?php echo $this->items[$i]->position; ?></td>
					<td align="center"><?php echo $researchArea->name; ?></td>
					<td align="center"><a href="maito:<?php echo $this->items[$i]->email; ?>"><?php echo $this->items[$i]->email ?></a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" /> 
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="staff"  />
	<input type="hidden" name="hidemainmenu" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>