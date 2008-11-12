<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data" class="form-validate" onSubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_REQUIRED')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('Title').': '?></td>
		<td colspan="3">
			<input name="title" id="title" size="80" maxlength="255" class="required" value="<?php echo $this->thesis?$this->thesis->title:'' ?>" />
			<br />
			<label for="title" class="labelform"><?php echo JText::_('JRESEARCH_THESIS_PROVIDE_VALID_TITLE'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>		
		<td><?php echo $this->areasList; ?></td>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_STATUS').' :' ?></td>
		<td><?php echo $this->status; ?></td>
		<td><?php echo JText::_('JRESEARCH_DEGREE').' :' ?></td>
		<td><?php echo $this->degree; ?></td>
	</tr>	
	<tr>
		<td><?php echo JText::_('JRESEARCH_STUDENTS').': '; ?></td>
		<td><?php echo $this->studentsControl; ?></td>
		<td><?php echo JText::_('JRESEARCH_DIRECTORS').': '; ?></td>
		<td><?php echo $this->directorsControl; ?></td>
	</tr>
	<tr>
		<th class="editpublication" colspan="4"><?php echo JText::_('JRESEARCH_OPTIONAL'); ?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></td>
		<?php $startDate = $this->thesis?$this->thesis->start_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $startDate ,'start_date', 'start_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="start_date" class="labelform"><?php echo JText::_('Please provide a valid date in format YYYY-MM-DD'); ?></label> 
		</td>
		<td><?php echo JText::_('JRESEARCH_DEADLINE').': ' ?></td>
		<?php $endDate = $this->thesis?$this->thesis->end_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $endDate ,'end_date', 'end_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="end_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
		</td>
	</tr>
	
	
	<tr>
		<td><?php echo JText::_('JRESEARCH_DIGITAL_VERSION').' (Url) : ' ?></td>
		<td colspan="3">
			<input name="url" class="validate-url" id="url" size="30" maxlength="255" value="<?php echo $this->thesis?$this->thesis->url:'' ?>" />
			<br />
			<label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>	
		</td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->thesis->description , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="theses" />
<input type="hidden" name="id" value="<?php echo $this->thesis?$this->thesis->id:'' ?>" />		
<?php echo JHTML::_('behavior.keepalive'); ?>
</form>
