<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for inbook
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<?php $colspan = 4; ?>
	<?php $editor = trim($this->publication->editor);  ?>
	<?php if(!empty($editor)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></th>		
	<td style="width:35%;"><?php echo $editor; ?></td>
	<?php endif; ?>
	<?php $volume = trim($this->publication->volume); ?>
	<?php if(!empty($volume)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></th>
	<td style="width:35%;"><?php echo $volume; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $chapter = trim($this->publication->chapter);  ?>
	<?php if(!empty($chapter)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_CHAPTER').': ' ?></th>		
	<td style="width:35%;"><?php echo $chapter; ?></td>
	<?php endif; ?>
	<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
	<?php if(!empty($pages)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></th>
	<td style="width:35%;"><?php echo $pages; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $publisher = trim($this->publication->publisher);  ?>
	<?php if(!empty($publisher)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('Publisher').': ' ?></th>		
	<td style="width:35%;"><?php echo $publisher; ?></td>
	<?php endif; ?>
	<?php $address = trim($this->publication->address); ?>
	<?php if(!empty($address)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td style="width:35%;"><?php echo $address; ?></td>
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
	<td style="width:35%;"><?php echo $series; ?></td>
	<?php endif; ?>
	<?php $type = trim($this->publication->type); ?>
	<?php if(!empty($type)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_TYPE_F').': ' ?></th>
	<td><?php echo $type; ?></td>
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
	<?php $edition = trim($this->publication->edition); ?>
	<?php if(!empty($edition)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_EDITION').': ' ?></th>
	<td><?php echo $edition; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<?php $isbn = trim($this->publication->isbn);  ?>
<?php if(!empty($isbn)): ?>
<tr>
	<th scope="row"><?php echo JText::_('JRESEARCH_ISBN').': ' ?></th>		
	<td><?php echo $isbn; ?></td>
	<td colspan="2"></td>	
</tr>
<?php endif; ?>