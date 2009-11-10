<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a single cooperation
*/
JHTML::_('behavior.modal');
?>
<h2 class="componentheading">
	<?php echo JText::_('JRESEARCH_COOPERATION');?>
	-
	<?php echo $this->coop->name;?>
</h2>
<?php 
if($this->coop->image_url):
	$url = JResearch::getUrlByRelative($this->coop->image_url);
	$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->coop->image_url):$url;
?>
<div style="text-align: center;">
	<a href="<?php echo $url?>" class="modal" rel="{handler: 'image'}">
		<img src="<?php echo $thumb;?>" title="<?php echo JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name)?>" alt="<?php echo JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name)?>" />
	</a>
</div>
<?php
endif;

$ampReplacedUrl = JFilterOutput::ampReplace($this->coop->url);
?>
<div class="content">
	<p>
		<strong><?php echo JText::_('JRESEARCH_COOPERATION_CATEGORY'); ?>:</strong> <?php echo $this->coop->getCategory()->title; ?>
	</p>
	<p>

		<strong><?php echo JText::_('JRESEARCH_COOPERATION_URL');?>:</strong> <a href="<?php echo $ampReplacedUrl;?>"><?php echo $ampReplacedUrl;?></a>

	</p>
	<?php
	if($this->description):
		foreach($this->description as $content):
	?>
	<p>
		<?php echo $content;?>
	</p>
	<?php
		endforeach;
	endif;
	?>
</div>