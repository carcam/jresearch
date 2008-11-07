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
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_BOOK_TITLE').': ' ?></td>		
	<td style="width:35%;"><?php echo $booktitle; ?></td>
	<?php endif; ?>
	<?php $publisher = trim($this->publication->publisher); ?>
	<?php if(!empty($publisher)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('Publisher').': ' ?></td>
	<td style="width:35%;"><?php echo $publisher; ?></td>
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
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></td>		
	<td style="width:35%;"><?php echo $editor; ?></td>
	<?php endif; ?>
	<?php $organization = trim($this->publication->organization); ?>
	<?php if(!empty($organization)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></td>
	<td style="width:35%;"><?php echo $organization; ?></td>
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
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></td>		
	<td style="width:35%;"><?php echo $address; ?></td>
	<?php endif; ?>
	<?php $month = trim($this->publication->month); ?>
	<?php if(!empty($month)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>
	<td style="width:35%;"><?php echo $month; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
	<?php if(!empty($pages)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></td>		
	<td style="width:35%;"><?php echo $pages; ?></td>
	<?php endif; ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
</tr>

<?php echo isset($this->reference)?$this->reference:''; ?>