<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a single cooperation
*/

$contentArr = explode('<hr id="system-readmore" />', $this->coop->description);
$ampReplacedUrl = JFilterOutput::ampReplace($this->coop->url);
?>
<h1 class="componentheading">
	<?php echo JText::_('JRESEARCH_COOPERATION');?>
	-
	<?php echo JFilterOutput::ampReplace($this->coop->name);?>
</h1>
<?php 
if($this->coop->image_url != ""):
?>
	<img src="<?php echo JResearch::getUrlByRelative($this->coop->image_url);?>" title="<?php echo JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name))?>" alt="<?php echo JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $this->coop->name)?>" />
<?php
endif;
?>
<h2 class="contentheading">
	<?php echo JText::_('JRESEARCH_COOPERATION_GENERAL');?>
</h2>
<div class="content">
	<div>
		<strong><?php echo JText::_('JRESEARCH_COOPERATION_URL').': ';?></strong> <a href="<?php echo $ampReplacedUrl;?>"><?php echo $ampReplacedUrl;?></a>
	</div>
	<div>
		<?php echo $contentArr[0];?>
	</div>
	<div style="text-align:left">
		<?php echo $contentArr[1];?>
	</div>
</div>
<h2 class="contentheading">
	<?php echo JText::_('JRESEARCH_COOPERATION_INTERCHANGE');?>
</h2>
<div class="content">
	<div>
		<strong><?php echo JText::_('JRESEARCH_COOPERATION_PERSON_NAME').': ';?></strong> <?php echo $this->coop->person_name;?>
	</div>
	<div>
		<strong><?php echo JText::_('JRESEARCH_COOPERATION_TOI').': ';?></strong> <?php echo JText::_('JRESEARCH_COOPERATION_INTERCHANGE_TYPE_'.$this->coop->type_ic);?>
	</div>
	<div>
		<strong><?php echo JText::_('JRESEARCH_START_DATE').' - '.JText::_('JRESEARCH_DEADLINE').': ';?></strong> <?php echo $this->coop->start_date;?> - <?php echo $this->coop->end_date;?>
	</div>
	<div>
		<strong><?php echo JText::_('JRESEARCH_COOPERATION_RECEPTOR').': ';?></strong> <?php echo $this->team->getItem($this->coop->receptor);?>
	</div>
	<div>
		<strong><?php echo JText::_('JRESEARCH_COOPERATION_EMISOR').': ';?></strong> <?php echo $this->team->getItem($this->coop->emisor);?>
	</div>
</div>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>