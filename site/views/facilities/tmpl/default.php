<?php
/**
 * @package JResearch
 * @subpackage Facilities
 * Default view for showing a list of facilities
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_FACILITIES'); ?></h1>
<?php
if(count($this->items) > 0):
?>
<ul id="jresearch-facility-list" style="padding-left:0px;">
	<?php foreach($this->items as $fac): ?>
		<?php $researchArea = $this->areaModel->getItem($fac->id_research_area); ?>
		<li class="liresearcharea">
			<div>
				<?php $itemId = JRequest::getVar('Itemid'); ?>
				<h2 class="contentheading">
					<?php echo $fac->name; ?>
				</h2>		
				<div>
					<strong>
						<?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '?>
					</strong>
					<span>
						<?php echo $researchArea->name;  ?>
					</span>
				</div>			
				<div>&nbsp;</div>
				<div style="text-align:left">
					<?php echo JHTML::_('jresearch.link', JText::_('JRESEARCH_READ_MORE'), 'facility', 'show', $fac->id); ?>
				</div>
			</div>
		</li>	
	<?php endforeach; ?>
</ul>
<?php
endif;
?>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>