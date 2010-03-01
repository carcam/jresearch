<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<?php $colspan = 4; ?>
	<?php $journal = trim($this->publication->getJournal());  ?>
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
	<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
	<?php if(!empty($pages)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_PAGES').': ' ?></td>
	<td style="width:35%;"><?php echo $pages ?></td>
	<?php endif; ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $month = trim($this->publication->month);  ?>
	<?php if(!empty($month)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>		
	<td style="width:35%;"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></td>
	<?php endif; ?>
	<?php $recava_ack = trim($this->publication->recava_ack); ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_OTHER_RECAVA_ACK').': ' ?></td>		
	<td style="width:35%;"><?php echo $recava_ack == 1?JText::_('Yes'):JText::_('No'); ?></td>
	<?php $colspan -= 2; ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
</tr>
	<?php $colspan = 4; ?>
	<?php $other_recava_groups = trim($this->publication->other_recava_groups);  ?>
	<?php if($other_recava_groups == 1): 
			$entries = explode(';', $this->publication->recava_groups);
	?>	
	<tr>		
	<td colspan="4">
		<div class="publicationlabel"><?php echo JText::_('JRESEARCH_RECAVA_GROUPS'); ?></div>
		<table style="text-align:center;width:70%;margin-left:auto;margin-right:auto;">
			<thead>
				<tr>
					<th style="width:30%;border:1px solid;"><?php echo JText::_('JRESEARCH_GROUP');?></th>
					<th style="width:70%;border:1px solid;"><?php echo JText::_('JRESEARCH_AUThOR');?></th>				
				</tr>				
			</thead>
			<tbody>
				<?php foreach($entries as $row): 
						$components = explode(',', $row);
				?>
				<tr>
					<td style="border:1px solid;"><?php echo $components[0]; ?></td>
					<td style="border:1px solid;"><?php echo $components[1]; ?></td>
				</tr>
				<?php endforeach;?>				
			</tbody>
		</table>
	</td>
	</tr>	
	<?php endif; ?>
	
	<?php $used_recava_platforms = trim($this->publication->used_recava_platforms);  ?>
	<?php if($used_recava_platforms == 1): ?>	
	<tr>
		<td colspan="4">
			<div class="publicationlabel"><?php echo JText::_('JRESEARCH_RECAVA_PLATFORMS'); ?></div>
			<table style="text-align:center;width:50%;margin-left:auto;margin-right:auto;">
				<thead>
				<tr>
					<th style="width:10%;border:1px solid;"><?php echo JText::_('No.');?></th>
					<th style="width:90%;border:1px solid;"><?php echo JText::_('JRESEARCH_PLATFORM_NAME');?></th>				
				</tr>					
				</thead>
				<tbody>				
				<?php 
					$j = 1;
					$entries = explode(';', $this->publication->recava_platforms);
					foreach($entries as $row): ?>
					<tr>
						<td style="border:1px solid;"><?php echo $j?></td>
						<td style="border:1px solid;"><?php echo $row; ?></td>
					</tr>
					<?php $j++; ?>
					<?php endforeach; ?>					
				</tbody>
			</table>				
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td colspan="4">
			<div class="publicationlabel"><?php echo JText::_('JRESEARCH_OTHER_LINES'); ?></div>
			<ul>
			<?php 			
			$linesArray = explode(';', $this->publication->secondary_lines);
			foreach($linesArray as $line){
				$lineparts = explode('=', $line);
				if($lineparts[1] == 1){
			?>
			<li><?php echo JText::_('JRESEARCH_'.$lineparts[0]);?></li>				
				
			<?php }
			}	
		?>
			</ul>
		</td>
	</tr>
<tr>
</tr>