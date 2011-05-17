<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="divTR">
	<div class="divTd"><label for="issn"><?php echo JText::_('JRESEARCH_ISSN').': ' ?></label></div>
	<div class="divTdl">
		<input type="text" name="issn" id="issn" size="20" maxlength="32" class="validate-issn" value="<?php echo isset($this->publication)?$this->publication->issn:''; ?>" />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'issn', JText::_('JRESEARCH_PROVIDE_VALID_ISSN')); ?>				
	</div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="journal"><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="journal" id="journal" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->journal:'' ?>" /></div>
	<div class="divTd"><label for="volume"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></label></div>
	<div class="divTdl"><input name="volume" id="volume" type="text" size="15" maxlength="30" value="<?php echo isset($this->publication)?$this->publication->volume:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="number"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="number" id="number" type="text" size="15" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->number:'' ?>" /></div>
	<div class="divTd"><label for="pages"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></label></div>
	<div class="divTdl"><input name="pages" id="pages" type="text" size="10" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->pages:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="crossref"><?php echo JText::_('JRESEARCH_CROSS_REFERENCE').': ' ?></label></div>
	<div class="divTdl"><input type="text" name="crossref" id="crossref" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->crossref:''; ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
