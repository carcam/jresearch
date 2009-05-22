<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for adding/editing a single project
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" action="./" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<table class="edit" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th class="title" colspan="4"><?php echo JText::_('JRESEARCH_REQUIRED')?></th>
	</tr>
	<tr>
		<th><?php echo JText::_('Title').': '?></th>
		<td colspan="3">
			<input name="title" id="title" size="80" maxlength="255" value="<?php echo $this->project?$this->project->title:'' ?>" class="required" />
			<br />
			<label for="title" class="labelform"><?php echo JText::_('JRESEARCH_PROJECT_PROVIDE_VALID_TITLE'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></th>		
		<td><?php echo $this->areasList; ?></td>
		<th><?php echo JText::_('JRESEARCH_PROJECT_STATUS').' :' ?></th>
		<td><?php echo $this->status; ?></td>
	</tr>
	<tr>
		<th><?php echo JText::_('Published').': '; ?></th>
		<td><?php echo $this->publishedRadio; ?></td>
		<th><?php echo JText::_('JRESEARCH_MEMBERS').': '; ?></th>
		<td><?php echo $this->membersControl; ?></td>
	</tr>
	<tr>
		<th class="title" colspan="4"><?php echo JText::_('JRESEARCH_OPTIONAL'); ?></th>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></th>
		<?php $startDate = $this->project?$this->project->start_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $startDate ,'start_date', 'start_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="start_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label> 
		</td>
		<th><?php echo JText::_('JRESEARCH_DEADLINE').': ' ?></th>
		<?php $endDate = $this->project?$this->project->end_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $endDate ,'end_date', 'end_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="end_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_FUNDED_BY').': '; ?></th>
		<td><?php echo $this->finList; ?></td>
		<th><?php echo JText::_('JRESEARCH_FINANCE_LEVEL').': '; ?></th>
		<td><input name="finance_value" id="finance_value" size="12" maxlength="12" value="<?php echo $this->project?$this->project->finance_value:'' ?>" /> <?php echo $this->currencyList; ?></td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_COOPERATION_WITH'); ?></th>
		<td><?php echo $this->coopList; ?></td>
		<th><?php echo JText::_('JRESEARCH_PROJECT_PAGE').' (Url) : ' ?></th>
		<td>
			<input name="url" id="url" class="validate-url" size="30" maxlength="255" value="<?php echo $this->project?$this->project->url:'' ?>" />
			<br />
			<label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_PROJECT_IMAGE').': '; ?></th>
		<td>
			<input type="file" name="inputfile" id="inputfile" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', 400, 400)); ?><br />
		</td>
		<?php
		if($this->project->url_project_image):
			$url = JResearch::getUrlByRelative($this->project->url_project_image);
			$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->project->url_project_image):$url;
		?>
		<td>
			<a href="<?php echo $url;?>" class="modal">
				<img src="<?php echo $thumb; ?>" alt="<?php echo JText::_('JRESEARCH_NO_PHOTO'); ?>" />
			</a>
			<input type="hidden" name="url_project_image" value="<?php echo $this->project->url_project_image; ?>" />
		</td>
		<td>
			<label for="delete" /><?php echo JText::_('Delete current photo'); ?></label><input type="checkbox" name="delete" id="delete" />
		</td>
		<?php 
		else:
		?>
		<td colspan="2"></td>
		<?php
		endif;
		?>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_FILES').': '; ?></th>
		<td colspan="2"><?php echo $this->files; ?></td>
		<?php if(!empty($this->project)): ?>
		<th><?php echo JText::_('Hits').': '?></th>
		<td><?php echo $this->project->hits;  ?><div><label for="resethits"><?php echo JText::_('Reset').': '; ?></label><input type="checkbox" name="resethits" id="resethits" /></div></td>
		<?php else: ?>
		<td></td>
		<td></td>	
		<?php endif; ?>

	</tr>
	<tr>
		<th colspan="4" align="left"><?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?></th>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->project->description , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>

<input type="hidden" name="id" value="<?php echo $this->project?$this->project->id:'' ?>" />		
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'projects'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
</form>
