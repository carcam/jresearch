<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single booklet
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<th><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></th>		
	<td><input name="howpublished" id="howpublished" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->howpublished:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td><input name="address" id="address" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>
	<td colspan="3"><input type="text" name="month" id="number" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
</tr>
