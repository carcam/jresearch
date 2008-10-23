<?php
$contentArr = explode('<hr id="system-readmore" />', $this->item->description);
?>
<div class="contentheading">
	<?=$this->item->name;?>
</div>
<img src="<?=$this->item->image_url;?>" title="Cooperation image of <?=$this->item->name?>" alt="Cooperation image of <?=$this->item->name?>" />
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