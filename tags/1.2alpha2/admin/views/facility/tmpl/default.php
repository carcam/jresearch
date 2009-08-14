<?php
/**
 * @package JResearch
 * @subpackage Facilities
 * Default view for adding/editing a single facility
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divForm">
<form name="adminForm" id="adminForm" action="./" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_REQUIRED')?></legend>
	<div class="divTable">
		<div class="divTR">
			<div class="divTd"><label for="name"><?php echo JText::_('Name').': '?></label></div>
			<div class="divTdl">
				<input name="name" id="name" size="50" maxlength="255" value="<?php echo $this->fac?$this->fac->name:'' ?>" class="required" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'name', JText::_('JRESEARCH_PROVIDE_VALID_NAME')); ?>		
			</div>
    		<div class="divEspacio" ></div>			
    	</div>
    	<div class="divTR">
			<div class="divTd"><label for="alias"><?php echo JText::_('Alias').': '?></label></div>
			<div class="divTdl">
				<input name="alias" id="alias" size="50" maxlength="255" value="<?php echo $this->fac?$this->fac->name:'' ?>" class="required" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'alias', JText::_('JRESEARCH_PROVIDE_VALID_ALIAS')); ?>		
			</div>
    		<div class="divEspacio" ></div>			
    	</div>
    	<div class="divTR">
    		<div class="divTd"><label for="id_research_area"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></label></div>
    		<div class="divTdl divTdl2"><?php echo $this->areasList; ?></div>
    		<div class="divTd"><label for="published"><?php echo JText::_('Published').': '; ?></label></div>
    		<div><?php echo $this->publishedRadio; ?></div>
    	</div>	
	</div>
</fieldset>
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_OPTIONAL'); ?></legend>
	<div class="divTable">
		<div class="divTR">
			<div class="divTd"><?php echo JText::_('JRESEARCH_FACILITY_IMAGE').': '; ?></div>
			<div class="divTdl">			
				<input type="file" name="inputfile" id="inputfile" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', 1024, 768)); ?><br />
				<label for="delete" /><?php echo JText::_('Delete current photo'); ?></label><input type="checkbox" name="delete" id="delete" />
			</div>
			<div class="divTdl">
				<?php
				if($this->fac && !is_null($this->fac->image_url)):
					$url = JResearch::getUrlByRelative($this->fac->image_url);
					$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->fac->image_url):$url;
				?>
					<a href="<?php echo $url;?>" class="modal">
						<img src="<?php echo $thumb; ?>" alt="Image of <?php echo $this->fac->name?>" width="100" />
					</a>
					<input type="hidden" name="image_url" id="image_url" value="<?php echo $this->fac->image_url;?>" />
				<?php endif; ?>		
			</div>
			<div class="divEspacio" ></div>
		</div>
		<div class="divTR">
			<label for="description"><?php echo JText::_('JRESEARCH_DESCRIPTION').': ';?></label>
			<div class="divEspacio" ></div>	
			<?php echo $this->editor->display( 'description',  $this->fac?$this->fac->description:'' , '100%', '350', '75', '20' ) ; ?>
		</div>
	</div>			
</fieldset>
<input type="hidden" name="id" value="<?php echo $this->fac?$this->fac->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'facilities'); ?>	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
</div>