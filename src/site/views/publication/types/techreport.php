<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for a techreport
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="divTR">
	<?php $address = trim($this->publication->address);  ?>
	<?php if(!empty($address)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></div>		
	<div class="divTdl"><?php echo $address; ?></div>
	<?php endif; ?>
	<?php $institution = trim($this->publication->institution); ?>
	<?php if(!empty($institution)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_INSTITUTION').': ' ?></div>
	<div class="divTdl"><?php echo $institution; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $number = trim($this->publication->number);  ?>
	<?php if(!empty($number)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></div>		
	<div class="divTdl"><?php echo $number; ?></div>
	<?php endif; ?>
	<?php $type = trim($this->publication->type); ?>
	<?php if(!empty($type)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></div>
	<div class="divTdl"><?php echo $type; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>

<div class="divTR">
	<?php $month = trim($this->publication->month);  ?>
	<?php if(!empty($number)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>		
	<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>