<?php
/**
 * @package JResearch
 * @subpackage ResearchAreas
 * Default View for showing a list of research areas
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h2 class="componentheading"><?php echo $this->params->get('researchareas_header'); ?></h2>

<p>
    <?php echo $this->params->get('researchareas_introtext'); ?>
</p>

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
        			<h3 class="contentheading"><?php echo JHTML::_('jresearchfrontend.link', $area->name, 'researcharea', 'show', $area->id); ?></h3>
                         <?php else: ?>
        			<h3 class="contentheading"><?php echo $area->name ?></h3>
                        <?php endif; ?>
			<?php echo $contentArray[0]; ?>
			<div>&nbsp;</div>			
			<div style="text-align:left"><?php echo JHTML::_('jresearchfronten.link', JText::_('JRESEARCH_READ_MORE'), 'researcharea', 'show', $area->id); ?></div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
<?php
endif;
?>
<div style="width:100%;text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>