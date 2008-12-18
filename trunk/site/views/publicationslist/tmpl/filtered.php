<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for listing publications per group
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch">
	<div style="text-align:left">
		<?php echo $this->lists['teams'] ?>
	</div>
	<table class="adminlist" cellspacing="1">
		<thead>
		<tr>		
			<th width="1%">#</th>
			<th class="title" width="30%"><?php echo JText::_('JRESEARCH_TITLE'); ?></th>
			<th class="title" width="30%"><?php echo JText::_('JRESEARCH_AUTHORS'); ?></th>
			<th class="title" width="19%"><?php echo JText::_('JRESEARCH_YEAR'); ?></th>
			<th width="20%" nowrap="nowrap"><?php echo JText::_('JOURNAL_ACCEPTANCE_RATE'); ?></th>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="3">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
		<?php 
			$n = count($this->items);
			for($i=0; $i<$n; $i++){
					$authors = implode(' ; ', $this->items[$i]->getAuthors());
		?>
			
				<?php $Itemid = JRequest::getVar('Itemid'); ?>
				<tr class="<?php echo "row$k"; ?>">
				<td width="40%"><a href="index.php?option=com_jresearch&controller=publications&task=show&id=<?php echo $this->items[$i]->id; ?><?php echo !empty($Itemid)?'Itemid='.$Itemid:''; ?>"><?php echo $this->items[$i]->title;  ?></a></td>
				<td width="22%" align="center"><?php echo $authors; ?></td>
				<td width="5%" align="center"><?php echo $this->items[$i]->year; ?></td>
				<td width="15%" align="center"><?php echo $this->items[$i]->journal_acceptance_rate; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="task" value="listpergroup" />
	<input type="hidden" name="controller" value="publications"  />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>