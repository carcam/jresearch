<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single proceeding
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="divTR">
	<div class="divTd"><label for="isbn"><?php echo JText::_('JRESEARCH_ISBN').': ' ?></label></div>
	<div class="divTdl divTdl2">
		<input type="text" name="isbn" id="isbn" size="15" maxlength="32" class="validate-isbn" value="<?php echo $this->publication?$this->publication->isbn:''; ?>" />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'isbn', JText::_('JRESEARCH_PROVIDE_VALID_ISBN')); ?>				
	</div>
	<div class="divTd"><?php echo JText::_('JRESEARCH_ISSN').': ' ?></div>
	<div class="divTdl">
		<input type="text" name="issn" id="issn" size="15" maxlength="32" class="validate-issn" value="<?php echo $this->publication?$this->publication->issn:''; ?>" />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'issn', JText::_('JRESEARCH_PROVIDE_VALID_ISSN')); ?>				
	</div>
	<div class="divEspacio" ></div>		
</div>
<div class="divTR">
	<div class="divTd"><label for="editor"><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="editor" id="editor" type="text" size="12" maxlength="255" value="<?php echo $this->publication?$this->publication->editor:'' ?>" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS'))?></div>
	<div class="divTd"><label for="volume"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></label></div>		
	<div class="divTdl"><input name="volume" id="volume" type="text" size="15" maxlength="30" value="<?php echo $this->publication?$this->publication->volume:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="number"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="number" id="number" type="text" size="15" maxlength="20" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></div>
	<div class="divTd"><label for="series"><?php echo JText::_('JRESEARCH_SERIES').': ' ?></label></div>		
	<div class="divTdl"><input name="series" id="series" type="text" size="15" maxlength="255" value="<?php echo $this->publication?$this->publication->series:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="address"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="address" id="address" type="text" size="15" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></div>
	<div class="divTd"><label for="publisher"><?php echo JText::_('Publisher').': ' ?></label></div>		
	<div class="divTdl"><input name="publisher" id="publisher" type="text" size="15" maxlength="60" value="<?php echo $this->publication?$this->publication->publisher:'' ?>" /></div>	
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="organization"><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></label></div>		
	<div class="divTdl divTdl2"><input name="organization" id="organization" type="text" size="15" maxlength="255" value="<?php echo $this->publication?$this->publication->organization:'' ?>" /></div>
	<div class="divTd"><label for="month"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></label></div>
	<div class="divTdl"><input type="text" name="month" id="month" size="15" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></div>
</div>