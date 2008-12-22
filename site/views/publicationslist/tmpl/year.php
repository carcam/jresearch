<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for showing the year of a publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

$n = count($this->items);
$previousYear = null;
for($i = 0; $i < $n; $i++ ): 
	$publicationText = $this->style->getReferenceHTMLText($this->items[$i], true, true);
	?>
	<tr><td align="right"><?php JHTML::_('Jresearch.icon','edit', 'publications', $this->items[$i]->id); ?></td></tr>
	<?php
	if($previousYear != $this->items[$i]->year):
		if($this->items[$i]->year == '0000' || $this->items[$i]->year == null )
			$yearHeader = JText::_('JRESEARCH_NO_YEAR');
		else
			$yearHeader = JText::_('JRESEARCH_YEAR').': '.$this->items[$i]->year;			
	?>
		<tr><td class="sectiontableheader"><?php echo $yearHeader; ?></td></tr>
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
	<?php $previousYear = $this->items[$i]->year; ?>
<?php endfor; ?>
