<?php
/**
* @package JResearch
* @subpackage Teams
* Default view for showing a single team
*/

$contentArr = explode('<hr id="system-readmore" />', $this->item->description);
?>
<h1 class="componentheading">
	<?=JText::_('JRESEARCH_TEAM');?>
	-
	<?=JFilterOutput::ampReplace($this->item->name);?>
</h1>
<div class="content">
	<div class="tr">
		<?php $leader = $this->item->getLeader(); ?>
		<strong><?=JText::_('JRESEARCH_TEAM_LEADER');?>:</strong> <?php echo !empty($leader)?$leader->__toString():''; ?>
	</div>
	<div class="tr">
		<?php 
		$members = $this->item->getMembers();
		$membersStrings = array();		
		foreach($members as $member)
			$membersStrings[] = $member->__toString(); 
		?>
		<strong><?=JText::_('JRESEARCH_TEAM_MEMBERS');?>:</strong> <ul><li><?php echo implode("</li><li> ", $membersStrings); ?></li></ul>
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
			<?php echo $contentArr[0]; ?>
		</div>
		<div style="text-align:left">
			<?php echo $contentArr[1]; ?>
		</div>
	<?php
	}
	?>
	<div style="text-align: center;"><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>
</div>