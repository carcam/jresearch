<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data" class="form-validate" onSubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_COOPERATION')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_COOPERATION_NAME').': '?></td>
		<td colspan="3">
			<input name="name" id="name" size="30" maxlength="30" class="required" value="<?php echo $this->coop?$this->coop->name:'' ?>" />
			<br /><label for="name" class="labelform"><?php echo JText::_('Please provide a name. Alphabetic characters plus _- and spaces are allowed.'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_COOPERATION_URL').': '; ?></td>
		<td colspan="3">
			<input name="url" id="url" size="30" maxlength="30" class="required" value="<?php echo $this->coop?$this->coop->url:''; ?>" />
			<br /><label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_URL'); ?></label>			
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
		<td><?php echo JText::_('Order').': '; ?></td>
		<td><?php echo $this->orderList; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('Photo').': ' ?></td>
		<td><input class="inputbox" name="inputfile" id="inputfile" type="file" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_IMAGE_SUPPORTED_FORMATS')); ?></td>
		<td><img src="<?php echo $this->coop->image_url; ?>" alt="<?php echo JText::_('JRESEARCH_NO_PHOTO'); ?>" /></td>
		<td><label for="delete" /><?php echo JText::_('JRESEARCH_DELETE_CURRENT_PHOTO'); ?></label><input type="checkbox" name="delete" id="delete" /></td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->coop->description , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="cooperations" />
<input type="hidden" name="id" value="<?php echo $this->coop?$this->coop->id:'' ?>" />	
<?php echo JHTML::_('behavior.keepalive'); ?>	
</form>