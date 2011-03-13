<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single booklet
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="divTR">
	<div class="divTd"><label for="howpublished"><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></label></div>		
	<div class="divTdl divTdl2"><input name="howpublished" id="howpublished" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->howpublished:'' ?>" /></div>
	<div class="divTd"><label for="address"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></label></div>
	<div class="divTdl"><input name="address" id="address" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->address:'' ?>" /></div>
	<div class="divEspacio" ></div>		
</div>