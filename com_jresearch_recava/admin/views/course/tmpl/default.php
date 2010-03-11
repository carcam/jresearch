<?php
/**
 * @package JResearch
 * @subpackage Courses
 * Default view for listing courses
 */
// no direct access
//@todo Add control for directors and groups
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
?>
<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data" class="form-validate" onSubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_COURSE')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_COURSE_TITLE').': '?></td>
		<td>
			<input name="title" id="title" size="50" maxlength="100" class="required" value="<?php echo $this->course?$this->course->title:'' ?>" />
			<br /><label for="title" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_TITLE'); ?></label>
		</td>
		<td><?php echo JText::_('JRESEARCH_COURSE_PLACE').': '; ?></td>
		<td>
			<input name="place" id="place" size="50" maxlength="255" class="required" value="<?php echo $this->course?$this->course->place:''; ?>" />
			<br /><label for="place" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_PLACE'); ?></label>			
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_COURSE_STARTDATE').': ' ?></td>
		<?php $startDate = $this->course?$this->course->start_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $startDate ,'start_date', 'start_date', '%Y-%m-%d', array('class'=>'validate-date required')); ?><br />
			<label for="start_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label> 
		</td>
		<td><?php echo JText::_('JRESEARCH_COURSE_ENDDATE').': ' ?></td>
		<?php $endDate = $this->course?$this->course->end_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $endDate ,'end_date', 'end_date', '%Y-%m-%d', array('class'=>'validate-date required')); ?><br />
			<label for="end_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_COURSE_DIRECTORS').': ' ?></td>
		<td><?php echo $this->membersControl; ?></td>
		<td><?php echo JText::_('JRESEARCH_COURSE_GROUPS').': ' ?></td>
		<td><?php echo $this->groupsControl; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
		<td><?php echo JText::_('JRESEARCH_COURSE_PARTICIPANTS').': ' ?></td>
		<td><?php echo $this->participantsSelect; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="courses" />
<input type="hidden" name="id" value="<?php echo $this->course?$this->course->id:'' ?>" />	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>		
</form>