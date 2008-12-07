<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single patent publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<td><?=JText::_('JRESEARCH_PATENT_NUMBER').': ' ?></td>		
	<td><input name="patent_number" id="patent_number" type="text" size="10" maxlength="10" value="<?=$this->publication?$this->publication->patent_number:'' ?>" /></td>
	<td>&nbsp;</td>
	<td><input name="drawings_dir" id="drawings_dir" type="text" size="30" maxlength="255" value="<?=$this->publication?$this->publication->drawings_dir:'' ?>" /></td>
</tr>
<tr>
	<td><?=JText::_('JRESEARCH_FILING_DATE').': ' ?></td>
	<?php $filDate = $this->publication?$this->publication->filing_date:''; ?>
	<td>
		<?=JHTML::_('calendar', $filDate ,'filing_date', 'filing_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
		<label for="filing_date" class="labelform"><?=JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
	</td>
	<td><?=JText::_('JRESEARCH_ISSUE_DATE').': ' ?></td>
	<?php $issueDate = $this->publication?$this->publication->issue_date:''; ?>
	<td>
		<?=JHTML::_('calendar', $issueDate ,'issue_date', 'issue_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
		<label for="issue_date" class="labelform"><?=JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
	</td>
</tr>
<tr>
	<td><?=JText::_('JRESEARCH_CLAIMS').': ' ?></td>
	<td colspan="3">
		<textarea cols="30" rows="5" name="claims" id="claims"><?=$this->publication?$this->publication->claims:''?></textarea>
	</td>
</tr>