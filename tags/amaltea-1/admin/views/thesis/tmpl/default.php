<?php
/**
 * @package JResearch
 * @subpackage Theses
 * Default view for adding/editing a single theses
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divForm">
<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_REQUIRED')?></legend>
	<div class="divTable">
		<div class="divTR">
			<div class="divTd"><label for="title"><?php echo JText::_('JRESEARCH_TITLE').': '?></label></div>
			<div class="divTdl">
				<input name="title" id="title" size="50" maxlength="255" class="required" value="<?php echo $this->thesis?$this->thesis->title:'' ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'title', JText::_('JRESEARCH_THESIS_PROVIDE_VALID_TITLE')); ?>			
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="alias"><?php echo JText::_('Alias').': '?></label></div>
			<div class="divTdl">
				<input name="alias" id="alias" size="50" maxlength="255" class="validate-alias" value="<?php echo $this->thesis?$this->thesis->alias:'' ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'alias', JText::_('JRESEARCH_PROVIDE_VALID_ALIAS')); ?>			
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="id_research_area"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></label></div>
			<div class="divTdl divTdl2"><?php echo $this->areasList; ?></div>
			<div class="divTd"><label for="published"><?php echo JText::_('Published').': '; ?></label></div>
			<div class="divTdl"><?php echo $this->publishedRadio; ?></div>
			<div class="divEspacio" ></div>						
		</div>
		<div class="divTR">
			<div class="divTd"><label for="status"><?php echo JText::_('JRESEARCH_STATUS').' :' ?></label></div>
			<div class="divTdl divTdl2"><?php echo $this->status; ?></div>
			<div class="divTd"><label for="degree"><?php echo JText::_('JRESEARCH_DEGREE').' :' ?></label></div>
			<div class="divTdl"><?php echo $this->degree; ?></div>
			<div class="divEspacio" ></div>			
		</div>	
	</div>
</fieldset>	
<fieldset>
<legend><?php echo JText::_('JRESEARCH_MEMBERS');?></legend>
<div class="divTable">
	<div class="divTR">
		<div class="divTd"><label for="studentsfield"><?php echo JText::_('JRESEARCH_STUDENTS').': '; ?></label></div>
		<div class="divTdl"><?php echo $this->studentsControl; ?></div>
		<div class="divEspacio" ></div>		
	</div>		
	<div class="divTR">
		<div class="divTd"><label for="directorsfield"><?php echo JText::_('JRESEARCH_DIRECTORS').': '; ?></label></div>
		<div class="divTdl"><?php echo $this->directorsControl; ?></div>
		<div class="divEspacio" ></div>		
	</div>
</div>
</fieldset>
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_OPTIONAL'); ?></legend>
	<div class="divTable">
		<div class="divTR">
			<div class="divTd"><label for="start_date"><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></label></div>
			<div class="divTdl divTdl2">
				<?php $startDate = $this->thesis?$this->thesis->start_date:''; ?>
				<?php echo JHTML::_('calendar', $startDate ,'start_date', 'start_date', '%Y-%m-%d', array('class'=>'validate-date', 'size'=>'15')); ?>
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'start_date', JText::_('JRESEARCH_PROVIDE_VALID_DATE')); ?>									
			</div>
			<div class="divTd"><label for="end_date"><?php echo JText::_('JRESEARCH_DEADLINE').': ' ?></label></div>
			<div class="divTdl">
				<?php $endDate = $this->thesis?$this->thesis->end_date:''; ?>
				<?php echo JHTML::_('calendar', $endDate ,'end_date', 'end_date', '%Y-%m-%d', array('class'=>'validate-date', 'size'=>'15')); ?>
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'end_date', JText::_('JRESEARCH_PROVIDE_VALID_DATE')); ?>												
			</div>
			<div class="divEspacio" ></div>			
		</div>
		<div class="divTR">
			<div class="divTd"><label for="url"><?php echo JText::_('JRESEARCH_DIGITAL_VERSION').' (Url) : ' ?></label></div>
			<div class="divTdl">
				<input name="url" class="validate-url" id="url" size="30" maxlength="255" value="<?php echo $this->thesis?$this->thesis->url:'' ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'url', JText::_('JRESEARCH_PROVIDE_VALID_URL')); ?>									
			</div>
			<div class="divEspacio" ></div>			
		</div>
		<div class="divTR">
			<div class="divTd"><label for="url"><?php echo JText::_('JRESEARCH_FILES').': '; ?></label></div>
			<div class="divTdl">
				<?php echo $this->files; ?>									
			</div>
			<div class="divEspacio" ></div>			
		</div>
		<?php if(!empty($this->thesis)): ?>
			<div class="divTR">
				<div class="divTd"><?php echo JText::_('Hits').': '?></div>
				<div class="divTdl"><?php echo JHTML::_('jresearchhtml.hitsControl', 'resethits', $this->thesis->hits); ?></div>
				<div class="divEspacio" ></div>			
			</div>			
		<?php endif; ?>
		<div class="divTR">
			<label for="description"><?php echo JText::_('JRESEARCH_DESCRIPTION').': ';?></label>
			<div class="divEspacio" ></div>	
			<?php echo $this->editor->display( 'description',  isset($this->thesis)?$this->thesis->description:'' , '100%', '350', '75', '20' ) ; ?>
		</div>	
	</div>		
</fieldset>
<input type="hidden" name="id" value="<?php echo $this->thesis?$this->thesis->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'theses'); ?>	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
</div>