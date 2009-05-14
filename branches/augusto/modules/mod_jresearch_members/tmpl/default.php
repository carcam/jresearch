<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$itemId = JRequest::getVar('Itemid');
?>
<ul id="jresearch-members" class="menu<?=$params->get('moduleclass_sfx')?>">
	<?php
	foreach($members as $member)
	{
	?>
		<li>
			<a href="./?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?=$member->id?>&amp;Itemid=<?=$itemId;?>" title="<?=$member->firstname.' '.$member->lastname?>">
				<span>
					<?=$member->firstname.' '.$member->lastname?>
				</span>
			</a>
		</li>
	<?php 
	}
	?>
</ul>