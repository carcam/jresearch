<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single misc. publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<td><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></td>		
	<td><input name="howpublished" id="howpublished" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->howpublished:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>
	<td><input type="text" name="month" id="number" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
</tr>