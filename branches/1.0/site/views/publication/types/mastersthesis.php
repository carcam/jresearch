<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for masterthesis
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<?php $colspan = 4; ?>
	<?php $school = trim($this->publication->school);  ?>
	<?php if(!empty($school)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_SCHOOL').': ' ?></td>		
	<td style="width:35%;"><?php echo $school; ?></td>
	<?php endif; ?>
	<?php $type = trim($this->publication->type); ?>
	<?php if(!empty($type)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></td>
	<td style="width:35%;"><?php echo $type; ?></td>
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
	<?php $type = trim($this->publication->month); ?>
	<?php if(!empty($type)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>
	<td style="width:35%;"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>