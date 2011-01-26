<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for exporting publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1><?php echo $this->exportAll? JText::_('JRESEARCH_EXPORT_ALL_PUBLICATIONS'): JText::_('JRESEARCH_EXPORT_SELECTED_RECORDS'); ?></h1>
<form name="adminForm" id="adminForm" action="index.php" method="post">
<table class="adminform">
	<tbody>
	<tr>
		<th width="10%"><?php echo JText::_('JRESEARCH_OUTPUT_FORMAT').': ' ?></th>
		<td width="10%">
			<?php echo $this->formatsList; ?>
		</td>
		<td style="text-align:left;">
			<label for="strict_bibtex"><?php echo JText::_('JRESEARCH_STRICT_BIBTEX'); ?></label>
			<input type="checkbox" id="strict_bibtex" name="strict_bibtex" />
			<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_STRICT_BIBTEX_DESCRIPTION')); ?>
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