<?php
/**
 * @package JResearch
 * @subpackage Facilities
 * Default view for showing a list of facilities
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo JText::_('JRESEARCH_FACILITIES'); ?></div>
<ul style="padding-left:0px;">
	<?php foreach($this->items as $fac): ?>
		<?php $researchArea = $this->areaModel->getItem($fac->id_research_area); ?>
		<li class="liresearcharea">
			<div>
				<?php $itemId = JRequest::getVar('Itemid'); ?>
				<div class="contentheading">
					<?=$fac->name; ?>
				</div>		
				<div>
					<span style="font-weight:bold;">
						<?=JText::_('JRESEARCH_RESEARCH_AREA').': '?>
					</span>
					<span>
						<?=$researchArea->name;  ?>
					</span>
				</div>			
				<div>&nbsp;</div>
				<div style="text-align:left">
					<a href="index.php?option=com_jresearch&task=show&view=facility&id=<?php echo $fac->id; ?><?php echo isset($itemId)?'&Itemid='.$itemId:''; ?>" >
						<?php echo JText::_('JRESEARCH_READ_MORE'); ?>
					</a>
				</div>
			</div>
		</li>	
	<?php endforeach; ?>
</ul>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>