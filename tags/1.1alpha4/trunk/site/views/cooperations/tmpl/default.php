<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a list of cooperations
*/

defined("_JEXEC") or die("Restricted access");

$intro_text = $this->params->get('intro_text');
?>
<div class="componentheading">
	<?=JText::_('Cooperations');?>
</div>
<?php
if($intro_text != "")
{
?>
	<div><?=$intro_text?></div>
	<div>&nbsp;</div>
<?php
}
?>
<ul style="padding-left:0px;">
	<?php
	foreach($this->items as $coop)
	{
	?>
		<li class="licooperation" style="background: none; clear: both; margin-bottom: 10px;">
			<?php 
			if($coop->image_url != "")
			{
			?>
				<img src="<?=$coop->image_url;?>" title="<?=JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $coop->name))?>" alt="<?=JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $coop->name))?>" style="float: left; margin-right: 10px;" />
			<?php 
			}
			?>
			<div style="width: 85%; margin-left: auto; margin-right: auto;">
				<?php
				$contentArray = explode('<hr id="system-readmore" />', $coop->description);
				$itemId = JRequest::getVar('Itemid');
				?>
				<div class="contentheading">
					<a href="<?=JFilterOutput::ampReplace($coop->url)?>">
						<?=JFilterOutput::ampReplace($coop->name)?>
					</a>
				</div>
				<?php 
				if($contentArray[0] != "")
				{
				?>
					<div class="description">
						<?=$contentArray[0];?>
					</div>
				<?php
				}
				
				if(count($contentArray) > 1)
				{
				?>
					<div style="text-align:left">
						<a href="index.php?option=com_jresearch&task=show&view=cooperation&id=<?=$coop->id.(isset($itemId)?'&Itemid='.$itemId:'');?>" >
							<?=JText::_('Read more...'); ?>
						</a>
					</div>
				<?php 
				}
				?>
			</div>
			<div style="clear: both;">&nbsp;</div>
			<hr style="clear: both;" />
		</li>
	<?php
	}
	?>
</ul>
<div style="width:100%;text-align:center;">
	<?=$this->page->getResultsCounter()?><br />
	<?=$this->page->getPagesLinks()?>
</div>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="controller" value="cooperations"  />
<input type="hidden" name="limitstart" value="" />
<input type="hidden" name="hidemainmenu" value="" />