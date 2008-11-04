<?php
/**
 * @package JResearch
 * @subpackage Cooperations
 * Default view for showing a list of cooperations
 */

defined("_JEXEC") or die("Restricted access");
?>
<div class="componentheading">
	<?=JText::_('Cooperations');?>
</div>
<ul style="padding-left:0px;">
	<?php
	foreach($this->items as $coop)
	{
	?>
		<li class="licooperation" style="background: none; clear: both;">
			<?php 
			if($coop->image_url != "")
			{
			?>
				<img src="<?=$coop->image_url;?>" title="Cooperation image of <?=$coop->name?>" alt="Cooperation image of <?=$coop->name?>" style="float: left; margin-right: 10px;" />
			<?php 
			}
			?>
			<div>
				<?php
				$contentArray = explode('<hr id="system-readmore" />', $coop->description);
				$itemId = JRequest::getVar('Itemid');
				?>
				<div class="contentheading">
					<a href="<?=$coop->url?>">
						<?=$coop->name;?>
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
				?>
				<div style="text-align:left">
					<a href="index.php?option=com_jresearch&task=show&view=cooperation&id=<?=$coop->id.(isset($itemId)?'&Itemid='.$itemId:'');?>" >
						<?=JText::_('Read more...'); ?>
					</a>
				</div>
			</div>
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