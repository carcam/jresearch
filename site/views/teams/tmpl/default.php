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
<ul class="jresearch-team-list">
	<?php
	foreach($this->items as $team):
	?>
		<li>
			<div>
				<h3 class="contentheading">
					<?php echo JFilterOutput::ampReplace($team->name)?>
				</h3>
				<?php $leader = $team->getLeader(); ?>				
				<?php if(!empty($leader)): ?>
					<div>
						<h4><?php echo JText::_('JRESEARCH_TEAM_LEADER');?></h4> <?php echo !empty($leader)?JResearchPublicationsHelper::formatAuthor($leader->__toString(), $this->format):'';?>
					</div>
				<?php endif; ?>
				<div style="text-align:left">
					<?php echo JHTML::_('jresearch.link', JText::_('JRESEARCH_READ_MORE'), 'team', 'show', $team->id); ?>
				</div>
			</div>
			<div class="divEspacio" style="clear: both;"></div>
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