<?php
/**
* @package JResearch
* @subpackage Teams
* Default view for showing a single team
*/

$contentArr = explode('<hr id="system-readmore" />', $this->item->description);

$db =& JFactory::getDBO();
?>
<div class="componentheading">
	<?=JText::_('JRESEARCH_TEAM');?>
	-
	<?=JFilterOutput::ampReplace($this->item->name);?>
</div>
<div class="content">
	<div class="tr">
		<strong><?=JText::_('JRESEARCH_TEAM_LEADER');?>:</strong> <?=$this->item->getLeader($db)?>
	</div>
	<div class="tr">
		<strong><?=JText::_('JRESEARCH_TEAM_MEMBERS');?>:</strong> <ul><li><?=implode("</li><li> ", $this->item->getMembers($db))?></li></ul>
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
</div>