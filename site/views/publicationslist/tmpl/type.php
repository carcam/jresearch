<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for showing the type of a publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

$n = count($this->items);
$previousType = null;
for($i = 0; $i < $n; $i++ ): 
	$publicationText = $this->style->getReferenceHTMLText($this->items[$i], true, true);
	if($previousYear != $this->items[$i]->pubtype):
		$header = JText::_('JRESEARCH_PUBLICATION_TYPE').': '.$this->items[$i]->pubtype;			
?>
		<tr><td class="sectiontableheader"><?php echo $header; ?></td></tr>
	<?php endif; ?>
	<?php $digitalVersion = JText::_('JRESEARCH_DIGITAL_VERSION'); ?>
	<?php $url = $this->items[$i]->url; ?>	
	<tr><td><?php echo $publicationText;  ?>&nbsp;
		<?php if($this->showmore): ?>
		<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $this->items[$i]->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a>&nbsp;
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php echo !empty($url)?"<a href=\"$url\">[$digitalVersion]</a>":''; ?>
	<?php endif; ?>
	</td></tr>
	<?php $previousYear = $this->items[$i]->pubtype; ?>
<?php endfor; ?>
