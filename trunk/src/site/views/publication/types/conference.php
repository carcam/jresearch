<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for conference publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $editor = trim($this->publication->editor);  ?>
	<?php if(!empty($editor)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $editor; ?></div>
	<?php endif; ?>
	<?php $volume = trim($this->publication->volume); ?>
	<?php if(!empty($volume)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></div>
	<div class="divTdl"><?php echo $volume ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $booktitle = trim($this->publication->booktitle);  ?>
	<?php if(!empty($booktitle)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_BOOKTITLE').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $booktitle; ?></div>
	<?php endif; ?>
	<?php $number = trim($this->publication->number); ?>
	<?php if(!empty($number)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></div>
	<div class="divTdl divTdl2"><?php echo $number ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $series = trim($this->publication->series);  ?>
	<?php if(!empty($series)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_SERIES').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $series; ?></div>
	<?php endif; ?>
	<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
	<?php if(!empty($pages)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></div>
	<div class="divTdl"><?php echo $pages ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $address = trim($this->publication->address);  ?>
	<?php if(!empty($address)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $address; ?></div>
	<?php endif; ?>
	<?php $publisher = trim($this->publication->publisher); ?>
	<?php if(!empty($month)): ?>
	<div class="divTd"><?php echo JText::_('Publisher').': ' ?></div>
	<div class="divTdl"><?php echo $publisher ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $organization = trim($this->publication->organization);  ?>
	<?php if(!empty($organization)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $organization; ?></div>
	<?php endif; ?>
	<?php $month = trim($this->publication->month); ?>
	<?php if(!empty($month)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>
	<div class="divTdl"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $isbn = trim($this->publication->isbn);  ?>
	<?php if(!empty($isbn)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ISBN').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $isbn; ?></div>
	<?php endif; ?>
	<?php $issn = trim($this->publication->issn); ?>
	<?php if(!empty($issn)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ISSN').': ' ?></div>
	<div><?php echo JResearchPublicationsHelper::formatISSN($issn); ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<?php echo isset($this->reference)?$this->reference:''; ?>