<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?=$this->fac->name; ?></div>
<?php 
if($this->fac->image_url)
{
?>
	<div>
		<img src="<?=$this->fac->image_url?>" alt="<?=$this->fac->name?>" title="<?=$this->fac->name?>" />
	</div>
<?php 
}
?>
<p>
	<?=$this->fac->description;?>
</p>
<div><a href="javascript:history.go(-1)"><?=JText::_('Back'); ?></a></div>