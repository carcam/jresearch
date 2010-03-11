<?php
/**
 * @package JResearch
 * @subpackage Projects
 * View for exporting projects
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1><?php echo $this->exportAll? JText::_('JRESEARCH_EXPORT_ALL_PROJECTS'): JText::_('JRESEARCH_EXPORT_SELECTED_RECORDS'); ?></h1>
<form name="adminForm" id="adminForm" method="post">
<table class="adminform">
	<tbody>
	<tr>
		<td width="20%"><?php echo JText::_('JRESEARCH_OUTPUT_FORMAT').': ' ?></td>
		<td width="80%">
	<?php echo $this->formatsList; ?>
	</td>
	</tr>
	</tbody>
</table>
<div style="text-align: center;">
	<input name="submit" value="<?php echo JText::_('Export'); ?>" type="submit" />
</div>
<input type="hidden" name="option" value="com_jresearch"  /> 
<input type="hidden" name="controller" value="projects" />
<input type="hidden" name="task" value="executeExport"  /> 
<input type="hidden" name="format" value="raw" />
</form>
