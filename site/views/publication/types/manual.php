<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for manual
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<?php $colspan = 4; ?>
	<?php $organization = trim($this->publication->organization);  ?>
	<?php if(!empty($organization)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></td>		
	<td style="width:35%;"><?php echo $organization; ?></td>
	<?php endif; ?>
	<?php $address = trim($this->publication->address); ?>
	<?php if(!empty($address)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></td>
	<td style="width:35%;"><?php echo $address; ?></td>
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
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_EDITION').': ' ?></td>		
	<td style="width:35%;"><?php echo $edition; ?></td>
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

