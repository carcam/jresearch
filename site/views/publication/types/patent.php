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
	<td style="width:15%;" class="publicationlabel"><?=JText::_('JRESEARCH_PATENT_NUMBER').': ' ?></td>		
	<td style="width:35%;"><?=$patent_number; ?></td>
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
	<td style="width:15%;" class="publicationlabel"><?=JText::_('JRESEARCH_FILING_DATE').': ' ?></td>		
	<td style="width:35%;"><?php echo $filing_date; ?></td>
	<?php endif; ?>
	<?php $issue_date = trim($this->publication->issue_date); ?>
	<?php if(!empty($issue_date)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?=JText::_('JRESEARCH_ISSUE_DATE').': ' ?></td>
	<td style="width:35%;"><?=$issue_date; ?></td>
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
	<td style="width:15%;" class="publicationlabel"><?=JText::_('JRESEARCH_CLAIMS').': ' ?></td>		
	<td style="width:35%;"><?=$claims; ?></td>
	<?php endif; ?>
	<td colspan="<?php echo $colspan; ?>"></td>	
</tr>
<?php echo isset($this->reference)?$this->reference:''; ?>