<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for exporting publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1><?php echo $this->exportAll? JText::_('JRESEARCH_EXPORT_ALL_PUBLICATIONS'): JText::_('JRESEARCH_EXPORT_SELECTED_RECORDS'); ?></h1>
<form name="adminForm" id="adminForm" action="./" method="post">
<table class="adminform">
	<tbody>
	<tr>
		<th width="20%"><?php echo JText::_('JRESEARCH_OUTPUT_FORMAT').': ' ?></th>
		<td width="80%">
	<?php echo $this->formatsList; ?>
	</td>
	</tr>
	</tbody>
</table>
<div style="text-align: center;">
	<input name="submit" value="<?php echo JText::_('Export'); ?>" type="submit" />
</div>

<input type="hidden" name="format" value="raw" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'publications', 'executeExport'); ?>
</form>
