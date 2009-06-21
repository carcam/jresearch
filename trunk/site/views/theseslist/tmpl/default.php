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
			<div><strong><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '?></strong><span><?php echo $researchArea->name;  ?></span></div>			
			<div>&nbsp;</div>
			<p><?php echo $contentArray[0]; ?></p>
			<?php if(count($contentArray) > 1 ): ?>
				<div style="text-align:left"><a href="index.php?option=com_jresearch&task=show&view=thesis&id=<?php echo $thesis->id; ?><?php echo isset($itemId)?'&Itemid='.$itemId:''; ?>" ><?php echo JText::_('JRESEARCH_READ_MORE'); ?></a></div>
			<?php endif; ?>	
		</div>

	</li>	
<?php endforeach; ?>
</ul>
<?php
endif;
?>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>