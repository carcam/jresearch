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
		<li><?php echo $listitem; ?></li>
	</ul>
	<?php 
	endforeach;
	?>
</div>