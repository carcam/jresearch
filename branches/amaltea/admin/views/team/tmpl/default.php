<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for adding/editing a cooperation
*/
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divForm">
<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<fieldset>
<legend><?php echo JText::_('JRESEARCH_TEAM')?></legend>
<div class="divTable">
	<div class="divTR">
		<div class="divTd"><label for="name"><?php echo JText::_('JRESEARCH_TEAM_NAME').': '?></label></div>
		<div class="divTdl">
			<input name="name" id="name" size="50" maxlength="100" class="required" value="<?php echo $this->team?$this->team->name:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'name', JText::_('JRESEARCH_PROVIDE_VALID_NAME')); ?>			
		</div>
		<div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd"><label for="alias"><?php echo JText::_('Alias').': '?></label></div>
		<div class="divTdl">
			<input name="alias" id="alias" size="50" maxlength="255" class="validate-alias" value="<?php echo $this->team?$this->team->alias:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'alias', JText::_('JRESEARCH_PROVIDE_VALID_ALIAS')); ?>			
		</div>
		<div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd"><label for="published"><?php echo JText::_('Published').': '; ?></label></div>
		<div class="divTdl divTdl2"><?php echo $this->publishedRadio; ?></div>
		<div class="divTd"><label for="parent"><?php echo JText::_('Parent').': '; ?></label></div>
		<div class="divTdl"><?php echo JHTML::_('jresearchhtml.teamshierarchy', $this->hierarchy, array('name' => 'parent', 'selected' => $this->team?$this->team->parent:null)); ?></div>
	    <div class="divEspacio" ></div>		
	</div>	
	<div class="divTR">
		<div class="divTd"><label for="id_leader"><?php echo JText::_('JRESEARCH_TEAM_LEADER').': '; ?></label></div>
		<div class="divTdl divTdl2"><?php echo $this->leaderList; ?></div>
		<div class="divTd"><label for="members"><?php echo JText::_('JRESEARCH_TEAM_MEMBERS').': '; ?></label></div>
		<div class="divTdl"><?php echo $this->memberList; ?></div>
	    <div class="divEspacio" ></div>		
	</div>	
	<div class="divTR">
		<div class="divTd"><label for="inputfile"><?php echo JText::_('JRESEARCH_TEAM_LOGO').': '; ?></label></div>
		<div class="divTdl">
			<input type="file" name="inputfile" id="inputfile" /><?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', 400, 400)); ?>
		</div>
		<div class="divTdl">		
			<?php
			if(isset($this->team)):
				if($this->team->logo):
					$url = JResearch::getUrlByRelative($this->team->logo);
					$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->team->logo):$url;
			?>
				<a href="<?php echo $url;?>" class="modal">
					<img src="<?php echo $thumb; ?>" alt="<?php echo JText::_('JRESEARCH_NO_PHOTO'); ?>" />
				</a>
				<input type="hidden" name="logo" value="<?php echo $this->team->logo; ?>" />
	
				<label for="delete"><?php echo JText::_('Delete current photo'); ?></label><input type="checkbox" name="delete" id="delete" />
				<?php endif; ?>
			<?php endif; ?>			
		</div>		
	    <div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<label for="description"><?php echo JText::_('JRESEARCH_DESCRIPTION').': ';?></label>
		<div class="divEspacio" ></div>	
		<?php echo $this->editor->display( 'description',  isset($this->team)?$this->team->description:'' , '100%', '350', '75', '20' ) ; ?>
	</div>				
</div>
</fieldset>
<input type="hidden" name="id" value="<?php echo $this->team?$this->team->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'teams'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
</div>