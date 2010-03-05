<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single patent publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<tr>
	<td><?php echo JText::_('JRESEARCH_PATENT_NUMBER').': ' ?></td>		
	<td colspan="3"><input name="patent_number" id="patent_number" type="text" size="10" maxlength="10" value="<?php echo $this->publication?$this->publication->patent_number:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_ISSUE_DATE').': ' ?></td>
	<?php $issueDate = $this->publication?$this->publication->issue_date:''; ?>
	<td>
		<?php echo JHTML::_('calendar', $issueDate ,'issue_date', 'issue_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
		<label for="issue_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
	</td>
	<td><?php echo JText::_('JRESEARCH_PATENT_COUNTRY').': '; ?></td>
	<td><input name="country" id="country" type="text" size="30" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->country:''; ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_TITULAR_ENTITY').': ' ?></td>
	<td>
		<input name="titular_entity" id="titular_entity" type="text" size="30" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->titular_entity:''; ?>" />
	</td>
	<td><?php echo JText::_('JRESEARCH_EXTENDED_TO_COUNTRIES').': ' ?></td>
	<td>
		<input name="extended_countries" id="extended_countries" type="text" size="30" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->extended_countries:''; ?>" />
	</td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_PATENT_IN_EXPLOTATION').': ' ?></td>
	<td>
	<?php 
	    //Published options
    	$booleanOptions = array();
    	$booleanOptions[] = JHTML::_('select.option', '1', JText::_('JRESEARCH_YES'));    	
    	$booleanOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_NO'));    	
	
		echo JHTML::_('select.genericlist', $booleanOptions , 'in_explotation', 'class="inputbox"' ,'value', 'text' , isset($this->publication) ? $this->publication->in_explotation : 0); ?>
	</td>
	<td colspan="2" />
</tr>