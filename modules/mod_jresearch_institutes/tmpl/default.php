<div id="mod_jresearch_institutes">
	<h2><?php echo JText::_('JRESEARCH_INSTITUTES'); ?></h2>
	<?php
	foreach($institutes as $institute):
	?>
	<ul class="listing">
		<li><?php echo $institute['i']->name." (".$institute['count'].")"; ?></li>
	</ul>
	<?php 
	endforeach;
	?>
</div>