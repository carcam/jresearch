<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for adding/editing a cooperation
*/
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data" class="form-validate" onSubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_TEAM')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_TEAM_NAME').': '?></td>
		<td>
			<input name="name" id="name" size="50" maxlength="100" class="required" value="<?php echo $this->team?$this->team->name:'' ?>" />
			<br /><label for="name" class="labelform"><?php echo JText::_('Please provide a name. Alphabetic characters plus _- and spaces are allowed.'); ?></label>
		</td>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_TEAM_LEADER').': '; ?></td>
		<td><?=$this->leaderList; ?></td>
		<td><?php echo JText::_('JRESEARCH_TEAM_MEMBERS').': '; ?></td>
		<td><?=$this->memberList; ?></td>
	</tr>
	<tr>
		<td colspan="4"><?=$this->editor->display( 'description',  $this->team->description , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="teams" />
<input type="hidden" name="id" value="<?php echo $this->team?$this->team->id:'' ?>" />	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>		
</form>