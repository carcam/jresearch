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
	<th scope="row"><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></th>		
	<td><?php echo JText::_('JRESEARCH_'.strtoupper($source_type)); ?></td>
	<?php endif; ?>
	<?php $access_date = trim($this->publication->access_date); ?>
	<?php if(!empty($access_date) && $access_date != '0000-00-00'): ?>
	<?php $colspan -= 2; ?>
	<?php $accessArr = explode(' ', $access_date); ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ACCESS_DATE').': ' ?></th>
	<td><?php echo $accessArr[0]; ?></td>
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
			<th scope="row"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>		
			<td><?php echo JResearchPublicationsHelper::formatMonth($month); ?></td>
		<?php else: ?>
			<th scope="row"><?php echo JText::_('JRESEARCH_DATE').': ' ?></th>		
			<td><?php echo JResearchPublicationsHelper::formatMonth($month).', '.$day; ?></td>		
		<?php endif; ?>
	<?php endif; ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
</tr>