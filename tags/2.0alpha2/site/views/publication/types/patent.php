<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for patent
 * @todo IMPLEMENT DRAWINGS DIR
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<?php $patent_number = trim($this->publication->patent_number);  ?>
	<?php if(!empty($patent_number)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_PATENT_NUMBER').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $patent_number; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $filing_date = trim($this->publication->filing_date);  ?>
	<?php if(!empty($filing_date)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_FILING_DATE').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $filing_date; ?></div>
	<?php endif; ?>
	<?php $issue_date = trim($this->publication->issue_date); ?>
	<?php if(!empty($issue_date)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ISSUE_DATE').': ' ?></div>
	<div class="divTdl"><?php echo $issue_date; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $filing_date = trim($this->publication->filing_date);  ?>
	<?php if(!empty($filing_date)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_FILING_DATE').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $filing_date; ?></div>
	<?php endif; ?>
	<?php $issue_date = trim($this->publication->issue_date); ?>
	<?php if(!empty($issue_date)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ISSUE_DATE').': ' ?></div>
	<div class="divTdl"><?php echo $issue_date; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $country = trim($this->publication->address);  ?>
	<?php if(!empty($address)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $address; ?></div>
	<?php endif; ?>
	<?php $office = trim($this->publication->office); ?>
	<?php if(!empty($office)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_PATENT_OFFICE').': ' ?></div>
	<div class="divTdl"><?php echo $office; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<?php $claims = trim($this->publication->claims);  ?>
	<?php if(!empty($claims)): ?>
	<div class="divTd"><?php echo JText::_('JRESEARCH_CLAIMS').': ' ?></div>		
	<div class="divTdl divTdl2"><?php echo $claims; ?></div>
	<?php endif; ?>
	<div class="divEspacio"></div>	
</div>
<?php echo isset($this->reference)?$this->reference:''; ?>