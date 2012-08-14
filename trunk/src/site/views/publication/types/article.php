<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for article
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
<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
<?php if(!empty($pages)): ?>
	<dt><?php echo JText::_('JRESEARCH_PAGES').': ' ?></dt>
	<dd><?php echo $pages ?></dd>
<?php endif; ?>
<?php $month = trim($this->publication->month);  ?>
<?php if(!empty($month)): ?>
	<dt><?php echo JText::_('JRESEARCH_MONTH').': ' ?></dt>		
	<dd><?php echo JResearchPublicationsHelper::formatMonth($month); ?></dd>
<?php endif; ?>
<?php $issn = trim($this->publication->issn);  ?>
<?php if(!empty($issn)): ?>
	<dt><?php echo JText::_('JRESEARCH_ISSN').': ' ?></dt>		
	<dd><?php echo JResearchPublicationsHelper::formatISSN($issn); ?></dd>
<?php endif; ?>
<?php echo isset($this->reference)?$this->reference:''; ?>