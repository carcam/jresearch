<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for misc. publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="divTR">
	<?php $howpublished = trim($this->publication->howpublished);  ?>
	<?php if(!empty($howpublished)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $howpublished; ?></div>
	<?php endif; ?>
	<?php $month = trim($this->publication->month); ?>
	<?php if(!empty($month)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>
	<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
	<?php endif; ?>
</div>