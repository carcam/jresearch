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
<h1 class="componentheading"><?php echo $this->fac->name; ?></h1>
<?php 
if($this->fac->image_url):
	$url = JResearch::getUrlByRelative($this->fac->image_url);
?>
<div style="text-align: center;">
	<a href="<?php echo $url?>" class="modal" rel="{handler: 'image'}">
		<img src="<?php echo $url?>" alt="<?php echo JText::sprintf('JRESEARCH_FACILITY_IMAGE_OF', $this->fac->name)?>" title="<?php echo JText::sprintf('JRESEARCH_FACILITY_IMAGE_OF', $this->fac->name)?>" style="width: 500px;" />
	</a>
</div>
<?php 
endif;
?>
<p>
<?php echo $this->fac->description?$this->fac->description:'&nbsp;'?>
</p>
<div style="text-align: center;">
	<a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a>
</div>