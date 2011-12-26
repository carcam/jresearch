<?php 
$className = "mod_latest_publications_".$params->get('moduleclass_sfx');
?>
<div class="<?php echo $className;  ?>">
	<?php
	foreach($papers as $paper):
		if($style != 'none'){
			$styleObj = JResearchCitationStyleFactory::getInstance($style, $paper->pubtype);
			$publicationText = $styleObj->getReferenceHTMLText($paper, true);
		}else{
			$publicationText = '<a href="./?option=com_jresearch&amp;view=publication&amp;task=show&amp;id='.$paper->id.'">'.($paper->title).'</a>';
		}
	?>
	<ul class="<?php echo $className; ?>">
		<li>
			<?php $team = modJResearchPapersHelper::getSponsorTeam($paper);?>
			<?php if(!empty($team)): ?>
				<div><a href="./?option=com_jresearch&amp;view=team&amp;task=show&amp;id=<?php echo $team['id']; ?>" title="<?php echo $team['name']; ?>"><?php echo $team['name']; ?></a></div>
			<?php endif; ?>	
			<div><?php echo $publicationText; ?></div>
		</li>
	</ul>
	<?php 
	endforeach;
	?>
</div>