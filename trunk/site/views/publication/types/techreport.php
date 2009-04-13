<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for a techreport
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<?php $colspan = 4; ?>
	<?php $address = trim($this->publication->address);  ?>
	<?php if(!empty($address)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>		
	<td><?php echo $address; ?></td>
	<?php endif; ?>
	<?php $institution = trim($this->publication->institution); ?>
	<?php if(!empty($institution)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_INSTITUTION').': ' ?></th>
	<td><?php echo $institution; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $number = trim($this->publication->number);  ?>
	<?php if(!empty($number)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></th>		
	<td><?php echo $number; ?></td>
	<?php endif; ?>
	<?php $type = trim($this->publication->type); ?>
	<?php if(!empty($type)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></th>
	<td><?php echo $type; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>

<tr>
	<?php $colspan = 4; ?>
	<?php $month = trim($this->publication->month);  ?>
	<?php if(!empty($number)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('Month of publication').': ' ?></th>		
	<td><?php echo JResearchPublicationsHelper::formatMonth($month); ?></td>
	<?php endif; ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
</tr>