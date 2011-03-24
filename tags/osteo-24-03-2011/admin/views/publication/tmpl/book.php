<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single book
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<div class="divTd">
		<label for="isbn"><?php echo JText::_('JRESEARCH_ISBN').': '?></label>
	</div>
	<div class="divTdl divTdl2">
		<input type="text" name="isbn" id="isbn" size="20" maxlength="32" class="validate-isbn" value="<?php echo isset($this->publication)?$this->publication->isbn:''; ?>" />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'isbn', JText::_('JRESEARCH_PROVIDE_VALID_ISBN')); ?>				
	</div>
	<div class="divTd"><label for="publisher"><?php echo JText::_('Publisher').': ' ?></label></div>
	<div class="divTdl"><input name="publisher" id="publisher" type="text" size="15" maxlength="60" value="<?php echo isset($this->publication)?$this->publication->publisher:'' ?>" /></div>	
	<div class="divEspacio" ></div>	
</div>