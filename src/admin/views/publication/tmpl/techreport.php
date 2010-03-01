<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single techreport
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>


<div class="divTR">
	<div class="divTd"><label for="address"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="address" id="address" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->address:'' ?>" /></div>
	<div class="divTd"><label for="month"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></label></div>
	<div class="divTdl"><input type="text" name="month" id="number" size="15" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->month:'' ?>" /></div>
	<div class="divEspacio" ></div>		
</div>
<div class="divTR">
	<div class="divTd"><label for="number"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="number" id="number" type="text" size="15" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->number:'' ?>" /></div>
	<div class="divTd"><label for="type"><?php echo JText::_('JRESEARCH_FIELD_TYPE').': ' ?></label></div>
	<div class="divTdl"><input name="type" id="type" type="text" size="15" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->type:'' ?>" /></div>
	<div class="divEspacio" ></div>		
</div>
<div class="divTR">
    <div class="divTd"><label for="institution"><?php echo JText::_('JRESEARCH_INSTITUTION').': ' ?></label></div>
	<div class="divTdl"><input name="institution" id="institution" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->institution:'' ?>" /></div>
</div>