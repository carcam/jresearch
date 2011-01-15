<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for adding/editing a cooperation
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
?>
<div class="divForm">
<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<fieldset>
<legend><?php echo JText::_('JRESEARCH_COOPERATION'); ?></legend>
<div class="divTable">
	<div class="divTR">
		<div class="divTd"><label for="name"><?php echo JText::_('JRESEARCH_COOPERATION_NAME').': '?></label></div>
		<div class="divTdl">
			<input name="name" id="name" size="50" maxlength="100" class="required" value="<?php echo ($this->coop)?$this->coop->name:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'name', JText::_('JRESEARCH_PROVIDE_VALID_NAME')); ?>			
		</div>
		<div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd"><label for="alias"><?php echo JText::_('Alias').': '?></label></div>
		<div class="divTdl">
			<input name="alias" id="alias" size="50" maxlength="255" class="validate-alias" value="<?php echo ($this->coop)?$this->coop->alias:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'alias', JText::_('JRESEARCH_PROVIDE_VALID_ALIAS')); ?>			
		</div>
		<div class="divEspacio" ></div>		
	</div>	
	<div class="divTR">
		<div class="divTd"><label for="url"><?php echo JText::_('JRESEARCH_COOPERATION_URL').': '; ?></label></div>
		<div class="divTdl">
			<input name="url" id="url" size="50" maxlength="255" class="required validate-url" value="<?php echo ($this->coop)?$this->coop->url:''; ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'url', JText::_('JRESEARCH_PROVIDE_VALID_URL')); ?>					
		</div>
		<div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd"><label for="published"><?php echo JText::_('Published').': '; ?></label></div>
		<div class="divTdl divTdl2"><?php echo $this->publishedList; ?></div>
		<div class="divTd"><label for="order"><?php echo JText::_('Order').': '; ?></label></div>
		<div class="divTdl"><?php echo $this->orderList; ?></div>
	    <div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd"><label for="id_team"><?php echo JText::_('JRESEARCH_TEAM').': '; ?></label></div>
		<div class="divTdl divTdl2"><?php echo $this->teamsList; ?></div>	
		<div class="divTd"><label for="catid"><?php echo JText::_('JRESEARCH_COOPERATION_CATEGORIES').': '?></label></div>
		<div class="divTdl"><?php echo $this->categoryList; ?></div>
	    <div class="divEspacio" ></div>				
	</div>
	<div class="divTR">
		<div class="divTd"><label for="file"><?php echo JText::_('Photo').': ' ?></label></div>
		<div class="divTdl">
			<input class="inputbox" name="inputfile" id="inputfile" type="file" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', _COOPERATION_IMAGE_MAX_WIDTH_, _COOPERATION_IMAGE_MAX_HEIGHT_)); ?>		
		</div>
		<div class="divTdl">
			<?php
			if($this->coop && $this->coop->image_url):
				$url = JResearch::getUrlByRelative($this->coop->image_url);
				$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->coop->image_url):$url;
			?>
			<a href="<?php echo $url?>" class="modal">
				<img src="<?php echo $thumb;?>" alt="<?php echo JText::_('Photo'); ?>" class="modal" />
			</a>
			<input type="hidden" name="image_url" id="image_url" value="<?php echo $this->coop->image_url;?>" />
			<label for="delete"><?php echo JText::_('JRESEARCH_DELETE_CURRENT_PHOTO'); ?></label><input type="checkbox" name="delete" id="delete" />		
			<?php endif; ?>
		</div>
		<div class="divEspacio" ></div>					
	</div>
	<div class="divTR">
		<label for="description"><?php echo JText::_('JRESEARCH_DESCRIPTION').': ';?></label>
		<div class="divEspacio" ></div>			
		<?php echo $this->editor->display( 'description',  ($this->coop)?$this->coop->description:'' , '100%', '350', '75', '20' ) ; ?>
	</div>						
</div>
</fieldset>

<input type="hidden" name="id" value="<?php echo ($this->coop)?$this->coop->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'cooperations'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
</div>