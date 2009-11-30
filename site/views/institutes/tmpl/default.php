<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a list of cooperations
*/

defined("_JEXEC") or die("Restricted access");

?>
<h1 class="componentheading">
	<?php echo JText::_('JRESEARCH_INSTITUTES');?>
</h1>
<?php
if(count($this->items) > 0):
?>
<ul id="jresearch-institute-list" style="padding-left:0px;">
	<?php
	$lastCat = -1;
	
	foreach($this->items as $institute):
	?>
		<li class="liinstitute" style="background: none; clear: both; margin-bottom: 10px;">
			<?php 
			if($institute->image_url):
				$url = JResearch::getUrlByRelative($institute->image_url);
			?>
				<img src="<?php echo $url;?>" title="<?php echo JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $institute->name))?>" alt="<?php echo JFilterOutput::ampReplace(JText::sprintf('JRESEARCH_COOPERATION_IMAGE_OF', $institute->name))?>" style="float: left; margin-right: 10px;" />
			<?php 
			endif;
			?>
			<div style="width: 85%; margin-left: auto; margin-right: auto;">
				<?php
				$contentArray = explode('<hr id="system-readmore" />', $institute->comment);
				?>
				<h2 class="contentheading">
					<?php echo JFilterOutput::ampReplace($institute->name)?>
				</h2>
				<p>
					<em><?php echo $institute->street; ?></em><br />
					<em><?php echo $institute->zip; ?></em> <em><?php echo $institute->place; ?></em>
				</p>
				<p>
					<a href="<?php echo $institute->url?>">
						<?php echo substr($institute->url, 0, 256)."..."; ?>
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
				<p style="text-align:left">
					<?php echo JHTML::_('jresearch.link', JText::_('JRESEARCH_READ_MORE'), 'institute', 'show', $institute->id); ?>
				</p>
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