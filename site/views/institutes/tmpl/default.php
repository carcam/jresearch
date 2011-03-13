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
		<li class="liinstitute">
			<div style="width: 95%; margin-left: auto; margin-right: auto;">
				<?php
				$contentArray = explode('<hr id="system-readmore" />', $institute->comment);
				?>
				<h2 class="contentheading">
					<?php echo JFilterOutput::ampReplace($institute->name)?>
				</h2>
				<?php if(!empty($institute->street)): ?>
					<em><?php echo $institute->street; ?></em><br />
				<?php endif; ?>
				<?php if(!empty($institute->street2)): ?>		
					<em><?php echo $institute->street2; ?></em><br />
				<?php endif; ?>
				<?php if(!empty($institute->zip)): ?>
					<em><?php echo $institute->zip; ?></em>
				<?php endif; ?>
				<?php if(!empty($institute->place)): ?>
				 	<em><?php echo $institute->place; ?></em>
				 <?php endif;?>	
				 <br />
				<?php if(!empty($institute->state_province)): ?>
					<em><?php echo $institute->state_province; ?></em>
				<?php endif; ?>
				<?php $country = $institute->getCountry(); ?>
				<?php if(!empty($country)): ?>
					<em><?php echo !empty($institute->state_province)?', ':''; ?><?php echo $country; ?></em>
				<?php endif; ?>		
				<p>
					<?php if(!empty($institute->url)):
							$ampReplacedUrl = JFilterOutput::ampReplace($institute->url);
					?>
					<strong><?php echo JText::_('JRESEARCH_INSTITUTE_URL');?>:</strong> <a href="<?php echo $ampReplacedUrl;?>"><?php echo $ampReplacedUrl;?></a>
					<?php endif; ?>
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