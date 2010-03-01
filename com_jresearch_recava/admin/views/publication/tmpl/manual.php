<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single manual
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<td><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></td>		
	<td><input name="organization" id="organization" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->organization:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></td>
	<td><input type="text" name="address" id="address" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_EDITION').': ' ?></td>		
	<td><input name="edition" id="edition" type="text" size="10" maxlength="10" value="<?php echo $this->publication?$this->publication->edition:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>
	<td><input type="text" name="month" id="number" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
</tr>