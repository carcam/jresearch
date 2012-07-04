<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for electronic articles
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $colspan = 4; ?>
	<?php $journal = trim($this->publication->journal);  ?>
	<?php if(!empty($journal)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $journal; ?></div>
	<?php endif; ?>
	<?php $volume = trim($this->publication->volume); ?>
	<?php if(!empty($volume)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></div>
	<div class="divTdl"><?php echo $volume; ?></div>
	<?php endif; ?>
</div>
<div class="divTR">
	<?php $number = trim($this->publication->number);  ?>
	<?php if(!empty($number)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $number; ?></div>
	<?php endif; ?>
	<?php $access_date = trim($this->publication->access_date); ?>
	<?php if(!empty($access_date) && $access_date != '0000-00-00'): ?>
	<?php $accessArr = explode(' ', $access_date); ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ACCESS_DATE').': ' ?></div>
	<div class="divTdl"><?php echo $accessArr[0]; ?></div>	
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<?php $month = trim($this->publication->month);  
		  $day = trim($this->publication->day);
	?>
	<?php if(!empty($month)): ?>
		<?php if(empty($day)): ?>
			<?php $colspan -= 2; ?>
			<div class="divTd"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>		
			<div class="divTdl divTdl2"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
		<?php else: ?>
			<div class="divTd"><?php echo JText::_('JRESEARCH_DATE').': ' ?></div>		
			<div class="divTdl divTdl2"><?php echo JResearchPublicationsHelper::formatMonth($month).', '.$day; ?></div>		
		<?php endif; ?>
	<?php endif; ?>
	<div class="divEspacio"></div>
</div>