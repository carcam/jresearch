<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for digital sources
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<?php $colspan = 4; ?>
	<?php $source_type = trim($this->publication->source_type);  ?>
	<?php if(!empty($source_type)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></th>		
	<td><?php echo JText::_('JRESEARCH_'.strtoupper($source_type)); ?></td>
	<?php endif; ?>
	<?php $publisher = trim($this->publication->publisher); ?>
	<?php if(!empty($publisher)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('Publisher').': ' ?></th>		
	<td><?php echo $publisher; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $address = trim($this->publication->address); ?>
	<?php if(!empty($address)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td><?php echo $address ?></td>
	<?php endif; ?>
	<td colspan="<?php echo $colspan; ?>"></td>
</tr>