<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for adding/editing a cooperation
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
?>
<form name="adminForm" id="adminForm" action="./" method="post" enctype="multipart/form-data" class="form-validate" onsubmit="return validate(this);">
<table class="edit" cellpadding="5" cellspacing="5">
<thead>
	<tr>
		<th colspan="4"><?=JText::_('JRESEARCH_COOPERATION'); ?></th>
	</tr>
</thead>
<tbody>
	<tr>
		<th><?php echo JText::_('JRESEARCH_COOPERATION_NAME').': '?></th>
		<td>
			<input name="name" id="name" size="50" maxlength="100" class="required" value="<?php echo $this->coop?$this->coop->name:'' ?>" />
			<br /><label for="name" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_NAME'); ?></label>
		</td>
		<th><?php echo JText::_('JRESEARCH_COOPERATION_URL').': '; ?></th>
		<td>
			<input name="url" id="url" size="50" maxlength="255" class="required" value="<?php echo $this->coop?$this->coop->url:''; ?>" />
			<br /><label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>			
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('Published').': '; ?></th>
		<td><?php echo $this->publishedList; ?></td>
		<th><?php echo JText::_('Order').': '; ?></th>
		<td><?php echo $this->orderList; ?></td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_COOPERATION_CATEGORIES').': '?></th>
		<td colspan="3">
			<?=$this->categoryList?>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('Photo').': ' ?></th>
		<td>
			<input class="inputbox" name="inputfile" id="inputfile" type="file" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', _COOPERATION_IMAGE_MAX_WIDTH_, _COOPERATION_IMAGE_MAX_HEIGHT_)); ?><br />
		</td>
		<?php
		if($this->coop && $this->coop->image_url):
			$url = JResearch::getUrlByRelative($this->coop->image_url);
			$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->coop->image_url):$url;
		?>
		<td>
			<a href="<?=$url?>" class="modal">
				<img src="<?=$thumb;?>" alt="<?php echo JText::_('Photo'); ?>" class="modal" />
			</a>
			<input type="hidden" name="image_url" id="image_url" value="<?=$this->coop->image_url;?>" />
		</td>
		<td><label for="delete"><?php echo JText::_('JRESEARCH_DELETE_CURRENT_PHOTO'); ?></label><input type="checkbox" name="delete" id="delete" /></td>
		<?php
		else:
		?>
		<td colspan="2"></td>
		<?php
		endif;
		?>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->coop?$this->coop->description:'' , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
</tbody>
</table>

<input type="hidden" name="id" value="<?php echo $this->coop?$this->coop->id:'' ?>" />

<?php echo JHTML::_('jresearchhtml.hiddenfields', 'cooperations'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>	
</form>