<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for incollection
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<?php $colspan = 4; ?>
	<?php $booktitle = trim($this->publication->booktitle);  ?>
	<?php if(!empty($journal)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_BOOK_TITLE').': ' ?></th>		
	<td><?php echo $booktitle; ?></td>
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
	<?php $editor = trim($this->publication->editor);  ?>
	<?php if(!empty($editor)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></th>		
	<td><?php echo $editor; ?></td>
	<?php endif; ?>
	<?php $organization = trim($this->publication->organization); ?>
	<?php if(!empty($organization)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></th>
	<td><?php echo $organization; ?></td>
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
<tr>
	<?php $colspan = 4; ?>
	<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
	<?php if(!empty($pages)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></th>		
	<td><?php echo $pages; ?></td>
	<?php endif; ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
</tr>
<?php $isbn = trim($this->publication->isbn);  ?>
<?php if(!empty($isbn)): ?>
<tr>
	<th scope="row"><?php echo JText::_('JRESEARCH_ISBN').': ' ?></th>		
	<td><?php echo $isbn; ?></td>
	<td colspan="2"></td>	
</tr>
<?php endif; ?>
<?php echo isset($this->reference)?$this->reference:''; ?>