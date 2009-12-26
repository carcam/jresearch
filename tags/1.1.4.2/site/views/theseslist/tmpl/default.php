<?php
/**
 * @package JResearch
 * @subpackage Theses
 * Default view for showing a list of theses
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_THESES'); ?></h1>
<ul style="padding-left:0px;">
 
<?php foreach($this->items as $thesis): ?>
	<?php $researchArea = $this->areaModel->getItem($thesis->id_research_area); ?>
	<li class="liresearcharea">
		<div>
			<?php $contentArray = explode('<hr id="system-readmore" />', $thesis->description); ?>
			<?php $itemId = JRequest::getVar('Itemid'); ?>
			<div class="contentheading"><?php echo $thesis->title; ?></div>
			<div>&nbsp;</div>			
			<div><span style="font-weight:bold;"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '?></span>
				<span>
				<?php if($researchArea->id > 1): ?>
					<a href="index.php?option=com_jresearch&amp;controller=researchAreas&amp;task=show&amp;view=researcharea&amp;id=<?php echo $researchArea->id; ?><?php echo $ItemidText ?>"><?php echo $researchArea->name; ?></a>
				<?php else: ?>
					<?php echo $researchArea->name; ?>	
				<?php endif; ?>	
				</span>
			</div>
			<div>&nbsp;</div>
			<div><?php echo $contentArray[0]; ?></div>
			<div style="text-align:left"><a href="index.php?option=com_jresearch&amp;task=show&amp;view=thesis&amp;id=<?php echo $thesis->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>" ><?php echo JText::_('JRESEARCH_READ_MORE'); ?></a></div>
		</div>

	</li>	
<?php endforeach; ?>
</ul>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>