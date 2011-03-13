<?php
/**
* @package JResearch
* @subpackage Institutes
* Default view for adding/editing a institute
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
?>
<div class="divForm">
<form name="adminForm" id="adminForm" action="./" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<fieldset>
<legend><?php echo JText::_('JRESEARCH_INSTITUTE'); ?></legend>
<div class="divTable">
	<fieldset>
		<legend><?php echo JText::_('JRESEARCH_GENERAL'); ?></legend>
		<div class="divTR">
			<div class="divTd"><label for="name"><?php echo JText::_('JRESEARCH_INSTITUTE_NAME').': '?></label></div>
			<div class="divTdl">
				<input name="name" id="name" size="50" maxlength="256" class="required" value="<?php echo ($this->institute)?$this->institute->name:'' ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'name', JText::_('JRESEARCH_PROVIDE_VALID_NAME')); ?>			
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="alias"><?php echo JText::_('Alias').': '?></label></div>
			<div class="divTdl">
				<input name="alias" id="alias" size="50" maxlength="255" class="validate-alias" value="<?php echo ($this->institute)?$this->institute->alias:'' ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'alias', JText::_('JRESEARCH_PROVIDE_VALID_ALIAS')); ?>			
			</div>
			<div class="divEspacio" ></div>		
		</div>	
		<div class="divTR">
			<div class="divTd"><label for="name2"><?php echo JText::_('JRESEARCH_INSTITUTE_NAME2').': '?></label></div>
			<div class="divTdl">
				<input name="name2" id="name2" size="50" maxlength="255" value="<?php echo ($this->institute)?$this->institute->name2:'' ?>" />
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="name_english"><?php echo JText::_('JRESEARCH_INSTITUTE_NAME_ENGLISH').': '?></label></div>
			<div class="divTdl">
				<input name="name_english" id="name_english" size="50" maxlength="255" value="<?php echo ($this->institute)?$this->institute->name_english:'' ?>" />
			</div>
			<div class="divEspacio" ></div>		
		</div>		
		<div class="divTR">
			<div class="divTd"><label for="url"><?php echo JText::_('JRESEARCH_INSTITUTE_URL').': '; ?></label></div>
			<div class="divTdl">
				<input name="url" id="url" size="50" maxlength="255" class="required validate-url" value="<?php echo ($this->institute)?$this->institute->url:''; ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'url', JText::_('JRESEARCH_PROVIDE_VALID_URL')); ?>					
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="published"><?php echo JText::_('Published').': '; ?></label></div>
			<div class="divTdl divTdl2"><?php echo $this->publishedList; ?></div>
		    <div class="divEspacio" ></div>		
		</div>
	</fieldset>
	<fieldset>
		<legend><?php echo JText::_('JRESEARCH_ADDITIONAL'); ?></legend>
		<div class="divTR">
			<div class="divTd"><label for="street"><?php echo JText::_('JRESEARCH_INSTITUTE_STREET').': '?></label></div>
			<div class="divTdl">
				<input name="street" id="street" size="50" maxlength="255" value="<?php echo ($this->institute)?$this->institute->street:'' ?>" />		
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="street2"><?php echo JText::_('JRESEARCH_INSTITUTE_STREET_2').': '?></label></div>
			<div class="divTdl">
				<input name="street2" id="street2" size="50" maxlength="255" value="<?php echo ($this->institute)?$this->institute->street2:'' ?>" />		
			</div>
			<div class="divEspacio" ></div>		
		</div>		
		<div class="divTR">
			<div class="divTd"><label for="zip"><?php echo JText::_('JRESEARCH_INSTITUTE_ZIP').': '?></label></div>
			<div class="divTdl">
				<input name="zip" id="zip" size="20" maxlength="8" value="<?php echo ($this->institute)?$this->institute->zip:'' ?>" />
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="place"><?php echo JText::_('JRESEARCH_INSTITUTE_PLACE').': '?></label></div>
			<div class="divTdl">
				<input name="place" id="place" size="50" maxlength="255" value="<?php echo ($this->institute)?$this->institute->place:'' ?>" />
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="state_province"><?php echo JText::_('JRESEARCH_INSTITUTE_STATE_OR_PROVINCE').': '; ?></label></div>
			<div class="divTdl divTdl2"><input type="text" name="state_province" id="state_province" size="20" maxlength="20" value="<?php echo (isset($this->institute))?$this->institute->state_province:'' ?>" /></div>
		    <div class="divEspacio" ></div>		
		</div>
		
		<div class="divTR">
			<div class="divTd"><label for="id_country"><?php echo JText::_('JRESEARCH_COUNTRY').': '?></label></div>
			<div class="divTdl">			
				<?php echo JHTML::_('jresearchhtml.countrieslist', 'id_country', 'class="inputbox"' ,isset($this->institute)?$this->institute->id_country:0);	?>		
			</div>
			<div class="divEspacio" ></div>		
		</div>		
		<div class="divTR">
			<div class="divTd"><label for="phone"><?php echo JText::_('JRESEARCH_INSTITUTE_PHONE').': '?></label></div>
			<div class="divTdl">
				<input name="phone" id="phone" size="20" maxlength="20" value="<?php echo ($this->institute)?$this->institute->phone:'' ?>" />		
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="fax"><?php echo JText::_('JRESEARCH_INSTITUTE_FAX').': '?></label></div>
			<div class="divTdl">
				<input name="fax" id="fax" size="20" maxlength="20" value="<?php echo isset($this->institute)?$this->institute->fax:'' ?>" />
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="url"><?php echo JText::_('JRESEARCH_INSTITUTE_CONTACT').': '; ?></label></div>
			<div class="divTdl">
				<input name="contact_p" id="contact_p" size="30" maxlength="80"  value="<?php echo isset($this->institute)?$this->institute->contact_p:''; ?>" />
			</div>
			<div class="divEspacio" ></div>		
		</div>		
		<div class="divTR">
			<div class="divTd"><label for="email"><?php echo JText::_('JRESEARCH_INSTITUTE_EMAIL').': '?></label></div>
			<div class="divTdl">
				<input name="email" id="email" size="50" maxlength="255" class="validate-email" value="<?php echo isset($this->institute)?$this->institute->email:'' ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'email', JText::_('JRESEARCH_PROVIDE_VALID_EMAIL')); ?>				
			</div>
			<div class="divEspacio" ></div>		
		</div>
		<div class="divTR">
			<div class="divTd"><label for="file"><?php echo JText::_('Logo').': ' ?></label></div>
			<div class="divTdl">
				<input class="inputbox" name="inputfile" id="inputfile" type="file" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', _INSTITUTE_IMAGE_MAX_WIDTH_, _INSTITUTE_IMAGE_MAX_HEIGHT_)); ?>		
			</div>
			<div class="divTdl">
				<?php
				if($this->institute && $this->institute->institute_logo):
					$url = JResearch::getUrlByRelative($this->institute->institute_logo);
					$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->institute->institute_logo):$url;
				?>
				<a href="<?php echo $url?>" class="modal">
					<img src="<?php echo $thumb;?>" alt="<?php echo JText::_('Photo'); ?>" class="modal" />
				</a>
				<input type="hidden" name="logo_url" id="logo_url" value="<?php echo $this->institute->institute_logo;?>" />
				<label for="delete"><?php echo JText::_('JRESEARCH_DELETE_CURRENT_PHOTO'); ?></label><input type="checkbox" name="delete" id="delete" />		
				<?php endif; ?>
			</div>
			<div class="divEspacio" ></div>					
		</div>
	</fieldset>
	<fieldset>
		<legend><?php echo JText::_('JRESEARCH_OPTIONAL'); ?></legend>
		<div class="divTR">
			<label for="description"><?php echo JText::_('JRESEARCH_INSTITUTE_COMMENT').': ';?></label>
			<div class="divEspacio" ></div>			
			<?php echo $this->editor->display( 'comment',  ($this->institute)?$this->institute->comment:'' , '100%', '350', '75', '20' ) ; ?>
		</div>
	</fieldset>				
</div>
</fieldset>

<input type="hidden" name="id" value="<?php echo ($this->institute)?$this->institute->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'institutes'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
</div>
