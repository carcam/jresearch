<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single inbook
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<div class="divTd"><label for="month"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="month" id="month" type="text" size="15" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->month:'' ?>" /></div>
	<div class="divTd"><label for="isbn"><?php echo JText::_('JRESEARCH_ISBN').': ' ?></label></div>
	<div class="divTdl">		
            <input type="text" name="isbn" id="isbn" size="20" maxlength="32" value="<?php echo isset($this->publication)?$this->publication->isbn:''; ?>" />
        </div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="editor"><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="editor" id="editor" type="text" size="12" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->editor:'' ?>" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS'))?></div>
	<div class="divTd"><label for="volume"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></label></div>
	<div class="divTdl"><input name="volume" id="volume" type="text" size="15" maxlength="30" value="<?php echo isset($this->publication)?$this->publication->volume:'' ?>" /></div>
	<div class="divEspacio" ></div>		
</div>
<div class="divTR">
	<div class="divTd"><label for="chapter"><?php echo JText::_('JRESEARCH_CHAPTER').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="chapter" id="chapter" type="text" size="15" maxlength="10" value="<?php echo isset($this->publication)?$this->publication->chapter:'' ?>" /></div>
	<div class="divTd"><label for="pages"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></label></div>
	<div class="divTdl"><input name="pages" id="pages" type="text" size="15" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->pages:'' ?>" /></div>
	<div class="divEspacio" ></div>		
</div>
<div class="divTR">
	<div class="divTd"><label for="publisher"><?php echo JText::_('Publisher').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="publisher" id="publisher" type="text" size="15" maxlength="60" value="<?php echo isset($this->publication)?$this->publication->publisher:'' ?>" /></div>
	<div class="divTd"><label for="number"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></label></div>
	<div class="divTdl"><input name="number" id="number" type="text" size="10" maxlength="10" value="<?php echo isset($this->publication)?$this->publication->number:'' ?>" /></div>
	<div class="divEspacio" ></div>		
</div>
<div class="divTR">
	<div class="divTd"><label for="series"><?php echo JText::_('JRESEARCH_SERIES').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="series" id="series" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->series:'' ?>" /></div>
	<div class="divTd"><label for="type"><?php echo JText::_('JRESEARCH_FIELD_TYPE').': ' ?></label></div>
	<div class="divTdl"><input name="type" id="type" type="text" size="15" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->type:'' ?>" /></div>
	<div class="divEspacio" ></div>		
</div>
<div class="divTR">
	<div class="divTd"><label for="address"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="address" id="address" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->address:'' ?>" /></div>
	<div class="divTd"><label for="edition"><?php echo JText::_('JRESEARCH_EDITION').': ' ?></label></div>
	<div class="divTdl"><input name="edition" id="edition" type="text" size="10" maxlength="10" value="<?php echo isset($this->publication)?$this->publication->edition:'' ?>" /></div>
	<div class="divEspacio" ></div>		
</div>