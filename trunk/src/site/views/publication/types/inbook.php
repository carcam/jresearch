<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for inbook
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $editor = trim($this->publication->editor);  ?>
	<?php if(!empty($editor)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></div>		
	<div class="divTdl"><?php echo $editor; ?></div>
	<?php endif; ?>
	<?php $volume = trim($this->publication->volume); ?>
	<?php if(!empty($volume)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></div>
	<div class="divTdl"><?php echo $volume; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<?php $colspan = 4; ?>
	<?php $chapter = trim($this->publication->chapter);  ?>
	<?php if(!empty($chapter)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_CHAPTER').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $chapter; ?></div>
	<?php endif; ?>
	<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
	<?php if(!empty($pages)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></div>
	<div class="divTdl"><?php echo $pages; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<?php $publisher = trim($this->publication->publisher);  ?>
	<?php if(!empty($publisher)): ?>
	<div class="divTd"><?php echo JText::_('Publisher').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $publisher; ?></div>
	<?php endif; ?>
	<?php $address = trim($this->publication->address); ?>
	<?php if(!empty($address)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></div>
	<div class="divTdl"><?php echo $address; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<?php $series = trim($this->publication->series);  ?>
	<?php if(!empty($series)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_SERIES').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $series; ?></div>
	<?php endif; ?>
	<?php $type = trim($this->publication->type); ?>
	<?php if(!empty($type)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_TYPE_F').': ' ?></div>
	<div class="divTdl"><?php echo $type; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<?php $number = trim($this->publication->number);  ?>
	<?php if(!empty($number)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $number; ?></div>
	<?php endif; ?>
	<?php $edition = trim($this->publication->edition); ?>
	<?php if(!empty($edition)): ?>
	<?php $colspan -= 2; ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_EDITION').': ' ?></div>
	<div class="divTdl"><?php echo $edition; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<?php $month = trim($this->publication->month);  ?>
	<?php if(!empty($month)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<?php $isbn = trim($this->publication->isbn);  ?>
<?php if(!empty($isbn)): ?>
<div class="divTR">
	<div class="divTd"><?php echo JText::_('JRESEARCH_ISBN').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $isbn; ?></div>
	<div class="divEspacio"></div>
</div>
<?php endif; ?>