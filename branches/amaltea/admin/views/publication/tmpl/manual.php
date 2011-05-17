<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single manual
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="divTR">
	<div class="divTd"><label for="organization"><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></label></div>		
	<div class="divTdl divTdl2"><input name="organization" id="organization" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->organization:'' ?>" /></div>
	<div class="divTd"><label for="address"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></label></div>
	<div class="divTdl"><input type="text" name="address" id="address" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->address:'' ?>" /></div>
	<div class="divEspacio" ></div>		
</div>
<div class="divTR">
	<div class="divTd"><label for="edition"><?php echo JText::_('JRESEARCH_EDITION').': ' ?></label></div>		
	<div class="divTdl divTdl2"><input name="edition" id="edition" type="text" size="15" maxlength="10" value="<?php echo isset($this->publication)?$this->publication->edition:'' ?>" /></div>
</div>