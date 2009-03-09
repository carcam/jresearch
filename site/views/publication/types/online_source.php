<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for online sources
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<?php $colspan = 4; ?>
	<?php $source_type = trim($this->publication->source_type);  ?>
	<?php if(!empty($source_type)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></td>		
	<td style="width:35%;"><?php echo JText::_('JRESEARCH_'.strtoupper($source_type)); ?></td>
	<?php endif; ?>
	<?php $access_date = trim($this->publication->access_date); ?>
	<?php if(!empty($access_date)): ?>
	<?php $colspan -= 2; ?>
	<?php $accessArr = explode(' ', $access_date); ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_ACCESS_DATE').': ' ?></td>
	<td style="width:35%;"><?php echo $accessArr[0]; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $month = trim($this->publication->month);  
		  $day = trim($this->publication->day);
	?>
	<?php if(!empty($month)): ?>
		<?php if(empty($day)): ?>
			<?php $colspan -= 2; ?>
			<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>		
			<td style="width:35%;"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></td>
		<?php else: ?>
			<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_DATE').': ' ?></td>		
			<td style="width:35%;"><?php echo JResearchPublicationsHelper::formatMonth($month).', '.$day; ?></td>		
		<?php endif; ?>
	<?php endif; ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
</tr>