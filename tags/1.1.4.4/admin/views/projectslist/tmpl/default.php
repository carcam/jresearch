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
					<?php echo $this->lists['state'];?>
					<?php echo $this->lists['authors'] ?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="adminlist" cellspacing="1">
		<thead>
		<tr>		
			<th width="1%">#</th>
			<th align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th class="title" width="30%"><?php echo JHTML::_('grid.sort', 'Title', 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'Published', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th align="center"><?php echo JText::_('JRESEARCH_MEMBERS'); ?></th>
			<th><?php echo JHTML::_('grid.sort',   JText::_('JRESEARCH_RESEARCH_AREA'), 'id_research_area', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
		<?php 
			$n = count($this->items);
			for($i=0; $i<$n; $i++){
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
					<td width="1%"><?php echo $checked; ?></td>
					<td width="30%"><a href="index.php?option=com_jresearch&controller=projects&task=edit&cid[]=<?php echo $this->items[$i]->id; ?>"><?php echo $this->items[$i]->title;  ?></a></td>
					<td align="center"><?php echo $published; ?></td>
					<td align="center"><?php echo $text; ?></td>
					<td align="center"><?php echo $researchArea->name ;?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" /> 
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="projects"  />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
