<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single techreport
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>


<tr>
	<th><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td><input name="address" id="address" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>
	<td><input type="text" name="month" id="number" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></th>
	<td><input name="number" id="number" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_FIELD_TYPE').': ' ?></th>
	<td><input name="type" id="type" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->type:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_INSTITUTION').': ' ?></th>
	<td colspan="3"><input name="institution" id="institution" type="text" size="20" maxlength="255" value="<?php echo $this->publication?$this->publication->institution:'' ?>" /></td>
</tr>