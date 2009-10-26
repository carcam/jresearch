<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for adding/editing a single project
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divForm">
<form name="adminForm" id="adminForm" action="./" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">

<fieldset><legend><?php echo JText::_('JRESEARCH_REQUIRED')?></legend>
<div class="divTable">	
	<div class="divTR">
		<div class="divTd"><label for="title"><?php echo JText::_('Title').': '?></label></div>
		<div class="divTdl">
			<input name="title" id="title" size="50" maxlength="255" value="<?php echo $this->project?$this->project->title:'' ?>" class="required" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'title', JText::_('JRESEARCH_PROJECT_PROVIDE_VALID_TITLE')); ?>		
		</div>
	    <div class="divEspacio" ></div>						
	</div>
	<div class="divTR">	
		<div class="divTd">
			<label for="alias"><?php echo JText::_('Alias').': '?></label>
		</div>
		<div class="divTdl">
			<input name="alias" id="alias" size="50" maxlength="255" class="validate-alias" value="<?php echo $this->project?$this->project->alias:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'alias', JText::_('JRESEARCH_PROVIDE_VALID_ALIAS')); ?>							
		</div>
	    <div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd"><label for="id_research_area"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></label></div>		
		<div class="divTdl divTdl2"><?php echo $this->areasList; ?></div>
		<div class="divTd"><label for="status"><?php echo JText::_('JRESEARCH_PROJECT_STATUS').' :' ?></label></div>
		<div class="divTdl"><?php echo $this->status; ?></div>
	    <div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('Published').': '; ?></div>
		<div class="divTdl"><?php echo $this->publishedRadio; ?></div>
	</div>
</div>	
</fieldset>	
<fieldset>
<legend><?php echo JText::_('JRESEARCH_MEMBERS'); ?></legend>
<div class="divTable">
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('JRESEARCH_MEMBERS').': '; ?></div>
		<div class="divTdl"><?php echo $this->membersControl; ?></div>
	</div>
</div>
</fieldset>	
<fieldset>
<legend><?php echo JText::_('JRESEARCH_OPTIONAL');?></legend>
<div class="divTable">
	<div class="divTR">
		<div class="divTd"><label for="start_date"><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></label></div>
			<?php $startDate = $this->project?$this->project->start_date:''; ?>		
		<div class="divTdl divTdl2">
			<?php echo JHTML::_('calendar', $startDate ,'start_date', 'start_date', '%Y-%m-%d', array('class'=>'validate-date', 'size'=>'15')); ?>
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'start_date', JText::_('JRESEARCH_PROVIDE_VALID_DATE')); ?>						
		</div>
		<div class="divTd"><label for="end_date"><?php echo JText::_('JRESEARCH_DEADLINE').': ' ?></label></div>		
		<?php $endDate = $this->project?$this->project->end_date:''; ?>		
		<div class="divTdl">		 
			<?php echo JHTML::_('calendar', $endDate ,'end_date', 'end_date', '%Y-%m-%d', array('class'=>'validate-date', 'size'=>'15')); ?>
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'end_date', JText::_('JRESEARCH_PROVIDE_VALID_DATE')); ?>						
		</div>
	    <div class="divEspacio" ></div>				
	</div>
	<div class="divTR">
		<div class="divTd"><label for="id_financier"><?php echo JText::_('JRESEARCH_FUNDED_BY').': '; ?></label></div>
		<div class="divTdl divTdl2"><?php echo $this->finList; ?></div>
		<div class="divTd"><label for="financie_value"><?php echo JText::_('JRESEARCH_FINANCE_LEVEL').': '; ?></label></div>
		<div class="divTdl"><input name="finance_value" id="finance_value" size="8" maxlength="12" value="<?php echo $this->project?$this->project->finance_value:'' ?>" /> <?php echo $this->currencyList; ?></div>
	    <div class="divEspacio" ></div>						
	</div>
	<div class="divTR">
		<div class="divTd"><label for="id_cooperation"><?php echo JText::_('JRESEARCH_COOPERATION_WITH'); ?></label></div>
		<div class="divTdl divTdl2"><?php echo $this->coopList; ?></div>
		<div class="divTd"><label for="url"><?php echo JText::_('JRESEARCH_PROJECT_PAGE').' (Url) : ' ?></label></div>
		<div class="divTdl">		
			<input name="url" id="url" class="validate-url" size="20" maxlength="255" value="<?php echo $this->project?$this->project->url:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'url', JText::_('JRESEARCH_PROVIDE_VALID_URL')); ?>						
		</div>
		<div class="divEspacio" ></div>	
	</div>
	<div class="divTR">	
		<div class="divTd"><label for="inputfile"><?php echo JText::_('JRESEARCH_PROJECT_IMAGE').': '; ?></label></div>
		<div class="divTdl">
			<input type="file" name="inputfile" id="inputfile" /><?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', 400, 400)); ?>
		</div>
		<div class="divTdl">
			<?php
			if(isset($this->project)):
				if($this->project->url_project_image):
					$url = JResearch::getUrlByRelative($this->project->url_project_image);
					$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->project->url_project_image):$url;
			?>
				<a href="<?php echo $url;?>" class="modal">
					<img src="<?php echo $thumb; ?>" alt="<?php echo JText::_('JRESEARCH_NO_PHOTO'); ?>" />
				</a>
				<input type="hidden" name="url_project_image" value="<?php echo $this->project->url_project_image; ?>" />
	
				<label for="delete" /><?php echo JText::_('Delete current photo'); ?></label><input type="checkbox" name="delete" id="delete" />
				<?php endif; ?>
			<?php endif; ?>			
		</div>	
		<div class="divEspacio" ></div>		
	</div>	
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('JRESEARCH_FILES').': '; ?></div>
		<div class="divTdl"><?php echo $this->files; ?></div>
		<div class="divEspacio" ></div>	
	</div>
	<?php if(!empty($this->project)): ?>
		<div class="divTR">
			<div class="divTd"><?php echo JText::_('Hits').': '?></div>
			<div class="divTdl"><?php echo JHTML::_('jresearchhtml.hitsControl', 'resethits', $this->project->hits); ?></div>
			<div class="divEspacio" ></div>			
		</div>			
	<?php endif; ?>
	<div class="divTR">
		<label for="description"><?php echo JText::_('JRESEARCH_DESCRIPTION').': ';?></label>
		<div class="divEspacio" ></div>	
		<?php echo $this->editor->display( 'description',  $this->project?$this->project->description:'' , '100%', '350', '75', '20' ) ; ?>
	</div>
</div>	
</fieldset>	
<input type="hidden" name="id" value="<?php echo $this->project?$this->project->id:'' ?>" />		
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'projects'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
</div>