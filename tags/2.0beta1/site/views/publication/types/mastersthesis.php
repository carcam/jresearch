<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for masterthesis
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php $school = trim($this->publication->school);  ?>
<?php if(!empty($school)): ?>
	<dt><?php echo JText::_('JRESEARCH_SCHOOL').': ' ?></dt>		
	<dd><?php echo $school; ?></dd>
	<?php endif; ?>
	<?php $type = trim($this->publication->type); ?>
<?php if(!empty($type)): ?>
	<dt><?php echo JText::_('JRESEARCH_TYPE').': ' ?></dt>
	<dd><?php echo $type; ?></dd>
<?php endif; ?>
<?php $address = trim($this->publication->address);  ?>
<?php if(!empty($address)): ?>
	<dt><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></dt>		
	<dd><?php echo $address; ?></dd>
<?php endif; ?>
<?php $type = trim($this->publication->month); ?>
<?php if(!empty($month)): ?>
	<dt><?php echo JText::_('JRESEARCH_MONTH').': ' ?></dt>
	<dd><?php echo JResearchPublicationsHelper::formatMonth($month); ?></dd>
<?php endif; ?>