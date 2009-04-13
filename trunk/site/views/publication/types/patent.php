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
	<th scope="row"><?php echo JText::_('JRESEARCH_PATENT_NUMBER').': ' ?></th>		
	<td style="width:35%;"><?php echo $patent_number; ?></td>
	<?php endif; ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?=$colspan; ?>"></td>	
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $filing_date = trim($this->publication->filing_date);  ?>
	<?php if(!empty($filing_date)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_FILING_DATE').': ' ?></th>		
	<td><?php echo $filing_date; ?></td>
	<?php endif; ?>
	<?php $issue_date = trim($this->publication->issue_date); ?>
	<?php if(!empty($issue_date)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ISSUE_DATE').': ' ?></th>
	<td><?php echo $issue_date; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $filing_date = trim($this->publication->filing_date);  ?>
	<?php if(!empty($filing_date)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_FILING_DATE').': ' ?></th>		
	<td><?php echo $filing_date; ?></td>
	<?php endif; ?>
	<?php $issue_date = trim($this->publication->issue_date); ?>
	<?php if(!empty($issue_date)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ISSUE_DATE').': ' ?></th>
	<td><?php echo $issue_date; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $country = trim($this->publication->address);  ?>
	<?php if(!empty($address)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>		
	<td><?php echo $address; ?></td>
	<?php endif; ?>
	<?php $office = trim($this->publication->office); ?>
	<?php if(!empty($office)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_PATENT_OFFICE').': ' ?></th>
	<td><?php echo $office; ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
<tr>
	<?php $colspan = 4; ?>
	<?php $claims = trim($this->publication->claims);  ?>
	<?php if(!empty($claims)): ?>
	<?php $colspan -= 2; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_CLAIMS').': ' ?></th>		
	<td><?php echo $claims; ?></td>
	<?php endif; ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
</tr>
<?php echo isset($this->reference)?$this->reference:''; ?>