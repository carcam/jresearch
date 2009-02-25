<?php
/**
 * @package JResearch
 * @subpackage Facilities
 * Default view for showing a single facility
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');

?>
<h1 class="componentheading"><?=$this->fac->name; ?></h1>
<?php 
if($this->fac->image_url):
	$url = JResearch::getUrlByRelative($this->fac->image_url);
?>
<div style="text-align: center;">
	<a href="<?=$url?>" class="modal" rel="{handler: 'image'}">
		<img src="<?=$url?>" alt="<?=JText::sprintf('JRESEARCH_FACILITY_IMAGE_OF', $this->fac->name)?>" title="<?=JText::sprintf('JRESEARCH_FACILITY_IMAGE_OF', $this->fac->name)?>" style="width: 500px;" />
	</a>
</div>
<?php 
endif;
?>
<p>
<?=$this->fac->description?$this->fac->description:'&nbsp;'?>
</p>
<div style="text-align: center;">
	<a href="javascript:history.go(-1)"><?=JText::_('Back'); ?></a>
</div>