<?php 
$itemId = JRequest::getVar('Itemid');
?>
<div id="mod_jresearch_institutes">
	<?php
	foreach($institutes as $institute):
		$i = $institute['i'];
		$count = $institute['count'];
		$listitem = $i->name." (".$count.")";
	?>
	<ul class="listing">
		<li>
			<a href="./?option=com_jresearch&amp;view=institute&amp;task=show&amp;id=<?php echo $i->id?>&amp;Itemid=<?php echo $itemId;?>" title="<?php echo $i->title; ?>">
				<?php echo $listitem; ?>
			</a>
		</li>
	</ul>
	<?php 
	endforeach;
	?>
</div>