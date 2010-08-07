<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single incollection
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="divTR">
	<div class="divTd"><label for="isbn"><?php echo JText::_('JRESEARCH_ISBN').': ' ?></label></div>
	<div class="divTdl divTdl2">		
		<input type="text" name="isbn" id="isbn" size="15" maxlength="32" class="validate-isbn" value="<?php echo isset($this->publication)?$this->publication->isbn:''; ?>" />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'isbn', JText::_('JRESEARCH_PROVIDE_VALID_ISBN')); ?>						
	</div>
	<div class="divTd"><label for="month"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></label></div>
	<div class="divTdl"><input type="text" name="month" id="number" size="15" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->month:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="booktitle"><?php echo JText::_('JRESEARCH_BOOKTITLE').': ' ?></label></div>		
	<div class="divTdl divTdl2"><input name="booktitle" id="booktitle" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->booktitle:'' ?>" /></div>
	<div class="divTd"><label for="publisher"><?php echo JText::_('Publisher').': ' ?></label></div>		
	<div class="divTdl"><input name="publisher" id="publisher" type="text" size="15" maxlength="60" value="<?php echo isset($this->publication)?$this->publication->publisher:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="editor"><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></label></div>		
	<div class="divTdl divTdl2"><input name="editor" id="editor" type="text" size="12" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->editor:'' ?>" /><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS'))?></div>
	<div class="divTd"><label for="organization"><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></label></div>		
	<div class="divTdl"><input name="organization" id="organization" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->organization:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="address"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="address" id="address" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->address:'' ?>" /></div>
	<div class="divTd"><label for="pages"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></label></div>
	<div class="divTdl"><input name="pages" id="pages" type="text" size="10" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->pages:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd">
	<label for="key"><?php echo JText::_('JRESEARCH_KEY').': ' ?></label></div>		
	<div class="divTdl divTdl2"><input name="key" id="key" type="text" size="12" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->key:'' ?>" /><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_KEY_TOOLTIP'));  ?></div>
	<div class="divTd"><label for="crossref"><?php echo JText::_('JRESEARCH_CROSS_REFERENCE').': ' ?></label></div>
	<div class="divTdl"><input type="text" name="crossref" id="crossref" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->crossref:''; ?>" /></div>
</div>