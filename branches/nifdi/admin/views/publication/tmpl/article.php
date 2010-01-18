<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<td><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></td>
	<td><input name="journal" id="journal" type="text" size="30" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->journal:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></td>
	<td><input name="volume" id="volume" type="text" size="30" maxlength="30" value="<?php echo isset($this->publication)?$this->publication->volume:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></td>
	<td><input name="number" id="number" type="text" size="20" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->number:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_PAGES').': ' ?></td>
	<td><input name="pages" id="pages" type="text" size="10" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->pages:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>
	<td><input type="text" name="month" id="month" size="20" maxlength="20" value="<?php echo isset($this->publication)?$this->publication->month:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_CROSS_REFERENCE').': ' ?></td>
	<td><input type="text" name="crossref" id="crossref" size="20" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->crossref:''; ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_DESIGN_TYPE').': ' ?></td>
	<td><input type="text" name="design_type" id="design_type" size="20" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->design_type:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_STUDENTS_INCLUDED').': ' ?></td>
	<td><input type="text" name="students_included" id="students_included" size="20" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->students_included:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_LOCATION').': ' ?></td>
	<td><input type="text" name="location" id="location" size="20" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->location:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_FIDELITY_DATA_COLLECTED').': ' ?></td>
	<?php
	    	//Published options
    		$fidelityOptions = array();
    		$fidelityOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    		$fidelityOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	
	  ?>
	  
	<td><?php echo JHTML::_('select.genericlist',  $fidelityOptions, 'fidelity_data_collected', 'class="inputbox" size="1"', 'value', 'text', isset($this->publication)?$this->publication->fidelity_data_collected:0); ?></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_OTHER_TAGS').': ' ?></td>
	<td><textarea name="other_tags" id="other_tags" cols="30" rows="5" ><?php echo isset($this->publication)?$this->publication->other_tags:'' ?></textarea></td>
	<td></td>
	<td></td>
</tr>