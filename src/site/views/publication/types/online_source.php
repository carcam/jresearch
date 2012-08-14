<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for online sources
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $source_type = trim($this->publication->source_type);  ?>
<?php if(!empty($source_type)): ?>
	<dt><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></dt>		
	<dd><?php echo JText::_('JRESEARCH_'.strtoupper($source_type)); ?></dd>
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