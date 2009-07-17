<?php
/**
 * @package JResearch
 * @subpackage Financiers
 * Default view for listing research areas
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
			<th style="width: 1%;">#</th>
			<th style="width: 1%; text-align:center;"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort',  'Name', 'name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th style="width: 1%;" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Published', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
			<?php 
			$n = count($this->items);
			for($i=0; $i<$n; $i++):
					$k = $i % 2;
					$checked 	= JHTML::_('grid.checkedout', $this->items[$i], $i ); 
					$published  = JHTML::_('grid.published', $this->items[$i], $i );
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="center"><?php echo $this->page->getRowOffset( $i ); ?></td>
					<td class="center"><?php echo $this->items[$i]->id >= 1?$checked:''; ?></td>
					<td><a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=financiers&task=edit&cid[]='.$this->items[$i]->id); ?>"><?php echo $this->items[$i]->name;  ?></a></td>
					<td class="center"><?php echo $published; ?></td>
				</tr>
			<?php
			endfor;
			
			if($n <= 0):
			?>
			<tr>
				<td colspan="4"></td>
			</tr>
			<?php 
			endif;
			?>
		</tbody>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" /> 

	<?php echo JHTML::_('jresearchhtml.hiddenfields', 'financiers'); ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
