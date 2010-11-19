<div id="mod_jresearch_institutes">
	<h2><?php echo JText::_('JRESEARCH_INSTITUTES'); ?></h2>
	<?php
	foreach($institutes as $institute):
		$listitem = $institute['i']->name." (".$institute['count'].")";
	?>
	<ul class="listing">
		<li><?php echo $listitem; ?></li>
	</ul>
	<?php 
	endforeach;
	?>
</div>