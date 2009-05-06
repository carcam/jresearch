<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$itemId = JRequest::getVar('Itemid');
?>
<ul id="jresearch-facilities" class="menu<?=$params->get('moduleclass_sfx')?>">
	<?php 
	if(count($areas) > 0)
	{
		foreach($areas as $area)
		{
			$areaItem = JResearchModelResearchArea::getItem($area);
	?>
		<li>
			<h5><?=$areaItem->name?></h5>
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
							<a href="./?option=com_jresearch&amp;view=facility&amp;task=show&amp;id=<?=$fac->id?>&amp;Itemid=<?=$itemId;?>" title="<?=$fac->name?>">
								<span><?=$fac->name?></span>
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
						<span><?=JText::_('NO_RECORDS')?></span>
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