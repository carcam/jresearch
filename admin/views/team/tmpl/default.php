<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for adding/editing a cooperation
*/
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" action="./" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<table class="edit" cellpadding="5" cellspacing="5">
<thead>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_TEAM')?></th>
	</tr>
</thead>
<tbody>
	<tr>
		<th><?php echo JText::_('JRESEARCH_TEAM_NAME').': '?></th>
		<td>
			<input name="name" id="name" size="50" maxlength="100" class="required" value="<?php echo $this->team?$this->team->name:'' ?>" />
			<br /><label for="name" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_NAME'); ?></label>
		</td>
		<th><?php echo JText::_('Published').': '; ?></th>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_TEAM_LEADER').': '; ?></th>
		<td><?php echo $this->leaderList; ?></td>
		<th><?php echo JText::_('JRESEARCH_TEAM_MEMBERS').': '; ?></th>
		<td><?php echo $this->memberList; ?></td>
	</tr>
	<tr>
		<th>
		<?php echo JText::_('Parent'); ?>
		</th>
		<td colspan="3">
			<?php echo JHTML::_('jresearchhtml.teamshierarchy', $this->hierarchy, array('name' => 'parent', 'selected' => $this->team?$this->team->parent:null)); ?>
		</td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->team?$this->team->description:'' , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>

<input type="hidden" name="id" value="<?php echo $this->team?$this->team->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'teams'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>