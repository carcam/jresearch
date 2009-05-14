<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single masterthesis
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<th><?php echo JText::_('JRESEARCH_SCHOOL').': ' ?></th>
	<td><input type="text" name="school" id="school" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->school:'' ?>" /></td>	
	<th><?php echo JText::_('JRESEARCH_FIELD_TYPE').': ' ?></th>
	<td><input name="type" id="type" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->type:'' ?>" /></td>

</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td><input type="text" name="address" id="address" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>
	<td><input type="text" name="month" id="number" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
</tr>