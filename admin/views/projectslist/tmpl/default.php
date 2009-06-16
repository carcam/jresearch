<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for listing projects.
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
					<?php echo $this->lists['state']?>
					<?php echo $this->lists['authors']?>
					<?php echo $this->lists['area']?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="adminlist" cellspacing="1">
		<thead>
		<tr>		
			<th style="width: 1%;">#</th>
			<th style="width: 1%; text-align: center;"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th style="width: 30%;" class="title"><?php echo JHTML::_('grid.sort', 'Title', 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th style="width: 1%;" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'Published', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th style="text-align: center; width: 32%;"><?php echo JText::_('JRESEARCH_MEMBERS'); ?></th>
			<th style="text-align: center; width: 32%;"><?php echo JHTML::_('grid.sort',   JText::_('JRESEARCH_RESEARCH_AREA'), 'id_research_area', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th style="width: 5%;"><?php echo JText::_('Hits'); ?></th>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
		<?php 
			$n = count($this->items);
			for($i=0; $i<$n; $i++):
					$text = '';
					$k = $i % 2;
					$checked 	= JHTML::_('grid.checkedout', $this->items[$i], $i ); 
					$published  = JHTML::_('grid.published', $this->items[$i], $i );
					$members = $this->items[$i]->getAuthors();
					$researchArea = $this->area->getItem((int)$this->items[$i]->id_research_area);
		          	foreach($members as $member){ 
		             	if($member instanceof JResearchMember)
		             	 	$text .= ' '.$member->__toString().',';
		             	else
		             	 	$text .= ' '.$member.',';
		          	}
		          	$text = rtrim($text, ',');					
					
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->page->getRowOffset( $i ); ?></td>
					<td><?php echo $checked; ?></td>
					<td><a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=projects&task=edit&cid[]='.$this->items[$i]->id); ?>"><?php echo $this->items[$i]->title;  ?></a></td>
					<td class="center"><?php echo $published; ?></td>
					<td class="center"><?php echo $text; ?></td>
					<td class="center"><?php echo $researchArea->name ;?></td>
					<td class="center"><?php echo $this->items[$i]->hits ;?></td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" /> 
	
	<?php echo JHTML::_('jresearchhtml.hiddenfields', 'projects'); ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
