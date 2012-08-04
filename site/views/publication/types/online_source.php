<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for online sources
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $colspan = 4; ?>
	<?php $source_type = trim($this->publication->source_type);  ?>
	<?php if(!empty($source_type)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTdl"><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo JText::_('JRESEARCH_'.strtoupper($source_type)); ?></div>
	<?php endif; ?>
	<?php $access_date = trim($this->publication->access_date); ?>
	<?php if(!empty($access_date) && $access_date != '0000-00-00'): ?>
	<?php $colspan -= 2; ?>
	<?php $accessArr = explode(' ', $access_date); ?>
	<div class="divTdl"><?php echo JText::_('JRESEARCH_ACCESS_DATE').': ' ?></div>
	<div class="divTdl"><?php echo $accessArr[0]; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $colspan = 4; ?>
	<?php $month = trim($this->publication->month);  
		  $day = trim($this->publication->day);
	?>
	<?php if(!empty($month)): ?>
		<?php if(empty($day)): ?>
			<?php $colspan -= 2; ?>
			<div class="divTdl"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>		
			<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
		<?php else: ?>
			<div class="divTdl"><?php echo JText::_('JRESEARCH_DATE').': ' ?></div>		
			<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month).', '.$day; ?></div>		
		<?php endif; ?>
	<?php endif; ?>	
	<div class="divEspacio"></div>	
</div>