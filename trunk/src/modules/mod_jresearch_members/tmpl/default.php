<?php // no direct access
/**
* @package		JResearch
* @subpackage 	Modules
* @license		GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$itemId = JRequest::getVar('Itemid');
?>
<ul id="jresearch-members" class="menu<?php echo $params->get('moduleclass_sfx')?>">
	<?php
	foreach($members as $member)
	{
	?>
		<li>
			<a href="./?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?php echo $member->id?>&amp;Itemid=<?php echo $itemId;?>" title="<?php echo $member->firstname.' '.$member->lastname?>">
				<span>
					<?php echo $member->firstname.' '.$member->lastname?>
				</span>
			</a>
		</li>
	<?php 
	}
	?>
</ul>