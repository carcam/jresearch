<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for misc. publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<?php $colspan = 4; ?>
	<?php $howpublished = trim($this->publication->howpublished);  ?>
	<?php if(!empty($howpublished)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></td>		
	<td style="width:35%;"><?php echo $howpublished; ?></td>
	<?php endif; ?>
	<?php $month = trim($this->publication->month); ?>
	<?php if(!empty($month)): ?>
	<?php $colspan -= 2; ?>
	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>
	<td style="width:35%;"><?php echo JResearchPublicationsHelper::formatMonth($month); ?></td>
	<?php else: ?>
	<?php if($colspan > 0): ?>
	<td colspan="<?php echo $month; ?>"></td>	
	<?php endif; ?>
	<?php endif; ?>
</tr>
