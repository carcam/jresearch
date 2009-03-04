<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

if(!empty($this->publication)){
	$type = JHTML::_('jresearchhtml.onlineresourcelist', array('selected'=> $this->publication->type, 'name'=> 'source_type'));
}else{
	$type = JHTML::_('jresearchhtml.onlineresourcelist', array('name'=>'source_type'));
}
?>

<tr>
	<th><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></th>
	<td><?php echo $type; ?></td>
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