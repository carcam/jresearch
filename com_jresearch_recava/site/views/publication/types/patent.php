<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for patent
 * @todo IMPLEMENT DRAWINGS DIR
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<?php $colspan = 4; ?>
	<?php $patent_number = trim($this->publication->patent_number);  ?>
	<?php if(!empty($patent_number)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_PATENT_NUMBER').': ' ?></td>		
	<td style="width:35%;"><?php echo $patent_number; ?></td>
	<?php endif; ?>
	<?php $authors = $this->publication->getInventors(); 
		  $n = count($authors); 
		  if($n > 0):
		  	$colspan -= 2;	
	?>	
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_INVENTORS').': ' ?></td>	
		<?php if($this->staff_list_arrangement == 'horizontal'): ?>
		<td>
				<?php 
					  $i = 0; 
					 ?>
				<?php foreach($authors as $auth): ?>
						<?php if($auth instanceof JResearchMember): ?>
							<?php if($auth->published): ?>
								<a href="index.php?option=com_jresearch&amp;view=member&amp;task=show<?php echo $ItemidText ?>&amp;id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a>
								<?php echo $i == $n - 1?'':',' ?>
							<?php else: ?>
								<?php echo $auth->__toString(); ?><?php echo $i == $n - 1?'':',' ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo $auth; ?>
								<?php echo $i == $n - 1?'':',' ?>
						<?php endif; ?>
						<?php $i++; ?>
				<?php endforeach; ?>
		</td>		
		<?php else: ?>
		<td style="width:35%;">
			<ul style="margin:0px;padding:0px;">
				<?php foreach($authors as $auth): ?>
					<li style="list-style:none;">
						<?php if($auth instanceof JResearchMember): ?>
							<?php if($auth->published): ?>
								<a href="index.php?option=com_jresearch&amp;view=member&amp;task=show<?php echo $ItemidText ?>&amp;id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a>
							<?php else: ?>
								<?php echo $auth->__toString(); ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo $auth; ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</td>
		<?php endif; ?>	
	<?php endif; ?>	
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $filing_date = trim($this->publication->filing_date);  ?>
	<?php if(!empty($filing_date)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_FILING_DATE').': ' ?></td>		
	<td style="width:35%;"><?php echo $filing_date; ?></td>
	<?php endif; ?>
	<?php $country = trim($this->publication->country); ?>
	<?php if(!empty($country)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_PATENT_COUNTRY').': ' ?></td>
	<td style="width:35%;"><?php echo $country; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $titular_entity = trim($this->publication->titular_entity);  ?>
	<?php if(!empty($titular_entity)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_TITULAR_ENTITY').': ' ?></td>		
	<td style="width:35%;"><?php echo $titular_entity; ?></td>
	<?php endif; ?>
	<?php $extended_countries = trim($this->publication->extended_countries); ?>
	<?php if(!empty($extended_countries)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_EXTENDED_TO_COUNTRIES').': ' ?></td>
	<td style="width:35%;"><?php echo $extended_countries; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $in_explotation = trim($this->publication->in_explotation);  ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_PATENT_IN_EXPLOTATION').': ' ?></td>		
	<td style="width:35%;"><?php echo $in_explotation? JText::_('JRESEARCH_YES') : JText::_('JRESEARCH_NO'); ?></td>
	<td colspan="2"></td>	
</tr>