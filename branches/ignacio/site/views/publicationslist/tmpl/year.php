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
?>
<?php
for($i = 0; $i < $n; $i++): 
	$styleObj = JResearchCitationStyleFactory::getInstance($style, $this->items[$i]->pubtype);
	$publicationText = $styleObj->getReferenceHTMLText($this->items[$i], true, true);
	?>
	<?php
	if($previousYear != $this->items[$i]->year):
		if($this->items[$i]->year == '0000' || $this->items[$i]->year == null )
			$yearHeader = JText::_('JRESEARCH_NO_YEAR');
		else
			$yearHeader = JText::_('JRESEARCH_YEAR').': '.$this->items[$i]->year;

		if($i > 0)
			echo "</ul>";	
	?>
		<div class="frontendheader"><?php echo $yearHeader; ?></div><ul>
	<?php endif; ?>
	<?php $digitalVersion = JText::_('JRESEARCH_DIGITAL_VERSION'); ?>
	<?php $url = $this->items[$i]->url; ?>
	<?php $attach = $this->items[$i]->getAttachment(0, 'publications'); ?>
	<li>
	<span><?php echo $publicationText;  ?></span>
	<?php if($this->showmore): ?>
		<span><a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $this->items[$i]->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a></span>
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php 
			  if(!empty($url))
				$link = $url;
			  elseif(!empty($attach))
			  	$link = $attach;							
		 ?>
		<?php if(!empty($link)): ?>
			<?php echo "<span><a href=\"$link\">[$digitalVersion]</a></span>"; ?>			
		<?php endif; ?>
	<?php endif; ?>	
	<span><?php JHTML::_('Jresearch.icon','edit', 'publications', $this->items[$i]->id); ?> <?php JHTML::_('Jresearch.icon','remove', 'publications', $this->items[$i]->id); ?></span>
	</li>
	<?php $previousYear = $this->items[$i]->year; ?>
	<?php if($i == $n - 1): ?>
		</ul>
	<?php endif; ?>
<?php endfor; ?>