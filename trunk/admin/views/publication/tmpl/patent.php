<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single patent publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<div class="divTd"><label for="filing_date"><?=JText::_('JRESEARCH_FILING_DATE').': ' ?></label></div>
	<?php $filDate = $this->publication?$this->publication->filing_date:''; ?>
	<div class="divTdl divTdl2">
		<?php echo JHTML::_('calendar', $filDate ,'filing_date', 'filing_date', '%Y-%m-%d', array('class'=>'validate-date',  'size'=>'10')); ?><br />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'filing_date', JText::_('JRESEARCH_PROVIDE_VALID_DATE')); ?>				
	</div>
	<div class="divTd"><label for="issue_date"><?=JText::_('JRESEARCH_ISSUE_DATE').': ' ?></label></div>
	<?php $issueDate = $this->publication?$this->publication->issue_date:''; ?>
	<div class="divTdl">
		<?php echo JHTML::_('calendar', $issueDate ,'issue_date', 'issue_date', '%Y-%m-%d', array('class'=>'validate-date', 'size'=>'10')); ?><br />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'issue_date', JText::_('JRESEARCH_PROVIDE_VALID_DATE')); ?>				
	</div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="address"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></label></div>
	<div class="divTdl divTdl2">
		<input name="address" id="address" type="text" size="15" maxlength="255" value="<?= $this->publication?$this->publication->address:''; ?>" />
	</div>
	<div class="divTd"><label for="office"><?php echo JText::_('JRESEARCH_PATENT_OFFICE').': ' ?></label></div>
	<div class="divTdl">
		<input name="office" id="office" type="text" size="15" maxlength="255" value="<?= $this->publication?$this->publication->office:''; ?>" />
	</div>
	<div class="divEspacio" ></div>
</div>
<div class="divTR">
	<div class="divTd"><label for="claims"><?php echo JText::_('JRESEARCH_CLAIMS').': ' ?></label></div>
	<div class="divTdl">
		<textarea cols="50" rows="4" name="claims" id="claims"><?php echo $this->publication?$this->publication->claims:''?></textarea>
	</div>
</div>