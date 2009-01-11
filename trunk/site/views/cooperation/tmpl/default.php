<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a single cooperation
*/

$contentArr = explode('<hr id="system-readmore" />', $this->coop->description);
?>
<h1 class="componentheading">
	<?=JText::_('JRESEARCH_COOPERATION');?>
	-
	<?=JFilterOutput::ampReplace($this->coop->name);?>
</h1>
<?php 
if($this->coop->image_url != "")
{
?>
	<img src="<?=$this->coop->image_url;?>" title="<?=JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name))?>" alt="<?=JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name)?>" />
<?php
}

?>
<div class="content">
	<div>
		<strong><?=JText::_('JRESEARCH_COOPERATION_URL');?></strong> <?=JFilterOutput::ampReplace($this->coop->url);?>
	</div>
	<div>
		<?=$contentArr[0];?>
	</div>
	<div style="text-align:left">
		<?=$contentArr[1];?>
	</div>
</div>