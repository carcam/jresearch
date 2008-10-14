<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

$n = count($this->items);
$previousYear = null;
for($i = 0; $i < $n; $i++ ): 
	$publicationText = $this->style->getReferenceHTMLText($this->items[$i], true, true);
	if($previousYear != $this->items[$i]->year):
		if($this->items[$i]->year == '0000' || $this->items[$i]->year == null )
			$yearHeader = JText::_('JRESEARCH_NO_YEAR');
		else
			$yearHeader = JText::_('JRESEARCH_YEAR').': '.$this->items[$i]->year;			
?>
		<tr><td class="sectiontableheader"><?php echo $yearHeader; ?></td></tr>
	<?php endif; ?>
	<tr><td><?php echo $publicationText;  ?>&nbsp;<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $this->items[$i]->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a></td></tr>
	<?php $previousYear = $this->items[$i]->year; ?>
<?php endfor; ?>
