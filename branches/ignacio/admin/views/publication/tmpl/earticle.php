<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

?>

<tr>
	<th><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></th>
	<td><input name="journal" id="journal" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->journal:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></th>
	<td><input name="volume" id="volume" type="text" size="30" maxlength="30" value="<?php echo $this->publication?$this->publication->volume:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></th>
	<td><input name="number" id="number" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>
	<td><input type="text" name="month" id="month" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_DAY').': ' ?></th>
	<td><input name="day" id="day" type="text" size="2" maxlength="2" value="<?php echo $this->publication?$this->publication->day:'' ?>" /></td>
	<th><?php echo JText::_('JRESEARCH_ACCESS_DATE').': ' ?></th>
	<td><?php echo JHTML::_('calendar', !empty($this->publication)?$this->publication->access_date:'' ,'access_date', 'access_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
		<label for="access_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label></td>
</tr>