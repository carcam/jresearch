<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for showing a list of cooperations
*/

defined("_JEXEC") or die("Restricted access");

?>
<h2 class="componentheading">
	<?php echo JText::_('JRESEARCH_TEAMS');?>
</h2>
<?php
if(count($this->items) > 0):
?>
<ul style="padding-left:0px;">
	<?php
	foreach($this->items as $team):
	?>
		<li class="liteam" style="background: none; clear: both; margin-bottom: 10px;">
			<div style="width: 85%; margin-left: auto; margin-right: auto;">
				<h3 class="contentheading">
					<?php echo JFilterOutput::ampReplace($team->name)?>
				</h3>
				<div>
					<?php $leader = $team->getLeader(); ?>
					<h4><?php echo JText::_('JRESEARCH_TEAM_LEADER');?></h4> <?php echo !empty($leader)?$leader->__toString():'';?>
				</div>
				<div style="text-align:left">
					<?php echo JHTML::_('jresearch.link', JText::_('JRESEARCH_READ_MORE'), 'team', 'show', $team->id); ?>
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
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="controller" value="teams"  />
<input type="hidden" name="limitstart" value="" />
<input type="hidden" name="hidemainmenu" value="" />