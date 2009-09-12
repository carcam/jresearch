<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for booklet
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<?php $colspan = 4; ?>
	<?php $howpublished = trim($this->publication->howpublished);  ?>
	<?php if(!empty($howpublished)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></th>		
	<td style="width:35%;"><?php echo $howpublished; ?></td>
	<?php endif; ?>
	<?php $address = trim($this->publication->address); ?>
	<?php if(!empty($address)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td style="width:35%;"><?php echo $address ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>