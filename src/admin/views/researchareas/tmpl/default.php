<?php
/**
 * @package JResearch
 * @subpackage Researchareas
 * Default view for listing research areas
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

$saveOrder = ($this->lists['order'] == 'ordering');

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
	<table class="adminlist">
		<thead>
		<tr>		
			<th style="width: 1%;">#</th>
			<th style="width: 1%;" class="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th style="width: 58%;" class="title"><?php echo JHTML::_('grid.sort', JText::_('JRESEARCH_RESEARCH_AREA_NAME'), 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			<th style="width: 10%;">
				<?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ORDERING'), 'ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					<?php if ($saveOrder) :
					?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'saveorder'); ?>
					<?php endif; ?>				
			</th>
			<th style="width: 30%;"><?php echo JHTML::_('grid.sort',   'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>

		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="5">
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
					<td class="center"><?php echo $checked; ?></td>
					<td>
					<a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=researchareas&task=edit&cid[]='.$this->items[$i]->id); ?>">
						<?php echo $this->items[$i]->name;  ?>
					</a>						
					<?php if(!empty($this->items[$i]->alias)): ?>
					<p class="smallsub">
						(<span><?php echo JText::_('JRESEARCH_ALIAS') ?></span>: <?php echo $this->items[$i]->alias; ?>)
					</p>
					<?php endif; ?>						
					</td>
					<td class="order" nowrap="nowrap">
						<?php if ($saveOrder) :?>
							<?php if ($this->lists['order_Dir'] == 'ASC') : ?>
								<span><?php echo $this->page->orderUpIcon($i, true, 'orderup', 'JLIB_HTML_MOVE_UP', $saveOrder); ?></span>
								<span><?php echo $this->page->orderDownIcon($i, $this->page->total, true, 'orderdown', 'JLIB_HTML_MOVE_DOWN', $saveOrder); ?></span>
							<?php elseif ($this->lists['order_Dir'] == 'DESC') : ?>
								<span><?php echo $this->page->orderUpIcon($i, true, 'orderdown', 'JLIB_HTML_MOVE_UP', $saveOrder); ?></span>
								<span><?php echo $this->page->orderDownIcon($i, $this->pagination->total, true, 'orderup', 'JLIB_HTML_MOVE_DOWN', $saveOrder); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $this->items[$i]->ordering;?>" <?php echo $disabled ?> class="text-area-order" />					
					</td>
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
	
	<?php echo JHtml::_('jresearchhtml.hiddenfields', 'researchareas'); ?>
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
