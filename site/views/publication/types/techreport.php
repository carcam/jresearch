<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for a techreport
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php $address = trim($this->publication->address);  ?>
<?php if(!empty($address)): ?>
	<dt><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></dt>		
	<dd><?php echo $address; ?></dd>
<?php endif; ?>
<?php $institution = trim($this->publication->institution); ?>
<?php if(!empty($institution)): ?>
	<dt><?php echo JText::_('JRESEARCH_INSTITUTION').': ' ?></dt>
	<dd><?php echo $institution; ?></dd>
<?php endif; ?>
<?php $number = trim($this->publication->number);  ?>
<?php if(!empty($number)): ?>
	<dt><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></dt>		
	<dd><?php echo $number; ?></dd>
<?php endif; ?>
<?php $type = trim($this->publication->type); ?>
<?php if(!empty($type)): ?>
	<dt><?php echo JText::_('JRESEARCH_TYPE').': ' ?></dt>
	<dd><?php echo $type; ?></dd>
<?php endif; ?>
<?php $month = trim($this->publication->month);  ?>
<?php if(!empty($number)): ?>
	<dt><?php echo JText::_('JRESEARCH_MONTH').': ' ?></dt>		
	<dd><?php echo JResearchPublicationsHelper::formatMonth($month); ?></dd>
<?php endif; ?>