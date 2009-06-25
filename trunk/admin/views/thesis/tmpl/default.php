<?php
/**
 * @package JResearch
 * @subpackage Theses
 * Default view for adding/editing a single theses
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th class="title" colspan="4"><?php echo JText::_('JRESEARCH_REQUIRED')?></th>
	</tr>
	<tr>
		<th><?php echo JText::_('Title').': '?></th>
		<td colspan="3">
			<input name="title" id="title" size="80" maxlength="255" class="required" value="<?php echo $this->thesis?$this->thesis->title:'' ?>" />
			<br />
			<label for="title" class="labelform"><?php echo JText::_('JRESEARCH_THESIS_PROVIDE_VALID_TITLE'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></th>		
		<td><?php echo $this->areasList; ?></td>
		<th><?php echo JText::_('Published').': '; ?></th>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_STATUS').' :' ?></th>
		<td><?php echo $this->status; ?></td>
		<th><?php echo JText::_('JRESEARCH_DEGREE').' :' ?></th>
		<td><?php echo $this->degree; ?></td>
	</tr>	
	<tr>
		<th><?php echo JText::_('JRESEARCH_STUDENTS').': '; ?></th>
		<td><?php echo $this->studentsControl; ?></td>
		<th><?php echo JText::_('JRESEARCH_DIRECTORS').': '; ?></th>
		<td><?php echo $this->directorsControl; ?></td>
	</tr>
	<tr>
		<th class="title" colspan="4"><?php echo JText::_('JRESEARCH_OPTIONAL'); ?></th>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></th>
		<?php $startDate = $this->thesis?$this->thesis->start_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $startDate ,'start_date', 'start_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="start_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label> 
		</td>
		<th><?php echo JText::_('JRESEARCH_DEADLINE').': ' ?></th>
		<?php $endDate = $this->thesis?$this->thesis->end_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $endDate ,'end_date', 'end_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="end_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
		</td>
	</tr>
	
	
	<tr>
		<th><?php echo JText::_('JRESEARCH_DIGITAL_VERSION').' (Url) : ' ?></th>
		<td>
			<input name="url" class="validate-url" id="url" size="30" maxlength="255" value="<?php echo $this->thesis?$this->thesis->url:'' ?>" />
			<br />
			<label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>	
		</td>
		<td><?php echo JText::_('JRESEARCH_FILES').': '; ?></td>
		<td><?php echo $this->files; ?></td>
	</tr>
	<?php if(!empty($this->thesis)): ?>
	<tr>
		<th><?php echo JText::_('Hits').': '?></th>
		<td><?php echo $this->thesis->hits;  ?><div><label for="resethits"><?php echo JText::_('Reset').': '; ?></label><input type="checkbox" name="resethits" id="resethits" /></div></td>
		<td></td>
		<td></td>
	</tr>
	<?php endif; ?>
	
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  isset($this->thesis)?$this->thesis->description:'' , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>

<input type="hidden" name="id" value="<?php echo $this->thesis?$this->thesis->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'theses'); ?>	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
