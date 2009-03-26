<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single conference
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<td><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></td>
	<td><input name="editor" id="editor" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->editor:'' ?>" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS'))?></td>
	<td><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></td>
	<td><input name="volume" id="volume" type="text" size="30" maxlength="30" value="<?php echo $this->publication?$this->publication->volume:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_BOOKTITLE').': '?></td>
	<td><input name="booktitle" id="booktitle" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->booktitle:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></td>
	<td><input name="number" id="number" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_SERIES').': ' ?></td>		
	<td><input name="series" id="series" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->series:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_PAGES').': ' ?></td>
	<td><input name="pages" id="pages" type="text" size="10" maxlength="20" value="<?php echo $this->publication?$this->publication->pages:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></td>
	<td><input type="text" name="address" id="address" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>
	<td><input type="text" name="month" id="number" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('Publisher').': ' ?></td>		
	<td><input name="publisher" id="publisher" type="text" size="30" maxlength="60" value="<?php echo $this->publication?$this->publication->publisher:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></td>		
	<td><input name="organization" id="organization" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->organization:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_CROSS_REFERENCE').': ' ?></td>		
	<td><input name="crossref" id="crossref" type="text" size="30" maxlength="60" value="<?php echo $this->publication?$this->publication->crossref:'' ?>" /></td>
	<td></td>
	<td></td>
</tr>