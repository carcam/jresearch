<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for digital sources
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $source_type = trim($this->publication->source_type);  ?>
<?php if(!empty($source_type)): ?>
	<dt><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></dt>		
	<dd><?php echo JText::_('JRESEARCH_'.strtoupper($source_type)); ?></dd>
<?php endif; ?>
<?php $publisher = trim($this->publication->publisher); ?>
<?php if(!empty($publisher)): ?>
	<dt><?php echo JText::_('Publisher').': ' ?></dt>		
	<dd><?php echo $publisher; ?></dd>
<?php endif; ?>
<?php $address = trim($this->publication->address); ?>
<?php if(!empty($address)): ?>
	<dt><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></dt>
	<dd><?php echo $address ?></dd>
<?php endif; ?>