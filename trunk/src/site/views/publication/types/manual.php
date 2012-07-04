<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for manual
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $organization = trim($this->publication->organization);  ?>
	<?php if(!empty($organization)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $organization; ?></div>
	<?php endif; ?>
	<?php $address = trim($this->publication->address); ?>
	<?php if(!empty($address)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></div>
	<div class="divTdl"><?php echo $address; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $edition = trim($this->publication->edition);  ?>
	<?php if(!empty($edition)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_EDITION').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $edition; ?></div>
	<?php endif; ?>
	<?php $month = trim($this->publication->month); ?>
	<?php if(!empty($month)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>
	<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>