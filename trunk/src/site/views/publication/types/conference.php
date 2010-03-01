<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for conference publication
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
	<td style="width:35%;"><?php echo $volume ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $booktitle = trim($this->publication->booktitle);  ?>
	<?php if(!empty($booktitle)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_BOOKTITLE').': ' ?></th>		
	<td style="width:35%;"><?php echo $booktitle; ?></td>
	<?php endif; ?>
	<?php $number = trim($this->publication->number); ?>
	<?php if(!empty($number)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></th>
	<td style="width:35%;"><?php echo $number ?></td>
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
	<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
	<?php if(!empty($pages)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></th>
	<td style="width:35%;"><?php echo $pages ?></td>
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
	<?php $publisher = trim($this->publication->publisher); ?>
	<?php if(!empty($month)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('Publisher').': ' ?></th>
	<td><?php echo $publisher ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>

<tr>
	<?php $colspan = 4; ?>
	<?php $organization = trim($this->publication->organization);  ?>
	<?php if(!empty($organization)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></th>		
	<td><?php echo $organization; ?></td>
	<?php endif; ?>
	<?php $month = trim($this->publication->month); ?>
	<?php if(!empty($month)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>
	<td><?php echo JResearchPublicationsHelper::formatMonth($month); ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $isbn = trim($this->publication->isbn);  ?>
	<?php if(!empty($isbn)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ISBN').': ' ?></th>		
	<td><?php echo $isbn; ?></td>
	<?php endif; ?>
	<?php $issn = trim($this->publication->issn); ?>
	<?php if(!empty($issn)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ISSN').': ' ?></th>
	<td><?php echo JResearchPublicationsHelper::formatISSN($issn); ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<?php echo isset($this->reference)?$this->reference:''; ?>