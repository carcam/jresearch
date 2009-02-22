<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<th><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></th>
	<td><input name="journal" id="journal" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->journal:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></th>
	<td><input name="volume" id="volume" type="text" size="30" maxlength="30" value="<?php echo $this->publication?$this->publication->volume:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></th>
	<td><input name="number" id="number" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_PAGES').': ' ?></th>
	<td><input name="pages" id="pages" type="text" size="10" maxlength="20" value="<?php echo $this->publication?$this->publication->pages:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>
	<td><input type="text" name="month" id="month" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_CROSS_REFERENCE').': ' ?></th>
	<td><input type="text" name="crossref" id="crossref" size="20" maxlength="255" value="<?php echo $this->publication?$this->publication->crossref:''; ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_ISSN').': ' ?></th>
	<td colspan="3">
		<input type="text" name="issn" id="issn" size="20" maxlength="32" class="validate-issn" value="<?php echo $this->publication?$this->publication->issn:''; ?>" />
		<br />
		<label for="issn" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_ISSN'); ?></label>
	</td>
</tr>
