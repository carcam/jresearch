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
global $mainframe;
$style = $mainframe->getParams('com_jresearch')->get('citationStyle', 'APA');

for($i = 0; $i < $n; $i++ ): 
	$styleObj = JResearchCitationStyleFactory::getInstance($style, $this->items[$i]->pubtype);
	$publicationText = $styleObj->getReferenceHTMLText($this->items[$i], true, true);
	?>

	<?php
	if($previousType != $this->items[$i]->pubtype):
		$header = JText::_('JRESEARCH_PUBLICATION_TYPE').': '.$this->items[$i]->pubtype;	
		if($i > 0)
			echo "</ul>";		
	?>
		<div class="frontendheader"><?php echo $header; ?></div><ul>
	<?php endif; ?>
	<?php $digitalVersion = JText::_('JRESEARCH_DIGITAL_VERSION'); ?>
	<?php $url = $this->items[$i]->url; ?>	
	<?php $attach = $this->items[$i]->getAttachment(0, 'publications'); ?>		
			
	<li><span><?php echo $publicationText;  ?></span>
		<?php if($this->showmore): ?>
		<span><a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $this->items[$i]->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a></span>
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php if(!empty($url))
				$link = $url;
			  elseif(!empty($attach))
			  	$link = $attach;							
		 ?>
		<?php if(!empty($link)): ?>
			<?php "<span><a href=\"$link\">[$digitalVersion]</a></span>"; ?>			
		<?php endif; ?>
	<?php endif; ?>
	<span><?php JHTML::_('Jresearch.icon','edit', 'publications', $this->items[$i]->id); ?> <?php JHTML::_('Jresearch.icon','remove', 'publications', $this->items[$i]->id); ?></span>	
	</li>
	<?php if($i == $n - 1): ?>
		</ul>
	<?php endif; ?>
	<?php $previousType = $this->items[$i]->pubtype; ?>
<?php endfor; ?>