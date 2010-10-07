<?php
/**
 * @package JResearch
 * @subpackage ResearchAreas
 * Default view for adding/editing a single research area
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divForm">
    <form name="adminForm" action="index.php" id="adminForm" method="post" class="form-validate" onSubmit="return validate(this);"  >
<fieldset>
	<legend>
		<?php echo JText::_('JRESEARCH_RESEARCH_AREA')?>
	</legend>
<div class="divTable">
<div class="divTR">
	<div class="divTd"><label for="name"><?php echo JText::_('Name').': '?></label></div>
	<div class="divTdl">
		<input name="name" id="name" size="50" maxlength="255" value="<?php echo $this->area?$this->area->name:'' ?>" class="required" />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'name', JText::_('JRESEARCH_RESEARCH_AREA_PROVIDE_VALID_NAME')); ?>
	</div>
    <div class="divEspacio" ></div>										
</div>		
<div class="divTR">
	<div class="divTd"><label for="alias"><?php echo JText::_('Alias').': '?></label></div>
	<div class="divTdl">
		<input name="alias" id="alias" class="validate-alias" size="50" maxlength="255" value="<?php echo $this->area?$this->area->alias:'' ?>"  />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'alias', JText::_('JRESEARCH_PROVIDE_VALID_ALIAS')); ?>
	</div>
    <div class="divEspacio" ></div>		
</div>
<div class="divTR">
	<div class="divTd"><label for="published"><?php echo JText::_('Published').': '; ?></label></div>
	<div class="divTdl"><?php echo $this->publishedRadio; ?></div>
    <div class="divEspacio" ></div>			
</div>
<div class="divTR">
	<?php echo $this->editor->display( 'description',  isset($this->area)?$this->area->description:'' , '100%', '350', '75', '20' ) ; ?>
</div>
</div>
<input type="hidden" name="id" value="<?php echo $this->area?$this->area->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'researchAreas'); ?>	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</fieldset>
</form>
</div>