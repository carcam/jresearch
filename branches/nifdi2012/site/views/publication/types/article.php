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
<?php $designType = trim($this->publication->design_type); 
	if(!empty($designType)): ?>
	<dt><?php echo JText::_('JRESEARCH_DESIGN_TYPE').': ' ?></dt>		
	<dd><?php echo $designType; ?></dd>
<?php endif; ?>
<?php $fidelityMonitored = $this->publication->fidelity_data_collected; ?> 
<dt><?php echo JText::_('JRESEARCH_FIDELITY_DATA_COLLECTED').': ' ?></dt>		
<dd><?php echo $fidelityMonitored ? JText::_('JYES') : JText::_('JNO'); ?></dd>

<?php $studentsIncluded = trim($this->publication->students_included); 
	if(!empty($studentsIncluded)): ?>
	<dt><?php echo JText::_('JRESEARCH_STUDENTS_INCLUDED').': ' ?></dt>		
	<dd><?php echo $studentsIncluded; ?></dd>
<?php endif; ?>
<?php $otherTags = trim($this->publication->other_tags); 
	if(!empty($otherTags)): ?>
	<dt><?php echo JText::_('JRESEARCH_OTHER_TAGS').': ' ?></dt>		
	<dd><?php echo $otherTags; ?></dd>
<?php endif; ?>
<?php $location = trim($this->publication->location); 
	if(!empty($location)): ?>
	<dt><?php echo JText::_('JRESEARCH_LOCATION').': ' ?></dt>		
	<dd><?php echo $location; ?></dd>
<?php endif; ?>


	
	
<?php echo isset($this->reference)?$this->reference:''; ?>