<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for listing publications per group
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');

?>

<h1 class="componentheading"><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></h1>
<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch">
	<div style="text-align:left">
		<?php echo !empty($this->lists['search'])?'<span>'.$this->lists['search'].'</span>':''; ?>
		<?php echo !empty($this->lists['teams'])?'<span>'.$this->lists['teams'].'</span>':''; ?>	
		<?php echo !empty($this->lists['areas'])?'<span>'.$this->lists['areas'].'</span>':''; ?>
		<?php echo !empty($this->lists['years'])?'<span>'.$this->lists['years'].'</span>':''; ?>
		<?php echo !empty($this->lists['pubtypes'])?'<span>'.$this->lists['pubtypes'].'</span>':''; ?>
		<?php echo !empty($this->lists['authors'])?'<span>'.$this->lists['authors'].'</span>':''; ?>
		<div><?php JHTML::_('Jresearch.icon','add','publications'); ?></div>						
	</div>
	<?php $label = JText::_('JRESEARCH_PUNCTUATION_AVERAGE'); ?>
	<?php if(!empty($this->average))
		printf("<div><h2>%s:&nbsp;%.2f</h2></div>", $label, $this->average); 
	?>
	<table>
		<thead>
		<tr>		
			<th width="3%">#</th>
			<th style="text-align:center;" width="40%"><?php echo JText::_('JRESEARCH_TITLE'); ?></th>
			<th style="text-align:center;" nowrap="nowrap" width="35%"><?php echo JText::_('JRESEARCH_AUTHORS'); ?></th>
			<th style="text-align:center;" width="10%"><?php echo JText::_('JRESEARCH_YEAR'); ?></th>
			<th style="text-align:center;" width="10%"><?php echo $this->punctuationField == 'journal_acceptance_rate'?JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE'):JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR'); ?></th>
			<th width="2%"></th>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="6">
					<div>&nbsp;</div>
					<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
		<?php 
			$n = count($this->items);
			for($i=0; $i<$n; $i++){
					$authors = implode(' ; ', JResearchPublicationsHelper::bibCharsToUtf8FromArray($this->items[$i]->getAuthors()));
		?>
			
				<?php $Itemid = JRequest::getVar('Itemid'); ?>
				<tr class="<?php echo "row$k"; ?>">
					<td width="3%"><?php echo $i; ?></td>
					<td width="40%"><a href="index.php?option=com_jresearch&controller=publications&task=show&id=<?php echo $this->items[$i]->id; ?><?php echo !empty($Itemid)?'Itemid='.$Itemid:''; ?>"><?php echo $this->items[$i]->title;  ?></a></td>
					<td width="35%" align="center"><?php echo $authors; ?></td>
					<td width="10%" align="center"><?php echo $this->items[$i]->year; ?></td>
					<?php if($this->punctuationField == 'journal_acceptance_rate'): ?>
						<td width="10%" align="center"><?php echo !empty($this->items[$i]->journal_acceptance_rate)?$this->items[$i]->journal_acceptance_rate:'--'; ?></td>
					<?php else: ?>
						<td width="10%" align="center"><?php echo !empty($this->items[$i]->impact_factor)?$this->items[$i]->impact_factor:'--'; ?></td>					
					<?php endif; ?>	
					<td width="2%"><?php JHTML::_('Jresearch.icon', 'edit', 'publications', $this->items[$i]->id); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="task" value="filtered" />
	<input type="hidden" name="controller" value="publications"  />
	<input type="hidden" name="limitstart" value="0" />
	<input type="hidden" name="modelkey" value="tabular" />
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />	
	<?php echo JHTML::_( 'form.token' ); ?>
</form>