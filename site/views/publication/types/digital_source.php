<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for digital sources
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $source_type = trim($this->publication->source_type);  ?>
	<?php if(!empty($source_type)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo JText::_('JRESEARCH_'.strtoupper($source_type)); ?></div>
	<?php endif; ?>
	<?php $publisher = trim($this->publication->publisher); ?>
	<?php if(!empty($publisher)): ?>
	<div class="divTd"><?php echo JText::_('Publisher').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $publisher; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $address = trim($this->publication->address); ?>
	<?php if(!empty($address)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></div>
	<div class="divTdl"><?php echo $address ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>