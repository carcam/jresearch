<?php/** * @package JResearch * @subpackage Publications * View for adding/editing a single phdthesis */// no direct accessdefined('_JEXEC') or die('Restricted access'); ?><tr>	<td><?php echo JText::_('JRESEARCH_SCHOOL').': ' ?></td>	<td><input type="text" name="school" id="school" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->school:'' ?>" /></td>		<td><?php echo JText::_('JRESEARCH_FIELD_TYPE').': ' ?></td>	<td><input name="type" id="type" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->type:'' ?>" /></td></tr><tr>	<td><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></td>	<td><input type="text" name="address" id="address" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>	<td><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>	<td><input type="text" name="month" id="number" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td></tr>