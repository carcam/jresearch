<?php 
$itemId = JRequest::getVar('Itemid');
?>
<div id="mod_jresearch_institutes">
	<?php
	foreach($institutes as $institute):
		$i = $institute['i'];
		$count = $institute['count'];
		$listitem = $i->name;
	?>
	<ul class="listing">
		<li>
			<a href="./?option=com_jresearch&amp;view=institute&amp;task=show&amp;id=<?php echo $i->id?>&amp;Itemid=<?php echo $itemId;?>" title="<?php echo $i->title; ?>">
				<?php echo $listitem; ?>
			</a>

			<a href="./?option=com_jresearch&amp;view=publicationssearch&amp;task=search&amp;limitstart=0&amp;limit=20&amp;key=<?php echo urlencode('"').$i->name.(urlencode('"')); ?>&amp;keyfield0=institute_name&amp;with_abstract=off&amp;pubtype=0&amp;language=0&amp;status=finished&amp;date_field=publication_date&amp;order_by1=date_descending&amp;order_by2=date_descending&amp;newSearch=1">
				<?php echo "(".$count.")"; ?>
			</a>
		</li>
	</ul>
	<?php 
	endforeach;
	?>
</div>
