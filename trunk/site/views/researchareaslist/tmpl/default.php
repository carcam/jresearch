<?php
/**
 * @package JResearch
 * @subpackage ResearchAreas
 * Default View for showing a list of research areas
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?></h1>
<ul style="padding-left:0px;">
	<?php foreach($this->items as $area): ?>
	<li class="liresearcharea">
		<div>
			<?php $contentArray = explode('<hr id="system-readmore" />', $area->description); ?>
			<?php $itemId = JRequest::getVar('Itemid'); ?>
			<h2 class="contentheading"><a href="index.php?option=com_jresearch&amp;task=show&amp;view=researcharea&amp;id=<?php echo $area->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo $area->name; ?></a></h2>			
			<p><?php echo $contentArray[0]; ?></p>
			<div>&nbsp;</div>			
			<div style="text-align:left"><a href="index.php?option=com_jresearch&amp;task=show&amp;view=researcharea&amp;id=<?php echo $area->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>" ><?php echo JText::_('JRESEARCH_READ_MORE'); ?></a></div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
<div style="width:100%;text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>