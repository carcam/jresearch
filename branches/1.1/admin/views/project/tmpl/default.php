<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for adding/editing a single project
 */
// no direct access
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
			<input name="title" id="title" size="80" maxlength="255" value="<?php echo $this->project?$this->project->title:'' ?>" class="required" />
			<br />
			<label for="title" class="labelform"><?php echo JText::_('JRESEARCH_PROJECT_PROVIDE_VALID_TITLE'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>		
		<td><?php echo $this->areasList; ?></td>
		<td><?php echo JText::_('JRESEARCH_PROJECT_STATUS').' :' ?></td>
		<td><?php echo $this->status; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
		<td><?php echo JText::_('JRESEARCH_MEMBERS').': '; ?></td>
		<td><?php echo $this->membersControl; ?></td>
	</tr>
	<tr>
		<th class="editpublication" colspan="4"><?php echo JText::_('JRESEARCH_OPTIONAL'); ?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></td>
		<?php $startDate = $this->project?$this->project->start_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $startDate ,'start_date', 'start_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="start_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label> 
		</td>
		<td><?php echo JText::_('JRESEARCH_DEADLINE').': ' ?></td>
		<?php $endDate = $this->project?$this->project->end_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $endDate ,'end_date', 'end_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="end_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_FUNDED_BY').': '; ?></td>
		<td><?php echo $this->finList; ?></td>
		<td><?php echo JText::_('JRESEARCH_FINANCE_LEVEL').': '; ?></td>
		<td><input name="finance_value" id="finance_value" size="12" maxlength="12" value="<?php echo $this->project?$this->project->finance_value:'' ?>" /> <?php echo $this->currencyList; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_PROJECT_PAGE').' (Url) : ' ?></td>
		<td>
			<input name="url" id="url" class="validate-url" size="30" maxlength="255" value="<?php echo $this->project?$this->project->url:'' ?>" />
			<br />
			<label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>
		</td>
		<td><?php echo JText::_('JRESEARCH_PROJECT_IMAGE').': '; ?></td>
		<td>
			<input type="file" name="inputfile" id="inputfile" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', 400, 400)); ?><br />
			<label for="delete" /><?php echo JText::_('Delete current photo'); ?></label><input type="checkbox" name="delete" id="delete" />
		</td>		
	</tr>
	<tr>
		<td colspan="3" align="left"><?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?></td>
		<td>
			<?php if(isset($this->project)): ?>
				<img src="<?php echo $this->project->getURLLogo(); ?>" alt="<?php echo JText::_('No photo'); ?>" />
			<?php endif; ?>
			<input type="hidden" name="url_project_image" value="<?php echo isset($this->project)?$this->project->url_project_image:''; ?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->project?$this->project->description:'' , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="projects" />
<input type="hidden" name="id" value="<?php echo $this->project?$this->project->id:'' ?>" />		
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>