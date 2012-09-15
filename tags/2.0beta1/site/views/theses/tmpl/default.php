<?php
/**
 * @package JResearch
 * @subpackage Theses
 * Default view for showing a list of theses
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h2 class="componentheading"><?php echo JText::_('JRESEARCH_THESES'); ?></h2>
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
			<h3 class="contentheading"><?php echo $thesis->title; ?></h3>
			<div>&nbsp;</div>			
			<div><h4><?php echo JText::_('JRESEARCH_RESEARCH_AREA')?></h4>
			
				<?php if($researchArea->id > 1): ?>
					<span><?php echo JHTML::_('jresearch.link', $researchArea->name, 'researcharea', 'show', $researchArea->id); ?></span>
				<?php else: ?>
					<span><?php echo $researchArea->name;  ?></span>
				<?php endif; ?>
			</div>			
			<div>&nbsp;</div>
			<?php echo $contentArray[0]; ?>
			<div style="text-align:left"><?php echo JHTML::_('jresearch.link', JText::_('JRESEARCH_READ_MORE'), 'thesis', 'show', $thesis->id); ?></div>
		</div>
	</li>	
<?php endforeach; ?>
</ul>
<?php
endif;
?>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>