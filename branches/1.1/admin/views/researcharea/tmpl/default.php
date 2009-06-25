<?php
/**
 * @package JResearch
 * @subpackage ResearchAreas
 * Default view for adding/editing a single research area
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" class="form-validate" onSubmit="return validate(this);"  >
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_RESEARCH_AREA')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('Name').': '?></td>
		<td>
			<input name="name" id="name" size="30" maxlength="255" value="<?php echo $this->area?$this->area->name:'' ?>" class="required" />
			<br />
			<label for="name" class="labelform" ><?php echo JText::_('JRESEARCH_RESEARCH_AREA_PROVIDE_VALID_NAME'); ?></label>
		</td>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  isset($this->area)?$this->area->description:'' , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="researchAreas" />
<input type="hidden" name="id" value="<?php echo $this->area?$this->area->id:'' ?>" />	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>