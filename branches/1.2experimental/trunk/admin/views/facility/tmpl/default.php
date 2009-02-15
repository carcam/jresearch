<?php
/**
 * @package JResearch
 * @subpackage Facilities
 * Default view for adding/editing a single facility
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" action="./" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<table class="edit" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th class="title" colspan="4"><?php echo JText::_('JRESEARCH_REQUIRED')?></th>
	</tr>
	<tr>
		<th><?php echo JText::_('Name').': '?></th>
		<td colspan="3">
			<input name="name" id="name" size="80" maxlength="255" value="<?=$this->fac?$this->fac->name:'' ?>" class="required" />
			<br />
			<label for="name" class="labelform"><?php echo JText::_('JRESEARCH_FACILITY_PROVIDE_VALID_NAME'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?=JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></th>		
		<td><?=$this->areasList; ?></td>
		<th><?=JText::_('Published').': '; ?></th>
		<td><?=$this->publishedRadio; ?></td>
	</tr>
	<tr>
		<th class="title" colspan="4"><?=JText::_('JRESEARCH_OPTIONAL'); ?></th>
	</tr>
	<tr>
		<th>
			<?=JText::_('JRESEARCH_FACILITY_IMAGE').': '; ?>
		</th>
		<td>
			<input type="file" name="inputfile" id="inputfile" />&nbsp;&nbsp;<?=JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', 1024, 768)); ?><br />
			<label for="delete" /><?=JText::_('Delete current photo'); ?></label><input type="checkbox" name="delete" id="delete" />
		</td>
		<td colspan="2" rowspan="2">
			<?php
			if($this->fac->image_url)
			{
			?>
				<a href="<?=$this->fac->image_url;?>" class="modal">
					<img src="<?=$this->fac->image_url; ?>" alt="Image of <?=$this->fac->name?>" width="100" />
				</a>
				<input type="hidden" name="image_url" id="image_url" value="<?=$this->fac->image_url;?>" />
			<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<th colspan="4" align="left"><?=JText::_('JRESEARCH_DESCRIPTION').': '; ?></th>
	</tr>
	<tr>
		<td colspan="4"><?=$this->editor->display( 'description',  $this->fac->description , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="facilities" />
<input type="hidden" name="id" value="<?=$this->fac?$this->fac->id:'' ?>" />		
<?=JHTML::_('behavior.keepalive'); ?>
</form>