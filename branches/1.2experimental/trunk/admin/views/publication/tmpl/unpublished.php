<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single unpublished publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<th><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>
	<td colspan="3"><input type="text" name="month" id="number" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
</tr>
