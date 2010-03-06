<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

//Published options
$publishedOptions = array();
$publishedOptions[] = JHTML::_('select.option', '1', JText::_('JRESEARCH_YES'));    	
$publishedOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_NO'));   
?>

<tr>
	<td><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></td>
	<td>
		<?php echo $this->journals; ?>
	</td>
	<td><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></td>
	<td><input name="volume" id="volume" type="text" size="20" maxlength="30" value="<?php echo $this->publication?$this->publication->volume:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></td>
	<td><input name="number" id="number" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_PAGES').': ' ?></td>
	<td><input name="pages" id="pages" type="text" size="10" maxlength="20" value="<?php echo $this->publication?$this->publication->pages:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_OTHER_RECAVA_ACK').': ' ?></td>
	<td><?php echo JHTML::_('select.genericlist', $publishedOptions ,'recava_ack', 'class="inputbox"' ,'value', 'text' , $this->publication?$this->publication->recava_ack:0);?></td>
	<td><?php echo JText::_('JRESEARCH_USED_RECAVA_PLATFORMS').': ' ?></td>
	<td><?php echo JHTML::_('select.genericlist', $publishedOptions ,'used_recava_platforms', 'class="inputbox" onchange="javascript:switchControl(\'div_recava_platforms\');"' ,'value', 'text' , $this->publication?$this->publication->used_recava_platforms:0);?></td>		
</tr>
<tr>
	<td style="vertical-align:top;padding:0px;" colspan="2"><?php echo JHTML::_('AuthorsSelector.recavagroups', 'recava_groups', $this->publication?$this->publication->recava_groups:'' , $this->publication?$this->publication->other_recava_groups:0); ?></td>
	<td style="vertical-align:top;padding:0px;" colspan="2"><?php echo JHTML::_('AuthorsSelector.recavaplatforms', 'recava_platforms' , $this->publication?$this->publication->recava_platforms:'', $this->publication?$this->publication->used_recava_platforms:0);?></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_OTHER_LINES').': '; ?></td>
	<td colspan="3"><?php echo JHTML::_('AuthorsSelector.recavaotherlines', 'secondary_lines', $this->publication?$this->publication->secondary_lines:"C1=0;C2=0;C3=0;C4=0;C5=0;C6=0;C7=0;C8=0;C9=0;C10=0;C11=0"); ?></td>	
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_PRIORITY_LINE').': ' ?></td>
 	<td>
 	<input type="text" name="priority_line" id="priority_line" size="40" maxlength="255" value="<?php echo $this->publication?$this->publication->priority_line:'' ?>" />
 	<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS')) ?>
 	</td>
	<td colspan="2"></td>
</tr>