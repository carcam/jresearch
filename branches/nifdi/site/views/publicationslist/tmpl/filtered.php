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
<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch&amp;view=publicationslist&amp;layout=filtered&amp;task=filtered&amp;modelkey=tabular">
	<div style="text-align:left">
		<?php echo !empty($this->lists['search'])?'<span>'.$this->lists['search'].'</span>':''; ?>
		<?php echo !empty($this->lists['teams'])?'<span>'.$this->lists['teams'].'</span>':''; ?>	
		<?php echo !empty($this->lists['areas'])?'<span>'.$this->lists['areas'].'</span>':''; ?>
		<?php echo !empty($this->lists['years'])?'<span>'.$this->lists['years'].'</span>':''; ?>
		<?php echo !empty($this->lists['pubtypes'])?'<span>'.$this->lists['pubtypes'].'</span>':''; ?>
		<?php echo !empty($this->lists['authors'])?'<span>'.$this->lists['authors'].'</span>':''; ?>
		<div><?php JHTML::_('Jresearch.icon','add','publications'); ?></div>						
	</div>
	<table style="width:100%;">
		<thead>
		<tr>		
			<th style="text-align:center;width:50%;"><?php echo JText::_('JRESEARCH_TITLE'); ?></th>
			<th style="text-align:center;width:38%;" nowrap="nowrap"><?php echo JText::_('JRESEARCH_AUTHORS'); ?></th>
			<th style="text-align:center;width:10%;"><?php echo JText::_('JRESEARCH_YEAR'); ?></th>
			<th></th>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="4">
					<div>&nbsp;</div>
					<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
		<?php 
			$n = count($this->items);
			$Itemid = JRequest::getVar('Itemid');
			$modelkey = JRequest::getVar('modelkey');
			$outIndex = JRequest::getInt('limitstart', 0) + 1;			
			for($i=0; $i<$n; $i++){
		          $authors = $this->items[$i]->getAuthors();
		          $text = JResearchPublicationsHelper::formatAuthorsArray($authors);
		?>
			
				<tr class="<?php $k = i%2; echo "row$k"; ?>">
					<td><a href="index.php?option=com_jresearch&amp;controller=publications&amp;task=show<?php !empty($modelkey)?'&amp;modelkey='.$modelkey:''; ?>&amp;id=<?php echo $this->items[$i]->id; ?><?php echo !empty($Itemid)?'&amp;Itemid='.$Itemid:''; ?>"><?php echo ($outIndex).'.- '.$this->items[$i]->title;  ?></a></td>
					<td style="text-align:center;"><?php echo $text; ?></td>
					<td style="text-align:center;"><?php echo $this->items[$i]->year; ?></td>
					<td><?php JHTML::_('Jresearch.icon', 'edit', 'publications', $this->items[$i]->id); ?></td>
				</tr>
				<?php $outIndex++; ?>
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
