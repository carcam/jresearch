<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for patent
 * @todo IMPLEMENT DRAWINGS DIR
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $patent_number = trim($this->publication->patent_number);  ?>
<?php if(!empty($patent_number)): ?>
	<dt><?php echo JText::_('JRESEARCH_PATENT_NUMBER').': ' ?></dt>		
	<dd><?php echo $patent_number; ?></dd>
<?php endif; ?>
<?php $filing_date = trim($this->publication->filing_date);  ?>
<?php if(!empty($filing_date)): ?>
	<dt><?php echo JText::_('JRESEARCH_FILING_DATE').': ' ?></dt>		
	<dd><?php echo $filing_date; ?></dd>
<?php endif; ?>
<?php $issue_date = trim($this->publication->issue_date); ?>
<?php if(!empty($issue_date)): ?>
	<dt><?php echo JText::_('JRESEARCH_ISSUE_DATE').': ' ?></dt>
	<dd><?php echo $issue_date; ?></dd>
<?php endif; ?>
<?php $filing_date = trim($this->publication->filing_date);  ?>
<?php if(!empty($filing_date)): ?>
	<dt><?php echo JText::_('JRESEARCH_FILING_DATE').': ' ?></dt>		
	<dd><?php echo $filing_date; ?></dd>
<?php endif; ?>
<?php $issue_date = trim($this->publication->issue_date); ?>
<?php if(!empty($issue_date)): ?>
	<dt><?php echo JText::_('JRESEARCH_ISSUE_DATE').': ' ?></dt>
	<dd><?php echo $issue_date; ?></dd>
<?php endif; ?>
<?php $country = trim($this->publication->address);  ?>
<?php if(!empty($address)): ?>
	<dt><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></dt>		
	<dd><?php echo $address; ?></dd>
<?php endif; ?>
<?php $office = trim($this->publication->office); ?>
<?php if(!empty($office)): ?>
	<dt><?php echo JText::_('JRESEARCH_PATENT_OFFICE').': ' ?></dt>
	<dd><?php echo $office; ?></dd>
<?php endif; ?>
<?php $claims = trim($this->publication->claims);  ?>
<?php if(!empty($claims)): ?>
	<dt><?php echo JText::_('JRESEARCH_CLAIMS').': ' ?></dt>		
	<dd><?php echo $claims; ?></dd>
<?php endif; ?>
<?php echo isset($this->reference)?$this->reference:''; ?>