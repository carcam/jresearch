<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single patent publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<th><?=JText::_('JRESEARCH_PATENT_NUMBER').': ' ?></th>		
	<td colspan="3"><input name="patent_number" id="patent_number" type="text" size="10" maxlength="10" value="<?=$this->publication?$this->publication->patent_number:'' ?>" /></td>
	<!--
		DRAWINGS DIR ISN'T IMPLEMENTED
		<td><?=JText::_('JRESEARCH_DRAWINGS_DIR').': ' ?></td>
		<td><input name="drawings_dir" id="drawings_dir" type="text" size="30" maxlength="255" value="<?=$this->publication?$this->publication->drawings_dir:'' ?>" /></td>
	-->
</tr>
<tr>
	<th><?=JText::_('JRESEARCH_FILING_DATE').': ' ?></th>
	<?php $filDate = $this->publication?$this->publication->filing_date:''; ?>
	<td>
		<?=JHTML::_('calendar', $filDate ,'filing_date', 'filing_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
		<label for="filing_date" class="labelform"><?=JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
	</td>
	<th><?=JText::_('JRESEARCH_ISSUE_DATE').': ' ?></th>
	<?php $issueDate = $this->publication?$this->publication->issue_date:''; ?>
	<td>
		<?=JHTML::_('calendar', $issueDate ,'issue_date', 'issue_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
		<label for="issue_date" class="labelform"><?=JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
	</td>
</tr>
<tr>
	<th><?=JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td>
		<input name="address" id="address" type="text" size="30" maxlength="255" value="<?= $this->publication?$this->publication->address:''; ?>" />
	</td>
	<th><?=JText::_('JRESEARCH_PATENT_OFFICE').': ' ?></th>
	<td>
		<input name="office" id="office" type="text" size="30" maxlength="255" value="<?= $this->publication?$this->publication->office:''; ?>" />
	</td>
</tr>
<tr>
	<th><?=JText::_('JRESEARCH_CLAIMS').': ' ?></th>
	<td colspan="3">
		<textarea cols="30" rows="5" name="claims" id="claims"><?=$this->publication?$this->publication->claims:''?></textarea>
	</td>
</tr>