<?php
/**
* @package JResearch
* @subpackage Teams
* Default view for showing a single team
*/

$contentArr = explode('<hr id="system-readmore" />', $this->item->description);
?>
<h1 class="componentheading">
	<?php echo JText::_('JRESEARCH_TEAM');?>
	-
	<?php echo JFilterOutput::ampReplace($this->item->name);?>
</h1>
<div class="content">
	<div class="tr">
		<strong><?php echo JText::_('JRESEARCH_TEAM_LEADER');?>:</strong> <?php echo $this->item->getLeader()?>
	</div>
	<div class="tr">
		<strong><?php echo JText::_('JRESEARCH_TEAM_MEMBERS');?>:</strong> <ul><li><?php echo implode("</li><li> ", $this->item->getMembers())?></li></ul>
	</div>
	<?php
	//Show description only if description exists
	if($contentArr[0] != "")
	{
	?>
		<div>
			<strong><?php echo JText::_('Description')?>:</strong>
		</div>
		<div>
			<?php echo $contentArr[0];?>
		</div>
		<div style="text-align:left">
			<?php echo $contentArr[1];?>
		</div>
	<?php
	}
	?>
	<div style="text-align: center;"><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>
</div>