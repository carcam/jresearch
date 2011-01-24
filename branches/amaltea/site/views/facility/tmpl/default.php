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
<h2 class="componentheading"><?php echo $this->fac->name; ?></h2>
<?php 
if($this->fac->image_url):
	$url = JResearch::getUrlByRelative($this->fac->image_url);
	$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->fac->image_url):$url;
?>
<?php if(!empty($this->fac->id_team)): ?>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_SPONSOR_TEAM'); ?></h3>
<p>
<?php 
	$team = $this->fac->getTeam();
	echo JHTML::_('jresearch.link', $team->name, 'team', 'show', $team->id);
?>
</p>
<?php endif; ?>
<div style="text-align: center;">
	<a href="<?php echo $url?>" class="modal" rel="{handler: 'image'}">
		<img src="<?php echo $thumb?>" alt="<?php echo JText::sprintf('JRESEARCH_FACILITY_IMAGE_OF', $this->fac->name)?>" title="<?php echo JText::sprintf('JRESEARCH_FACILITY_IMAGE_OF', $this->fac->name)?>"  />
	</a>
</div>

<?php 
endif;
if($this->description):
	foreach($this->description as $content):
?>
	<?php echo $content; ?>
<?php
	endforeach;
endif;
?>
<div>
	<a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a>
</div>