<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for booklet
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $colspan = 4; ?>
	<?php $howpublished = trim($this->publication->howpublished);  ?>
	<?php if(!empty($howpublished)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $howpublished; ?></div>
	<?php endif; ?>
	<?php $address = trim($this->publication->address); ?>
	<?php if(!empty($address)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></div>
	<div class="divTdl"><?php echo $address ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<div>
	<?php $colspan = 4; ?>
	<?php $month = trim($this->publication->month);  ?>
	<?php if(!empty($month)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>		
	<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>