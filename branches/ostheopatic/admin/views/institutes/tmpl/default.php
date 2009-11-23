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
			<th style="width: 1%; text-align: center;"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th style="width: 50%; text-align: center;"><?php echo JHTML::_('grid.sort', JText::_('JRESEARCH_INSTITUTE'), 'name', @$this->lists['order_Dir'], @$this->lists['order'] );?></th>
			<th style="width: 1%;" nowrap="nowrap"><?php echo JHTML::_('grid.sort','Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			<th>
				<?php echo JText::_('JRESEARCH_INSTITUTE_URL'); ?>
			</th>
			<th>
				<?php echo JText::_('JRESEARCH_INSTITUTE_EMAIL'); ?>
			</th>
			<th>
				<?php echo JText::_('JRESEARCH_INSTITUTE_PHONE'); ?>
			</th>
			<th>
				<?php echo JText::_('JRESEARCH_INSTITUTE_FAX'); ?>
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
			for($i=0; $i<$n; $i++):
					$k = $i % 2;
					$checked 	= JHTML::_('grid.checkedout', $this->items[$i], $i ); 
					$published  = JHTML::_('grid.published', $this->items[$i], $i );
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->page->getRowOffset( $i ); ?></td>
					<td><?php echo $checked; ?></td>
					<td>
						<a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=institutes&task=edit&cid[]='.$this->items[$i]->id); ?>">
							<?php echo $this->items[$i]->name; ?>
						</a>
					</td>
					<td class="center"><?php echo $published; ?></td>
					<td class="center"><?php echo $this->items[$i]->url; ?></td>
					<td class="center"><?php echo $this->items[$i]->email; ?></td>
					<td class="center"><?php echo $this->items[$i]->phone; ?></td>
					<td class="center"><?php echo $this->items[$i]->fax; ?></td>
				</tr>
			<?php
			endfor;
			
			if($n <= 0):
			?>
			<tr>
				<td colspan="8"></td>
			</tr>
			<?php 
			endif;
			?>
		</tbody>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" /> 
	<input type="hidden" name="hidemainmenu" value="" />
	
	<?php echo JHTML::_('jresearchhtml.hiddenfields', 'institutes'); ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>