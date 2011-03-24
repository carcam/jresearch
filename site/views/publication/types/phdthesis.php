<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for phdthesis
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<?php $colspan = 4; ?>
	<?php $school = trim($this->publication->school);  ?>
	<?php if(!empty($school)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_SCHOOL').': ' ?></th>		
	<td><?php echo $school; ?></td>
	<?php endif; ?>
	<?php $type = trim($this->publication->type); ?>
	<?php if(!empty($type)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_DEGREE').': ' ?></th>
	<td><?php echo $type; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $address = trim($this->publication->address);  ?>
	<?php if(!empty($address)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>		
	<td><?php echo $address; ?></td>
	<?php endif; ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
</tr>