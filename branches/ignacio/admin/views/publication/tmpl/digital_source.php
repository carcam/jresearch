<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

if(!empty($this->publication)){
	$type = JHTML::_('jresearchhtml.digitalresourcelist', array('selected'=> $this->publication->type, 'name'=> 'source_type'));
}else{
	$type = JHTML::_('jresearchhtml.digitalresourcelist', array('name'=>'source_type'));
}
?>
<tr>
	<th><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></th>
	<td><?php echo $type; ?></td>
	<th><?php echo JText::_('Publisher').': ' ?></th>
	<td><input name="publisher" id="publisher" type="text" size="30" maxlength="60" value="<?php echo $this->publication?$this->publication->publisher:'' ?>" /></td>
</tr>
<tr>
	<th><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></th>
	<td><input type="text" name="address" id="address" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->address:'' ?>" /></td>
	<td></td>
	<td></td>
</tr>