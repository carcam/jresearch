<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for booklet
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $howpublished = trim($this->publication->howpublished);  ?>
<?php if(!empty($howpublished)): ?>
<dt><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></dt>		
<dd><?php echo $howpublished; ?></dd>
<?php endif; ?>
<?php $address = trim($this->publication->address); ?>
<?php if(!empty($address)): ?>
	<dt><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></dt>
	<dd><?php echo $address ?></dd>
<?php endif; ?>
<?php $month = trim($this->publication->month);  ?>
<?php if(!empty($month)): ?>
	<dt><?php echo JText::_('JRESEARCH_MONTH').': ' ?></dt>		
	<dd><?php echo JResearchPublicationsHelper::formatMonth($month); ?></dd>
<?php endif; ?>