<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for book
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<?php $colspan = 4; ?>
	<?php $publisher = trim($this->publication->publisher);  ?>
	<?php if(!empty($publisher)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('Publisher').': ' ?></th>		
	<td><?php echo $publisher; ?></td>
	<?php endif; ?>
	<?php $editor = trim($this->publication->editor); ?>
	<?php if(!empty($editor)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></th>
	<td><?php echo $editor ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>

<tr>
	<?php $colspan = 4; ?>
	<?php $volume = trim($this->publication->volume);  ?>
	<?php if(!empty($volume)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></th>		
	<td><?php echo $volume; ?></td>
	<?php endif; ?>
	<?php $number = trim($this->publication->number); ?>
	<?php if(!empty($number)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></th>
	<td><?php echo $number ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $series = trim($this->publication->series);  ?>
	<?php if(!empty($series)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_SERIES').': ' ?></th>		
	<td><?php echo $series; ?></td>
	<?php endif; ?>
	<?php $address = trim($this->publication->address); ?>
	<?php if(!empty($address)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td><?php echo $address ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $edition = trim($this->publication->edition);  ?>
	<?php if(!empty($edition)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_EDITION').': ' ?></th>		
	<td><?php echo $edition; ?></td>
	<?php endif; ?>
	<?php $isbn = trim($this->publication->isbn); ?>
	<?php if(!empty($isbn)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ISBN').': ' ?></th>
	<td><?php echo $isbn; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>