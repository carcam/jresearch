<?php
/**
 * @package JResearch
 * @subpackage Theses
 * Default view for showing a list of theses
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_THESES'); ?></h1>
<?php
if(count($this->items) > 0):
?>
<ul style="padding-left:0px;">
<?php foreach($this->items as $thesis): ?>
	<?php $researchArea = $this->areaModel->getItem($thesis->id_research_area); ?>
	<li class="liresearcharea">
		<div>
			<?php $contentArray = explode('<hr id="system-readmore" />', $thesis->description); ?>
			<?php $itemId = JRequest::getVar('Itemid'); ?>
			<h2 class="contentheading"><?php echo $thesis->title; ?></h2>
			<div>&nbsp;</div>			
			<div><strong><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '?></strong>
			
				<?php if($researchArea->id > 1): ?>
					<span><a href="index.php?option=com_jresearch&amp;view=researcharea&amp;id=<?php echo $researchArea->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo $researchArea->name;  ?></a></span>
				<?php else: ?>
					<span><?php echo $researchArea->name;  ?></span>
				<?php endif; ?>
			</div>			
			<div>&nbsp;</div>
			<p><?php echo $contentArray[0]; ?></p>
			<div style="text-align:left"><a href="index.php?option=com_jresearch&amp;task=show&amp;view=thesis&amp;id=<?php echo $thesis->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>" ><?php echo JText::_('JRESEARCH_READ_MORE'); ?></a></div>
		</div>
	</li>	
<?php endforeach; ?>
</ul>
<?php
endif;
?>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>