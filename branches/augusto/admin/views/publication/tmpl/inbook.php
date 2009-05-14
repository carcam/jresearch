<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single inbook
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<th><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></th>
	<td><input name="editor" id="editor" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->editor:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></th>
	<td><input name="volume" id="volume" type="text" size="30" maxlength="30" value="<?php echo $this->publication?$this->publication->volume:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_CHAPTER').': ' ?></th>
	<td><input name="chapter" id="chapter" type="text" size="10" maxlength="10" value="<?php echo $this->publication?$this->publication->chapter:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_PAGES').': ' ?></th>
	<td><input name="pages" id="pages" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->pages:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('Publisher').': ' ?></th>
	<td><input name="publisher" id="publisher" type="text" size="30" maxlength="60" value="<?php echo $this->publication?$this->publication->publisher:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></th>
	<td><input name="number" id="number" type="text" size="10" maxlength="10" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_SERIES').': ' ?></th>
	<td><input name="series" id="series" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->series:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_FIELD_TYPE').': ' ?></th>
	<td><input name="type" id="type" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->type:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td><input name="address" id="address" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_EDITION').': ' ?></th>
	<td><input name="edition" id="edition" type="text" size="10" maxlength="10" value="<?php echo $this->publication?$this->publication->edition:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>
	<td><input name="month" id="month" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_ISBN').': ' ?></th>
	<td>
		<input type="text" name="isbn" id="isbn" size="20" maxlength="32" class="validate-isbn" value="<?php echo $this->publication?$this->publication->isbn:''; ?>" />
		<br />
		<label for="isbn" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_ISBN'); ?></label>
	</td>
</tr>