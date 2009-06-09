<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a list of cooperations
*/

defined("_JEXEC") or die("Restricted access");

?>
<h1 class="componentheading">
	<?php echo JText::_('JRESEARCH_TEAMS');?>
</h1>
<ul style="padding-left:0px;">
	<?php
	foreach($this->items as $team)
	{
	?>
		<li class="liteam" style="background: none; clear: both; margin-bottom: 10px;">
			<div style="width: 85%; margin-left: auto; margin-right: auto;">
				<?php
				$contentArray = explode('<hr id="system-readmore" />', $team->description);
				$itemId = JRequest::getVar('Itemid');
				?>
				<div class="contentheading">
					<?php echo JFilterOutput::ampReplace($team->name); ?>
				</div>
				<div>
					<?php $leader = $team->getLeader(); ?>
					<strong><?=JText::_('JRESEARCH_TEAM_LEADER');?>:</strong> <?php echo $leader->__toString(); ?>
				</div>
				<div style="text-align:left">
					<a href="index.php?option=com_jresearch&task=show&view=team&id=<?php echo $team->id.(isset($itemId)?'&Itemid='.$itemId:'');?>" >
						<?php echo JText::_('JRESEARCH_READ_MORE'); ?>
					</a>
				</div>
			</div>
			<div style="clear: both;">&nbsp;</div>
			<hr style="clear: both;" />
		</li>
	<?php
	}
	?>
</ul>
<div style="width:100%;text-align:center;">
	<?php echo $this->page->getResultsCounter()?><br />
	<?php echo $this->page->getPagesLinks()?>
</div>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="controller" value="teams"  />
<input type="hidden" name="limitstart" value="" />
<input type="hidden" name="hidemainmenu" value="" />