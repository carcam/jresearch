<?php 
$itemId = JRequest::getVar('Itemid');
?>
<div id="mod_jresearch_papers">
	<h2><?php echo JText::_('JRESEARCH_PAPERS'); ?></h2>
	<?php
	foreach($papers as $paper):
		$listitem = $paper->title;
		
		switch($type)
		{
			case "most_viewed":
				$listitem .= " (".$paper->hits.")";
				break;
			default:
				break;
		}
	?>
	<ul class="listing">
		<li>
			<a href="./?option=com_jresearch&amp;view=publication&amp;task=show&amp;id=<?php echo $paper->id?>&amp;Itemid=<?php echo $itemId;?>" title="<?php echo $paper->title; ?>">
				<?php echo $listitem; ?>
			</a>
		</li>
	</ul>
	<?php 
	endforeach;
	?>
</div>