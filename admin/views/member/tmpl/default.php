<?php
/**
 * @package JResearch
 * @subpackage Staff
 * Default view for adding/editing a single member.
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data" class="form-validate" onSubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_PROFILE')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_FORMER_MEMBER')?></td>
		<td>
			<input type="checkbox" name="former_member" value="1" <?php echo (($this->member && $this->member->former_member == 1) ? 'checked="checked"' : "")?> />
		</td>
		<td><?php echo JText::_('Order').': '; ?></td>
		<td><?php echo $this->orderList; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_FIRST_NAME').': '?></td>
		<td>
			<input name="firstname" id="firstname" size="30" maxlength="30" class="required" value="<?php echo $this->member?$this->member->firstname:'' ?>" />
			<br /><label for="firstname" class="labelform"><?php echo JText::_('Please provide a firstname. Alphabetic characters plus _- and spaces are allowed.'); ?></label>
		</td>
		<td><?php echo JText::_('JRESEARCH_LAST_NAME').': '; ?></td>
		<td>
			<input name="lastname" id="lastname" size="30" maxlength="30" class="required" value="<?php echo $this->member?$this->member->lastname:''; ?>" />
			<br /><label for="lastname" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_LAST_NAME'); ?></label>			
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>		
		<td><?php echo $this->areasList; ?></td>
		<td><?php echo JText::_('Email').' :' ?></td>
		<td>
			<input maxlength="255" size="30" name="email" class="required validate-email" id="email" value="<?php echo $this->member?$this->member->email:''; ?>" />
			<br /><label for="email" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_EMAIL'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('Position').': ' ?></td>
		<td><input type="text" name="position" id="position" value="<?php echo $this->member?$this->member->position:'' ?>" /></td>
		<td><?php echo JText::_('JRESEARCH_PERSONAL_PAGE').': ' ?></td>
		<td>
			<input type="text" size="30" maxlength="255" class="validate-url" name="url_personal_page" id="url_personal_page" value="<?php echo $this->member?$this->member->url_personal_page:'' ?>" />
			<br />
			<label for="url_personal_page" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
		<td><?php echo JText::_('JRESEARCH_PHONE_OR_FAX').': ' ?></td>
		<td><input name="phone_or_fax" id="phone_or_fax" size="15" maxlength="15" value="<?php echo $this->member?$this->member->phone_or_fax:'' ?>" /></td>
	</tr>
	<tr>
		<td><?php echo JText::_('Photo').': ' ?></td>
		<td><input class="inputbox" name="inputfile" id="inputfile" type="file" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_IMAGE_SUPPORTED_FORMATS', 400, 400)); ?></td>
		<td>
			<img src="<?php echo $this->member->url_photo; ?>" alt="<?php echo JText::_('JRESEARCH_NO_PHOTO'); ?>" />
			<input type="hidden" name="url_photo" id="url_photo" value="<?=$this->member->url_photo;?>" />
		</td>
		<td><label for="delete" /><?php echo JText::_('JRESEARCH_DELETE_CURRENT_PHOTO'); ?></label><input type="checkbox" name="delete" id="delete" /></td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->member->description , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="staff" />
<input type="hidden" name="id" value="<?php echo $this->member?$this->member->id:'' ?>" />	
<input type="hidden" name="username" value="<?php echo $this->member?$this->member->username:'';?>" />
<?php echo JHTML::_('behavior.keepalive'); ?>	
</form>