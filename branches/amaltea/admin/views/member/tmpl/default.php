<?php
/**
 * @package JResearch
 * @subpackage Staff
 * Default view for adding/editing a single member.
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divForm">
<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<fieldset>
<legend><?php echo JText::_('JRESEARCH_PROFILE')?></legend>
<div class="divTable">
	<div class="divTR">
		<div class="divTd"><label for="firstname"><?php echo JText::_('JRESEARCH_FIRST_NAME').': '?></label></div>
		<div class="divTdl divTdl2">
			<input name="firstname" id="firstname" size="15" maxlength="30" class="required" value="<?php echo isset($this->member)?$this->member->firstname:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'firstname', JText::_('JRESEARCH_MEMBER_PROVIDE_VALID_FIRSTNAME')); ?>			
		</div>
		<div class="divTd"><label for="lastname"><?php echo JText::_('JRESEARCH_LAST_NAME').': '; ?></label></div>
		<div class="divTdl">
			<input name="lastname" id="lastname" size="15" maxlength="30" class="required" value="<?php echo isset($this->member)?$this->member->lastname:''; ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'lastname', JText::_('JRESEARCH_PROVIDE_LAST_NAME')); ?>			
		</div>
	    <div class="divEspacio" ></div>			
	</div>
	<div class="divTR">
		<div class="divTd"><label for="title"><?php echo JText::_('JRESEARCH_MEMBER_TITLE').': '; ?></label></div>
		<div class="divTdl divTdl2"><input name="title" id="title" size="10" maxlength="10" value="<?php echo isset($this->member)?$this->member->title:''; ?>" type="text" /></div>			
		<div class="divTd"><label for="tagline"><?php echo JText::_('JRESEARCH_TAGLINE').': '; ?></label></div>
		<div class="divTdl"><input name="tagline" id="tagline" size="20" maxlength="255" value="<?php echo isset($this->member)?$this->member->tagline:''; ?>" /></div>
	    <div class="divEspacio" ></div>		
	</div>
	<div>		
		<div class="divTd"><label for="published"><?php echo JText::_('Published').': '; ?></label></div>
		<div class="divTdl"><?php echo $this->publishedRadio; ?></div>
	    <div class="divEspacio" ></div>			
	</div>
	<div class="divTR">	
		<div class="divTd"><label for="id_research_area"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></label></div>
		<div class="divTdl divTdl2"><?php echo $this->areasList; ?></div>
		<div class="divTd"><label for="order"><?php echo JText::_('Order').': '; ?></label></div>
		<div class="divTdl"><?php echo $this->orderList; ?></div>
	    <div class="divEspacio" ></div>		
	</div>	
	<div class="divTR">
		<div class="divTd"><label for="position"><?php echo JText::_('Position').': ' ?></label></div>
		<div class="divTdl divTdl2"><?php echo $this->positionList; ?></div>
		<div class="divTd"><label for="location"><?php echo JText::_('JRESEARCH_LOCATION').': '; ?></label></div>
		<div class="divTdl"><input type="text" size="15" maxlength="255" name="location" id="location" value="<?php echo isset($this->member)?$this->member->location:'' ?>" /></div>
	    <div class="divEspacio" ></div>		
	</div>		
	<div class="divTR">
		<div class="divTd"><label for="former_member"><?php echo JText::_('JRESEARCH_FORMER_MEMBER')?></label></div>
		<div class="divTdl divTdl2"><input type="checkbox" name="former_member" value="1" <?php echo (($this->member->former_member == 1) ? 'checked="checked"' : "")?> /></div>	
		<div class="divTd"><label for="phone"><?php echo JText::_('JRESEARCH_PHONE').': ' ?></label></div>
		<div class="divTdl"><input name="phone" id="phone" size="15" maxlength="15" value="<?php echo isset($this->member)?$this->member->phone:'' ?>" /></div>
	    <div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd"><label for="fax"><?php echo JText::_('JRESEARCH_FAX').': ' ?></label></div>	
		<div class="divTdl divTdl2"><input name="fax" id="fax" size="15" maxlength="15" value="<?php echo isset($this->member)?$this->member->fax:'' ?>" /></div>
	
		<div class="divTd"><label for="email"><?php echo JText::_('Email').': ' ?></label></div>
		<div class="divTdl">
			<input maxlength="255" size="15" name="email" class="required validate-email" id="email" value="<?php echo isset($this->member)?$this->member->email:''; ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'email', JText::_('JRESEARCH_PROVIDE_EMAIL')); ?>						
		</div>		
	    <div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
			<div class="divTd"><label for="url_personal_page"><?php echo JText::_('JRESEARCH_PERSONAL_PAGE').': ' ?></label></div>
		<div class="divTdl">
			<input type="text" size="30" maxlength="255" class="validate-url" name="url_personal_page" id="url_personal_page" value="<?php echo isset($this->member)?$this->member->url_personal_page:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'url_personal_page', JText::_('JRESEARCH_PROVIDE_VALID_URL')); ?>			
		</div>
	    <div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd"><label for="file"><?php echo JText::_('Photo').': ' ?></label></div>
		<div class="divTdl">
			<input class="inputbox" size="20" name="inputfile" id="inputfile" type="file" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', 400, 400)); ?>						
		</div>
		<div class="divTdl">
			<?php
			if($this->member && !is_null($this->member->url_photo)):
				$url = JResearch::getUrlByRelative($this->member->url_photo);
				$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->member->url_photo):$url;
			?>
			<a href="<?php echo $url;?>" class="modal">
				<img src="<?php echo $thumb; ?>" alt="<?php echo JText::_('JRESEARCH_NO_PHOTO'); ?>" />
			</a>
			<input type="hidden" name="url_photo" id="url_photo" value="<?php echo $this->member->url_photo;?>" />
			<label for="delete"><?php echo JText::_('JRESEARCH_DELETE_CURRENT_PHOTO'); ?></label><input type="checkbox" name="delete" id="delete" />
			<?php endif; ?>
		</div>
	    <div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<label for="description"><?php echo JText::_('JRESEARCH_DESCRIPTION').': ';?></label>
		<div class="divEspacio" ></div>	
		<?php echo $this->editor->display( 'description',  isset($this->member)?$this->member->description:'' , '100%', '350', '75', '20' ) ; ?>
	</div>			
</div>
</fieldset>
<input type="hidden" name="id" value="<?php echo isset($this->member)?$this->member->id:'' ?>" />	
<input type="hidden" name="username" value="<?php echo isset($this->member)?$this->member->username:'';?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'staff'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
</div>