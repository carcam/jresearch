<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a single cooperation
*/

$contentArr = explode('<hr id="system-readmore" />', $this->coop->description);
?>
<div class="componentheading">
	<?=JText::_('Cooperations');?>
	-
	<?=$this->coop->name;?>
</div>
<?php 
if($this->coop->image_url != "")
{
?>
	<img src="<?=$this->coop->image_url;?>" title="Cooperation image of <?=$this->coop->name?>" alt="Cooperation image of <?=$this->coop->name?>" />
<?php
}
?>
<div class="content">
	<div>
		<strong>URL:</strong> <?=$this->coop->url;?>
	</div>
	<div>
		<?=$contentArr[0];?>
	</div>
	<div style="text-align:left">
		<?=$contentArr[1];?>
	</div>
</div>