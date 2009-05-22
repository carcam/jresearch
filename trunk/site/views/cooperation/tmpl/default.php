<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a single cooperation
*/

$contentArr = explode('<hr id="system-readmore" />', $this->coop->description);
JHTML::_('behavior.modal');
?>
<h1 class="componentheading">
	<?php echo JText::_('JRESEARCH_COOPERATION');?>
	-
	<?php echo JFilterOutput::ampReplace($this->coop->name);?>
</h1>
<?php 
if($this->coop->image_url):
	$url = JResearch::getUrlByRelative($this->coop->image_url);
	$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->coop->image_url):$url;
?>
<div style="text-align: center;">
	<a href="<?php echo $url?>" class="modal" rel="{handler: 'image'}">
		<img src="<?php echo $thumb;?>" title="<?php echo JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name))?>" alt="<?php echo JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name)?>" />
	</a>
</div>
<?php
endif;

$ampReplacedUrl = JFilterOutput::ampReplace($this->coop->url);
?>
<div class="content">
	<div>
		<strong><?php echo JText::_('JRESEARCH_COOPERATION_URL');?></strong> <a href="<?php echo $ampReplacedUrl;?>"><?php echo $ampReplacedUrl;?></a>
	</div>
	<div>
		<?php echo $contentArr[0];?>
	</div>
	<div style="text-align:left">
		<?php echo $contentArr[1];?>
	</div>
</div>