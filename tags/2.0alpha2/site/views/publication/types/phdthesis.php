<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for phdthesis
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $school = trim($this->publication->school);  ?>
	<?php if(!empty($school)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_SCHOOL').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $school; ?></div>
	<?php endif; ?>
	<?php $type = trim($this->publication->type); ?>
	<?php if(!empty($type)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></div>
	<div class="divTdl divTdl2"><?php echo $type; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<?php $address = trim($this->publication->address);  ?>
	<?php if(!empty($address)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $address; ?></div>
	<?php endif; ?>
	<?php $type = trim($this->publication->month); ?>
	<?php if(!empty($month)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>
	<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>