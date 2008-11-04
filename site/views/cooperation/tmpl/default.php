<?php
/**
 * @package JResearch
 * @subpackage Cooperations
 * Default view for showing a single cooperation
 */

$contentArr = explode('<hr id="system-readmore" />', $this->item->description);
?>
<div class="componentheading">
	<?=JText::_('Cooperations');?>
	-
	<?=$this->item->name;?>
</div>
<?php 
if($this->item->image_url != "")
{
?>
	<img src="<?=$this->item->image_url;?>" title="Cooperation image of <?=$this->item->name?>" alt="Cooperation image of <?=$this->item->name?>" />
<?php
}
?>
<div class="content">
	<div>
		<strong>URL:</strong> <?=$this->item->url;?>
	</div>
	<div>
		<?=$contentArr[0];?>
	</div>
	<div style="text-align:left">
		<?=$contentArr[1];?>
	</div>
</div>