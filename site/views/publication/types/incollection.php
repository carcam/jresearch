<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for incollection
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php $booktitle = trim($this->publication->booktitle);  ?>
<?php if(!empty($journal)): ?>
	<dt><?php echo JText::_('JRESEARCH_BOOK_TITLE').': ' ?></dt>		
	<dd><?php echo $booktitle; ?></dd>
<?php endif; ?>
<?php $publisher = trim($this->publication->publisher); ?>
<?php if(!empty($publisher)): ?>
	<dt><?php echo JText::_('Publisher').': ' ?></dt>
	<dd><?php echo $publisher; ?></dd>
<?php endif; ?>
<?php $editor = trim($this->publication->editor);  ?>
<?php if(!empty($editor)): ?>
	<dt><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></dt>		
	<dd><?php echo $editor; ?></dd>
<?php endif; ?>
<?php $organization = trim($this->publication->organization); ?>
<?php if(!empty($organization)): ?>
	<dt><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></dt>
	<dd><?php echo $organization; ?></dd>
<?php endif; ?>
<?php $address = trim($this->publication->address);  ?>
<?php if(!empty($address)): ?>
	<dt><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></dt>		
	<dd><?php echo $address; ?></dd>
<?php endif; ?>
<?php $month = trim($this->publication->month); ?>
<?php if(!empty($month)): ?>
	<dt><?php echo JText::_('JRESEARCH_MONTH').': ' ?></dt>
	<dd><?php echo JResearchPublicationsHelper::formatMonth($month); ?></dd>
<?php endif; ?>
<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
<?php if(!empty($pages)): ?>
	<dt><?php echo JText::_('JRESEARCH_PAGES').': ' ?></dt>		
	<dd><?php echo $pages; ?></dd>
<?php endif; ?>

<?php $isbn = trim($this->publication->isbn);  ?>
<?php if(!empty($isbn)): ?>
	<dt><?php echo JText::_('JRESEARCH_ISBN').': ' ?></dt>		
	<dd><?php echo $isbn; ?></dd>
<?php endif; ?>
<?php echo isset($this->reference)?$this->reference:''; ?>