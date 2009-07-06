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
		<?php echo !empty($this->lists['search'])?'<div style="float: left">'.$this->lists['search'].'</div>':''; ?>
		<?php echo !empty($this->lists['teams'])?'<div style="float: left">'.$this->lists['teams'].'</div>':''; ?>	
		<?php echo !empty($this->lists['areas'])?'<div style="float: left">'.$this->lists['areas'].'</div>':''; ?>
		<?php echo !empty($this->lists['years'])?'<div style="float: left">'.$this->lists['years'].'</div>':''; ?>
		<?php echo !empty($this->lists['pubtypes'])?'<div style="float: left">'.$this->lists['pubtypes'].'</div>':''; ?>
		<?php echo !empty($this->lists['authors'])?'<div style="float: left">'.$this->lists['authors'].'</div>':''; ?>
		<div><?php echo JHTML::_('Jresearch.icon','add','publications'); ?></div>						
	</div>
	<?php $label = JText::_('JRESEARCH_PUNCTUATION_AVERAGE'); ?>
	<?php if(!empty($this->average))
		printf("<div><h2>%s:&nbsp;%.2f</h2></div>", $label, $this->average); 
	?>
	<table style="clear: both;">
		<thead>
		<tr>		
			<th style="width:3%;">#</th>
			<th style="text-align:center;width:40%;"><?php echo JText::_('JRESEARCH_TITLE'); ?></th>
			<th style="text-align:center;width:35%;"><?php echo JText::_('JRESEARCH_AUTHORS'); ?></th>
			<th style="text-align:center;width:10%;"><?php echo JText::_('JRESEARCH_YEAR'); ?></th>
			<th style="text-align:center;width:10%;"><?php echo $this->punctuationField == 'journal_acceptance_rate'?JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE'):JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR'); ?></th>
			<?php $user = JFactory::getUser(); 
				  if(!$user->guest): ?>
						<th style="width:2%;"></th>
			<?php endif; ?>			
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
			
			if($n > 0):
			$Itemid = JRequest::getVar('Itemid');
			$modelkey = JRequest::getVar('modelkey');
			for($i=0; $i<$n; $i++){
	          		$authors = $this->items[$i]->getAuthors();
    	      			$text = JResearchPublicationsHelper::formatAuthorsArray($authors, $this->format);
		?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $i+1; ?></td>
					<td><a href="index.php?option=com_jresearch&amp;controller=publications&amp;task=show<?php !empty($modelkey)?'&amp;modelkey='.$modelkey:''; ?>&amp;id=<?php echo $this->items[$i]->id; ?><?php echo !empty($Itemid)?'Itemid='.$Itemid:''; ?>"><?php echo $this->items[$i]->title;  ?></a></td>
					<td style="text-align:center"><?php echo $text; ?></td>
					<td style="text-align:center"><?php echo $this->items[$i]->year; ?></td>
					<td style="text-align:center"><?php echo !empty($this->items[$i]->journal_acceptance_rate)?$this->items[$i]->journal_acceptance_rate:'--'; ?></td>
					<?php if(!$user->guest): ?>
						<td><?php JHTML::_('Jresearch.icon', 'edit', 'publications', $this->items[$i]->id); ?></td>
					<?php endif; ?>			
				</tr>
			<?php
			}
			else:
			?>
			<tr><td colspan="5">&nbsp;</td></tr>
			<?php 
			endif;
			?>
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