<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$itemId = JRequest::getVar('Itemid');
?>
<ul id="jresearch-facilities" class="menu<?php echo $params->get('moduleclass_sfx')?>">
	<?php 
	if(count($areas) > 0)
	{
		foreach($areas as $area)
		{
			$areaItem = JResearchModelResearchArea::getItem($area);
	?>
		<li>
			<h5><?php echo $areaItem->name?></h5>
			<ul>
				<?php
				if(count($facs) > 0)
				{
					foreach($facs as $fac)
					{
						if($fac->id_research_area == $area)
						{
					?>
						<li>
							<a href="./?option=com_jresearch&amp;view=facility&amp;task=show&amp;id=<?php echo $fac->id?>&amp;Itemid=<?php echo $itemId;?>" title="<?php echo $fac->name?>">
								<span><?php echo $fac->name?></span>
							</a>
						</li>
				<?php 
						}
					}
				}
				else
				{
				?>
					<li>
						<span><?php echo JText::_('NO_RECORDS')?></span>
					</li>
				<?php
				}
				?>
			</ul>
		</li>
	<?php 
		}
	}
	?>
</ul>