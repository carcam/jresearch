<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for unpublished publication
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<?php $colspan = 4; ?>
	<?php $month = trim($this->publication->month);  ?>
	<?php if(!empty($month)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>		
	<td><?php echo JResearchPublicationsHelper::formatMonth($month); ?></td>
	<?php endif; ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
</tr>
