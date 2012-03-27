<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $colspan = 4; ?>
	<?php $journal = trim($this->publication->journal);  ?>
	<?php if(!empty($journal)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $journal; ?></div>
	<?php endif; ?>
	<?php $volume = trim($this->publication->volume); ?>
	<?php if(!empty($volume)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></div>
	<div class="divTdl"><?php echo $volume; ?></div>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<span></span>
	<?php endif; ?>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<?php $colspan = 4; ?>
	<?php $number = trim($this->publication->number);  ?>
	<?php if(!empty($number)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $number; ?></div>
	<?php endif; ?>
	<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
	<?php if(!empty($pages)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></div>
	<div class="divTdl"><?php echo $pages ?></div>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<span></span>	
	<?php endif; ?>
	<?php endif; ?>
	<div class="divEspacio"></div>
</div>
<div class="divTR">
	<?php $colspan = 4; ?>
	<?php $month = trim($this->publication->month);  ?>
	<?php if(!empty($month)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>		
	<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<?php $issn = trim($this->publication->issn);  ?>
<?php if(!empty($issn)): ?>
<div class="divTR">
	<div class="divTd"><?php echo JText::_('JRESEARCH_ISSN').': ' ?></div>		
	<div class="divTdl"><?php echo JResearchPublicationsHelper::formatISSN($issn); ?></div>
	<div class="divEspacio"></div>	
</div>
<?php endif; ?>
<?php echo isset($this->reference)?$this->reference:''; ?>