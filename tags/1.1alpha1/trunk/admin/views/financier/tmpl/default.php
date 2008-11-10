<?php
/**
 * @package JResearch
 * @subpackage Financiers
 * Default view for adding/editing a single financier
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" class="form-validate" onSubmit="return validate(this);"  >
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_FINANCIER')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('Name').': '?></td>
		<td>
			<input name="name" id="name" size="60" maxlength="60" value="<?php echo $this->financier?$this->financier->name:'' ?>" class="required" />
			<br />
			<label for="name" class="labelform" ><?php echo JText::_('JRESEARCH_FINANCIER_PROVIDE_VALID_NAME'); ?></label>
		</td>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('Url').': '?></td>
		<td colspan="3">
			<input name="url" id="url" size="50" maxlength="255" value="<?php echo $this->financier?$this->financier->url:'' ?>" class="required" />
			<br />
			<label for="url" class="labelform" ><?php echo JText::_('JRESEARCH_FINANCIER_PROVIDE_VALID_URL'); ?></label>
		</td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="financiers" />
<input type="hidden" name="id" value="<?php echo $this->financier?$this->financier->id:'' ?>" />	
<?php echo JHTML::_('behavior.keepalive'); ?>
</form>