<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a list of cooperations
*/

defined("_JEXEC") or die("Restricted access");

?>
<h2 class="componentheading">
	<?php echo JText::_('JRESEARCH_COOPERATIONS');?>
</h2>
<?php
if(!empty($this->intro_text)):
?>
	<p style="text-align: justify;"><?php echo $this->intro_text?></p>
<?php
endif;

if(count($this->items) > 0):
?>
<ul id="jresearch-cooperation-list" style="padding-left:0px;">
	<?php
	$lastCat = -1;
	
	foreach($this->items as $coop):
		if($lastCat != $coop->catid && $coop->catid != 0):
			$print = null;
			foreach($this->cats as $key=>$cat)
			{
				if($cat->cid == $coop->catid)
				{
					$print = $cat;
					unset($this->cats[$key]);
					
					break;
				}
			}
			//print Category
			?>
			<li style="background: none; list-style-type: none; text-align: center;">
				<?php 
				if(!empty($print->image)):
				?>
					<img src="images/stories/<?php echo $print->image?>" alt="<?php echo $print->title?> Cooperation category" title="<?php echo $print->title?> Cooperation category" />
				<?php 
				else:
					?>
					<h3><?php echo $print->title?></h3>
					<?php
				endif;
				?>
			</li>
			<?php
			$lastCat = $coop->catid;
		endif;
	?>
		<li class="licooperation" style="background: none; clear: both; margin-bottom: 10px;">
			<?php 
			if($coop->image_url):
				$url = JResearch::getUrlByRelative($coop->image_url);
			?>
				<img src="<?php echo $url;?>" title="<?php echo JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $coop->name))?>" alt="<?php echo JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $coop->name))?>" style="float: left; margin-right: 10px;" />
			<?php 
			endif;
			?>
			<div style="width: 85%; margin-left: auto; margin-right: auto;">
				<?php
				$contentArray = explode('<hr id="system-readmore" />', $coop->description);
				?>
				<h4 class="contentheading">
					<?php echo JFilterOutput::ampReplace($coop->name)?>
				</h4>
				<p>
					<strong>Website:</strong> 
					<a href="<?php echo $coop->url?>">
						<?php echo $coop->url?>
					</a>
				</p>
				<?php 
				if(!empty($contentArray[0])):
				?>
					<p style="text-align: justify;" class="description">
						<?php echo $contentArray[0];?>
					</p>
				<?php
				endif;
				?>
				<div style="text-align:left">
					<?php echo JHTML::_('jresearch.link', JText::_('JRESEARCH_READ_MORE'), 'cooperation', 'show', $coop->id); ?>
				</div>
			</div>
			<div style="clear: both;">&nbsp;</div>
		</li>
	<?php
	endforeach;
	?>
</ul>
<?php
endif;
?>
<div style="width:100%;text-align:center;">
	<?php echo $this->page->getResultsCounter()?><br />
	<?php echo $this->page->getPagesLinks()?>
</div>