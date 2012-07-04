<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for unpublished publication
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="divTR">
	<?php $month = trim($this->publication->month);  ?>
	<?php if(!empty($month)): ?>
	<div class="divTR"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>		
	<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>			
</div>
