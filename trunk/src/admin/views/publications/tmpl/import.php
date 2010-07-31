<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for importing publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
?>
<form name="adminForm" id="adminForm" enctype="multipart/form-data" method="post">
<table class="adminform">
	<tbody>
	<tr>
		<th width="20%"><?php echo JText::_('JRESEARCH_INPUT_FORMAT').': ' ?></th>
		<td width="80%">
	<?php echo $this->formatsList; ?>
	</td>
	</tr>
	<tr>
		<th width="20%"><?php echo JText::_('File').': ' ?></th>
		<td width="80%"> 
			<input class="inputbox" name="inputfile" id="inputfile" type="file" />
                        <label for="maptostaff"><?php echo JText::_('JRESEARCH_MAP_TO_STAFF').': '; ?></label>
                        <input type="checkbox" name="maptostaff" id="maptostaff"  />
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '; ?></th>
		<td><?php echo $this->categoryList; ?></td>
	</tr>
	</tbody>
</table>
<div style="text-align: center;">
	<input name="submit" value="<?php echo JText::_('Upload'); ?>" type="submit" />
</div>
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'publications', 'executeImport'); ?>
</form>
