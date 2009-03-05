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
global $mainframe;
$style = $mainframe->getParams('com_jresearch')->get('citationStyle', 'APA');

for($i = 0; $i < $n; $i++): 
	$styleObj = JResearchCitationStyleFactory::getInstance($style, $this->items[$i]->pubtype);
	$publicationText = $styleObj->getReferenceHTMLText($this->items[$i], true, true);
	?>
	<tr><td align="right"><?php JHTML::_('Jresearch.icon','edit', 'publications', $this->items[$i]->id); ?> <?php JHTML::_('Jresearch.icon','remove', 'publications', $this->items[$i]->id); ?></td></tr>
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
	<?php $attach = $this->items[$i]->getAttachment(0, 'publications'); ?>
	<tr><td><?php echo $publicationText;  ?>&nbsp;
	<?php if($this->showmore): ?>
		<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $this->items[$i]->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a>&nbsp;
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php 
			  if(!empty($url))
				$link = $url;
			  elseif(!empty($attach))
			  	$link = $attach;							
		 ?>
		<?php if(!empty($link)): ?>
			<?php echo "<a href=\"$link\">[$digitalVersion]</a>"; ?>			
		<?php endif; ?>
	<?php endif; ?>	
	</td></tr>
	<?php $previousYear = $this->items[$i]->year; ?>
<?php endfor; ?>
