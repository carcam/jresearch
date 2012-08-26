<?php
/**
 * @package JResearch
 * @subpackage ResearchAreas
 * Default View for showing a list of research areas
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php if($this->params->get('show_page_heading', 1)): ?>
	<h1 class="componentheading"><?php echo $this->escape($this->params->get('page_heading', JText::_('JRESEARCH_RESEARCH_AREAS'))); ?></h1>
<?php endif; ?>

<?php $introText = $this->params->get('researchareas_introtext', ''); ?>
<?php if(!empty($introText)): ?>
<p>
    <?php echo $introText; ?>
</p>
<?php endif; ?>
<?php
if(count($this->items) > 0):
?>
<ul style="padding-left:0px;">
	<?php foreach($this->items as $area): ?>
	<li class="liresearcharea">
		<div>
			<?php $contentArray = explode('<hr id="system-readmore" />', $area->description); ?>
			<?php $itemId = JRequest::getVar('Itemid'); ?>
                        <?php if($this->params->get('researchareas_link_in_title')): ?>
        			<h2 class="contentheading"><?php echo JHTML::_('jresearchfrontend.link', $area->name, 'researcharea', 'show', $area->id); ?></h2>
                         <?php else: ?>
        			<h2 class="contentheading"><?php echo $area->name ?></h2>
                        <?php endif; ?>
			<?php echo $contentArray[0]; ?>			
			<div style="text-align:left"><?php echo JHTML::_('jresearchfrontend.link', JText::_('JRESEARCH_READ_MORE'), 'researcharea', 'show', $area->id); ?></div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
<?php
endif;
?>
<div class="frontendPagination"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>