<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for electronic articles
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<?php $colspan = 4; ?>
	<?php $journal = trim($this->publication->journal);  ?>
	<?php if(!empty($journal)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></td>		
	<td style="width:35%;"><?php echo $journal; ?></td>
	<?php endif; ?>
	<?php $volume = trim($this->publication->volume); ?>
	<?php if(!empty($volume)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></td>
	<td style="width:35%;"><?php echo $volume; ?></td>
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
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></td>		
	<td style="width:35%;"><?php echo $number; ?></td>
	<?php endif; ?>
	<?php $access_date = trim($this->publication->access_date); ?>
	<?php if(!empty($access_date) && $access_date != '0000-00-00'): ?>
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