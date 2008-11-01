<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data" class="form-validate" onSubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="2"><?php echo JText::_('JRESEARCH_REQUIRED')?></th>
	</tr>
	<tr>
		<td><?=JText::_('Member').': '?></td>
		<td>
			<?=$this->memberList?>
		</td>
	</tr>
	<tr>
		<td><?=JText::_('JRESEARCH_MDM_MONTH').': ' ?></td>
		<?php $month = ($this->mdm) ? $this->mdm->month : ''; ?>
		<td>
			<?=JHTML::_('calendar', $month ,'month', 'month', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="month" class="labelform"><?=JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label> 
		</td>
	</tr>
	<tr>
		<th colspan="2"><?php echo JText::_('JRESEARCH_OPTIONAL')?></th>
	</tr>
	<tr>
		<td colspan="2"><?=$this->editor->display('description', $this->mdm->description , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="mdm" />
<input type="hidden" name="id" value="<?=($this->mdm)?($this->mdm->id):''?>" />		
<?php echo JHTML::_('behavior.keepalive'); ?>
</form>