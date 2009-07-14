<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a single cooperation
*/

$contentArr = explode('<hr id="system-readmore" />', $this->coop->description);
?>
<h1 class="componentheading">
	<?php echo JText::_('JRESEARCH_COOPERATION');?>
	-
	<?php echo JFilterOutput::ampReplace($this->coop->name);?>
</h1>
<?php 
if($this->coop->image_url != "")
{
?>
	<img src="<?php echo JResearch::getUrlByRelative($this->coop->image_url);?>" title="<?php echo JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name))?>" alt="<?php echo JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name)?>" />
<?php
}

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