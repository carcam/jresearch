<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for electronic articles
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $journal = trim($this->publication->journal);  ?>
<?php if(!empty($journal)): ?>
	<dt><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></dt>		
	<dd><?php echo $journal; ?></dd>
<?php endif; ?>
<?php $volume = trim($this->publication->volume); ?>
<?php if(!empty($volume)): ?>
	<dt><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></dt>
	<dd><?php echo $volume; ?></dd>
<?php endif; ?>
<?php $number = trim($this->publication->number);  ?>
<?php if(!empty($number)): ?>
	<dt><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></dt>		
	<dd><?php echo $number; ?></dd>
<?php endif; ?>
<?php $access_date = trim($this->publication->access_date); ?>
<?php if(!empty($access_date) && $access_date != '0000-00-00'): ?>
<?php $accessArr = explode(' ', $access_date); ?>
	<dt><?php echo JText::_('JRESEARCH_ACCESS_DATE').': ' ?></dt>
	<dd><?php echo $accessArr[0]; ?></dd>	
<?php endif; ?>
<?php $month = trim($this->publication->month);  
	  $day = trim($this->publication->day);
?>
<?php if(!empty($month)): ?>
	<?php if(empty($day)): ?>
		<dt><?php echo JText::_('JRESEARCH_MONTH').': ' ?></dt>		
		<dd><?php echo JResearchPublicationsHelper::formatMonth($month); ?></dd>
	<?php else: ?>
		<dt><?php echo JText::_('JRESEARCH_DATE').': ' ?></dt>		
		<dd><?php echo JResearchPublicationsHelper::formatMonth($month).', '.$day; ?></dd>		
	<?php endif; ?>
<?php endif; ?>