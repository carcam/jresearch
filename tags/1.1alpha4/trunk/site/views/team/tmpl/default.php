<?php
/**
* @package JResearch
* @subpackage Teams
* Default view for showing a single team
*/

$contentArr = explode('<hr id="system-readmore" />', $this->item->description);
?>
<div class="componentheading">
	<?=JText::_('JRESEARCH_TEAM');?>
	-
	<?=JFilterOutput::ampReplace($this->item->name);?>
</div>
<div class="content">
	<div class="tr">
		<strong><?=JText::_('JRESEARCH_TEAM_LEADER');?>:</strong> <?=$this->item->getLeader()?>
	</div>
	<div class="tr">
		<strong><?=JText::_('JRESEARCH_TEAM_MEMBERS');?>:</strong> <ul><li><?=implode("</li><li> ", $this->item->getMembers())?></li></ul>
	</div>
	<?php
	//Show description only if description exists
	if($contentArr[0] != "")
	{
	?>
		<div>
			<strong><?=JText::_('Description')?>:</strong>
		</div>
		<div>
			<?=$contentArr[0];?>
		</div>
		<div style="text-align:left">
			<?=$contentArr[1];?>
		</div>
	<?php
	}
	?>
	<div style="text-align: center;"><a href="javascript:history.go(-1)"><?=JText::_('Back'); ?></a></div>
</div>