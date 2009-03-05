<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single incollection
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<th><?php echo JText::_('JRESEARCH_BOOKTITLE').': ' ?></th>		
	<td><input name="booktitle" id="booktitle" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->booktitle:'' ?>" /></td>
	<th><?php echo JText::_('Publisher').': ' ?></th>		
	<td><input name="publisher" id="publisher" type="text" size="30" maxlength="60" value="<?php echo $this->publication?$this->publication->publisher:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></th>		
	<td><input name="editor" id="editor" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->editor:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></th>		
	<td><input name="organization" id="organization" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->organization:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td><input name="address" id="address" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_PAGES').': ' ?></th>
	<td><input name="pages" id="pages" type="text" size="10" maxlength="20" value="<?php echo $this->publication?$this->publication->pages:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_EDITION').': ' ?></th>		
	<td><input name="edition" id="edition" type="text" size="10" maxlength="10" value="<?php echo $this->publication?$this->publication->edition:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>
	<td><input type="text" name="month" id="number" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_CROSS_REFERENCE').': ' ?></th>		
	<td><input name="crossref" id="crossref" type="text" size="30" maxlength="60" value="<?php echo $this->publication?$this->publication->crossref:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_KEY').': ' ?></th>		
	<td><input name="key" id="key" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->key:'' ?>" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_KEY_TOOLTIP'));  ?></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_ISBN').': ' ?></th>
	<td colspan="3">
		<input type="text" name="isbn" id="isbn" size="20" maxlength="32" class="validate-isbn" value="<?php echo $this->publication?$this->publication->isbn:''; ?>" />
		<br />
		<label for="isbn" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_ISBN'); ?></label>
	</td>
</tr>