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
			<input name="name" id="name" size="80" maxlength="255" value="<?php echo $this->fac?$this->fac->name:'' ?>" class="required" />
			<br />
			<label for="name" class="labelform"><?php echo JText::_('JRESEARCH_FACILITY_PROVIDE_VALID_NAME'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></th>		
		<td><?php echo $this->areasList; ?></td>
		<th><?php echo JText::_('Published').': '; ?></th>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<th class="title" colspan="4"><?php echo JText::_('JRESEARCH_OPTIONAL'); ?></th>
	</tr>
	<tr>
		<th>
			<?php echo JText::_('JRESEARCH_FACILITY_IMAGE').': '; ?>
		</th>
		<td>
			<input type="file" name="inputfile" id="inputfile" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', 1024, 768)); ?><br />
			<label for="delete" /><?php echo JText::_('Delete current photo'); ?></label><input type="checkbox" name="delete" id="delete" />
		</td>
		<td colspan="2" rowspan="2">
			<?php
			if($this->fac && !is_null($this->fac->image_url)):
				$url = JResearch::getUrlByRelative($this->fac->image_url);
				$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->fac->image_url):$url;
			?>
				<a href="<?php echo $url;?>" class="modal">
					<img src="<?php echo $thumb; ?>" alt="Image of <?php echo $this->fac->name?>" width="100" />
				</a>
				<input type="hidden" name="image_url" id="image_url" value="<?php echo $this->fac->image_url;?>" />
			<?php
			endif;
			?>
		</td>
	</tr>
	<tr>
		<th colspan="4" align="left"><?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?></th>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->fac?$this->fac->description:'' , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>

<input type="hidden" name="id" value="<?php echo $this->fac?$this->fac->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'facilities'); ?>	
<?php echo JHTML::_('behavior.keepalive'); ?>
</form>