<div id="mod_jresearch_papers">
	<h2><?php echo JText::_('JRESEARCH_PAPERS'); ?></h2>
	<?php
	foreach($papers as $paper):
	?>
	<ul class="listing">
		<li><?php echo $paper->title.(($type == "most_viewed")?(" (".$paper->hits.")"):""); ?></li>
	</ul>
	<?php 
	endforeach;
	?>
</div>