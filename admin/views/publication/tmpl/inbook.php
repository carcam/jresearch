<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<td><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></td>
	<td><input name="editor" id="editor" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->editor:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></td>
	<td><input name="volume" id="volume" type="text" size="30" maxlength="30" value="<?php echo $this->publication?$this->publication->volume:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_CHAPTER').': ' ?></td>
	<td><input name="chapter" id="chapter" type="text" size="10" maxlength="10" value="<?php echo $this->publication?$this->publication->chapter:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_PAGES').': ' ?></td>
	<td><input name="pages" id="pages" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->pages:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('Publisher').': ' ?></td>
	<td><input name="publisher" id="publisher" type="text" size="30" maxlength="60" value="<?php echo $this->publication?$this->publication->publisher:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></td>
	<td><input name="number" id="number" type="text" size="10" maxlength="10" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_SERIES').': ' ?></td>
	<td><input name="series" id="series" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->series:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_FIELD_TYPE').': ' ?></td>
	<td><input name="type" id="type" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->type:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></td>
	<td><input name="address" id="address" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_EDITION').': ' ?></td>
	<td><input name="edition" id="edition" type="text" size="10" maxlength="10" value="<?php echo $this->publication?$this->publication->edition:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>
	<td><input name="month" id="month" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>