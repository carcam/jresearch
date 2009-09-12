<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for misc. publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<?php $colspan = 4; ?>
	<?php $howpublished = trim($this->publication->howpublished);  ?>
	<?php if(!empty($howpublished)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></th>		
	<td><?php echo $howpublished; ?></td>
	<?php endif; ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
</tr>
