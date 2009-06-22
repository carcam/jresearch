<?php
/**
 * @package JResearch
 * @subpackage ResearchAreas
 * Default view for adding/editing a single research area
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" class="form-validate" onSubmit="return validate(this);"  >
<table class="edit" cellpadding="5" cellspacing="5">
<thead>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_RESEARCH_AREA')?></th>
	</tr>
</thead>
<tbody>
	<tr>
		<th><?php echo JText::_('Name').': '?></th>
		<td>
			<input name="name" id="name" size="30" maxlength="255" value="<?php echo $this->area?$this->area->name:'' ?>" class="required" />
			<br />
			<label for="name" class="labelform" ><?php echo JText::_('JRESEARCH_RESEARCH_AREA_PROVIDE_VALID_NAME'); ?></label>
		</td>
		<th><?php echo JText::_('Published').': '; ?></th>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->area?$this->area->description:'' , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>

<input type="hidden" name="id" value="<?php echo $this->area?$this->area->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'researchAreas'); ?>	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>