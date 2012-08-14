<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for manual
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $organization = trim($this->publication->organization);  ?>
<?php if(!empty($organization)): ?>
	<dt><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></dt>		
	<dd><?php echo $organization; ?></dd>
<?php endif; ?>
<?php $address = trim($this->publication->address); ?>
<?php if(!empty($address)): ?>
	<dt><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></dt>
	<dd><?php echo $address; ?></dd>
<?php endif; ?>
<?php $edition = trim($this->publication->edition);  ?>
<?php if(!empty($edition)): ?>
	<dt><?php echo JText::_('JRESEARCH_EDITION').': ' ?></dt>		
	<dd><?php echo $edition; ?></dd>
<?php endif; ?>
<?php $month = trim($this->publication->month); ?>
<?php if(!empty($month)): ?>
	<dt><?php echo JText::_('JRESEARCH_MONTH').': ' ?></dt>
	<dd><?php echo JResearchPublicationsHelper::formatMonth($month); ?></dd>
<?php endif; ?>