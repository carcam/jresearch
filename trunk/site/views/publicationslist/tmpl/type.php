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
	<tr><td><?php echo $publicationText;  ?>&nbsp;<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $this->items[$i]->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a></td></tr>
	<?php $previousYear = $this->items[$i]->pubtype; ?>
<?php endfor; ?>

