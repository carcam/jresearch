<?php
/**
* @package JResearch
* @subpackage Teams
* Default view for showing a single team
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 
$Itemid = JRequest::getVar('Itemid', 0);
?>
<h2 class="componentheading">
	<?php echo JText::_('JRESEARCH_TEAM');?>
	-
	<?php echo JFilterOutput::ampReplace($this->item->name);?>
</h2>
<div class="divEspacio"></div>
<style>
table.teamdescription{
	width: 100%;
}

table.teamdescription th{
	width: 20%;
}
</style>
<table summary="<?php echo JText::_('JRESEARCH_TEAM_SUMMARY'); ?>" class="teamdescription">
	<tbody>
		<tr>
			<th><?php echo JText::_('JRESEARCH_TEAM_LEADER');?>:</th>
			<td>
				<?php if(!empty($this->leader)): ?>
					<?php $textLeader = JResearchPublicationsHelper::formatAuthor($this->leader->__toString(), $this->format); ?>
					<span><?php echo $this->leader->published? JHTML::_('jresearch.link',$textLeader, 'member', 'show', $this->leader->id, true) : $textLeader; ?></span>
					<?php echo !empty($this->leader->tagline)? '<span>('.$this->leader->tagline.')</span>' : '';   ?>					
				<?php endif; ?>
			</td>	
			<td rowspan="3">
				<?php
			   	if(!empty($this->item->logo)): 
		       		$url = JResearch::getUrlByRelative($this->item->logo);
		       		$thumb = ($this->enableThumbnails == 1)?JResearch::getThumbUrlByRelative($this->item->logo):$url;
		       	?>		
		    			<a href="<?php echo $url?>" class="modal" rel="{handler: 'image'}">
		    				<img src="<?php echo $thumb; ?>" border="0" alt="<?php echo $this->item->name; ?>" />
		    			</a>
		    	<?php endif; ?>
			</td>
		</tr>
		<?php $researchArea = $this->item->getResearchArea(); 
			  if(!empty($researchArea)):	
		?>		
		<tr>
			<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '; ?></th>
			<?php if($researchArea->published): ?>
				<td><?php echo JHTML::_('jresearch.link', $researchArea->name, 'researchArea', 'show', $researchArea->id); ?></td>
			<?php else: ?>
				<td><?php echo $researchArea->name; ?></td>
			<?php endif;?>	
		</tr>		
		<?php endif; ?>
		<?php if(!empty($this->leader)): ?>		
				<?php 
				if($this->leader->position):
				?>
				<tr>
					<th><?php echo JText::_('Position').': ' ?></th>
					<td><?php $position = $this->leader->getPosition(); 
					echo $position->position; ?></td>
				</tr>
				<?php 
				endif;
				
				if($this->leader->location):
				?>
				<tr>
					<th><?php echo JText::_('JRESEARCH_LOCATION'); ?></th>
					<td><?php echo $this->leader->location; ?></td>
				</tr>
				<?php 
				endif;
				
				if($this->leader->phone_or_fax):
				?>
				<tr>
					<th><?php echo JText::_('JRESEARCH_PHONE').': ' ?></th>
					<td colspan="2"><?php echo $this->leader->phone; ?></td>
				</tr>
				<?php 
				endif;
				
				if($this->leader->email):
				?>
				<tr>
					<th><?php echo JText::_('Email').' :' ?></th>
					<td colspan="2"><?php echo JHTML::_('email.cloak',$this->leader->email, false); ?></td>
				</tr>
				<?php 
				endif;
				?>
		<?php endif;?>	
		<?php if(!empty($this->memberLinks)): ?>	
		<tr>
			<th><?php echo JText::_('JRESEARCH_TEAM_MEMBERS');?>:</th>
			<td colspan="2">
				<ul><li><?php echo implode("</li><li> ", $this->memberLinks)?></li></ul>
			</td>
		</tr>
		<?php endif; ?>
		<?php if(!empty($this->formerMemberLinks)): ?>
		<tr>
			<th><?php echo JText::_('JRESEARCH_FORMER_TEAM_MEMBERS');?>:</th>
			<td colspan="2">
				<ul><li><?php echo implode("</li><li> ", $this->formerMemberLinks)?></li></ul>
			</td>
		</tr>
		<?php endif; ?>
		<?php
		//Show description only if description exists
		if(count($this->description) > 0 && !empty($this->description[0])):
		?>
		<tr>
			<th colspan="3" scope="colgroup">
				<?php echo JText::_('Description')?>:
			</th>
		</tr>
		<tr>
			<td colspan="3">
				<?php
				foreach($this->description as $content):
				?>
					<?php echo $content; ?>
				<?php
				endforeach;
				?>
			</td>
		</tr>
		<?php
		endif;
		?>
	</tbody>
</table>
<?php 
$pub_view_all = JRequest::getVar('publications_view_all', 0);
$pro_view_all = JRequest::getVar('projects_view_all', 0);
$the_view_all = JRequest::getVar('theses_view_all', 0);
$fac_view_all = JRequest::getVar('facilities_view_all', 0);
$coo_view_all = JRequest::getVar('cooperations_view_all', 0);
?>

<?php if(!empty($this->projects)): ?>
<div class="divEspacio"></div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_PROJECTS'); ?></h3>
<ul>
<?php foreach($this->projects as $project): ?>
	<li><?php echo JHTML::_('jresearch.link', $project->title, 'project', 'show', $project->id); ?></li>
<?php endforeach; ?>
</ul>
<?php 
$text = '';
if($pro_view_all == 0 && $this->max_projects > count($this->projects))
	$text = '['.JText::_('JRESEARCH_VIEW_ALL').']';
elseif($pro_view_all == 1 && $this->max_projects > $this->npro)
	$text = '['.JText::_('JRESEARCH_VIEW_LESS').']';

if(!empty($text))
	echo '<span><a href="index.php?option=com_jresearch&amp;Itemid='.$Itemid.'&amp;publications_view_all='.$pub_view_all.'&amp;projects_view_all='.(!$pro_view_all).'&amp;theses_view_all='.$the_view_all.'&amp;facilities_view_all='.$fac_view_all.'&amp;areas_view_all='.$are_view_all.'&amp;cooperations_view_all='.$coo_view_all.'&amp;task=show&amp;view=team&amp;id='.$this->item->id.'">'.$text.'</a></span><div>&nbsp;</div>';		
?>
<?php endif; ?>

<?php if(!empty($this->publications)): ?>
<div class="divEspacio"></div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></h3>
<ul>
<?php if($this->applyStyles): ?>
<?php foreach($this->publications as $publication): ?>
<li>
  <?php  
  	$styleObj = JResearchCitationStyleFactory::getInstance($this->style, $publication->pubtype);
  	echo $styleObj->getReferenceHTMLText($publication, true); 
  ?>
</li>
<?php endforeach; ?>
<?php else:?>
<?php foreach($this->publications as $publication): ?>
	<li><?php echo JHTML::_('jresearch.link', $publication->title, 'publication', 'show', $publication->id); ?></li>
<?php endforeach; ?>
<?php endif; ?>
</ul>
<?php 
$text = '';
if($pub_view_all == 0 && $this->max_publications > count($this->publications))
	$text = '['.JText::_('JRESEARCH_VIEW_ALL').']';
elseif($pub_view_all == 1 && $this->max_publications > $this->npubs)
	$text = '['.JText::_('JRESEARCH_VIEW_LESS').']';

if(!empty($text))
	echo '<span><a href="index.php?option=com_jresearch&amp;Itemid='.$Itemid.'&amp;publications_view_all='.(!$pub_view_all).'&amp;projects_view_all='.$pro_view_all.'&amp;theses_view_all='.$the_view_all.'&amp;facilities_view_all='.$fac_view_all.'&amp;areas_view_all='.$are_view_all.'&amp;cooperations_view_all='.$coo_view_all.'&amp;task=show&amp;view=team&amp;id='.$this->item->id.'">'.$text.'</a></span><div>&nbsp;</div>';		
?>
<?php endif; ?>
<?php if(!empty($this->theses)): ?>
<div class="divEspacio"></div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_THESES'); ?></h3>
<ul>
<?php foreach($this->theses as $thesis): ?>
	<li><?php echo JHTML::_('jresearch.link', $thesis->title, 'thesis', 'show', $thesis->id); ?></li>
<?php endforeach; ?>
</ul>
<?php 
$text = '';
if($the_view_all == 0 && $this->max_theses > count($this->theses))
	$text = '['.JText::_('JRESEARCH_VIEW_ALL').']';
elseif($the_view_all == 1 && $this->max_theses > $this->nthes)
	$text = '['.JText::_('JRESEARCH_VIEW_LESS').']';

if(!empty($text))
	echo '<span><a href="index.php?option=com_jresearch&amp;Itemid='.$Itemid.'&amp;publications_view_all='.$pub_view_all.'&amp;projects_view_all='.$pro_view_all.'&amp;theses_view_all='.(!$the_view_all).'&amp;facilities_view_all='.$fac_view_all.'&amp;areas_view_all='.$are_view_all.'&amp;cooperations_view_all='.$coo_view_all.'&amp;task=show&amp;view=team&amp;id='.$this->item->id.'">'.$text.'</a></span><div>&nbsp;</div>';

?>
<?php endif; ?>
<?php if(!empty($this->facilities)): ?>
<div class="divEspacio"></div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_FACILITIES'); ?></h3>
<ul class="float">
<?php foreach($this->facilities as $facility): ?>
	<li>
		<?php echo JHTML::_('jresearch.link', $facility->name, 'facility', 'show', $facility->id); ?>
	</li>
<?php endforeach; ?>
</ul>
<?php 
$text = '';
if($fac_view_all == 0 && $this->max_facilities > count($this->facilities))
	$text = '['.JText::_('JRESEARCH_VIEW_ALL').']';
elseif($fac_view_all == 1 && $this->max_facilities > $this->nfac)
	$text = '['.JText::_('JRESEARCH_VIEW_LESS').']';

if(!empty($text))
	echo '<span><a href="index.php?option=com_jresearch&amp;Itemid='.$Itemid.'&amp;publications_view_all='.$pub_view_all.'&amp;projects_view_all='.$pro_view_all.'&amp;theses_view_all='.$the_view_all.'&amp;facilities_view_all='.!($fac_view_all).'&amp;areas_view_all='.$are_view_all.'&amp;cooperations_view_all='.$coo_view_all.'&amp;task=show&amp;view=team&amp;id='.$this->item->id.'">'.$text.'</a></span><div>&nbsp;</div>';		
?>
<?php endif; ?>
<?php if(!empty($this->cooperations)): ?>
<div class="divEspacio"></div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_COOPERATIONS'); ?></h3>
<ul class="float">
<?php foreach($this->cooperations as $cooperation): ?>
	<li>
		<?php echo JHTML::_('jresearch.link', $cooperation->name, 'cooperation', 'show', $cooperation->id); ?>
	</li>
<?php endforeach; ?>
</ul>
<?php 
$text = '';
if($coo_view_all == 0 && $this->max_cooperations > count($this->cooperations))
	$text = '['.JText::_('JRESEARCH_VIEW_ALL').']';
elseif($coo_view_all == 1 && $this->max_cooperations > $this->ncoo)
	$text = '['.JText::_('JRESEARCH_VIEW_LESS').']';

if(!empty($text))
	echo '<span><a href="index.php?option=com_jresearch&amp;Itemid='.$Itemid.'&amp;publications_view_all='.$pub_view_all.'&amp;projects_view_all='.$pro_view_all.'&amp;theses_view_all='.$the_view_all.'&amp;facilities_view_all='.$fac_view_all.'&amp;areas_view_all='.$are_view_all.'&amp;cooperations_view_all='.!($coo_view_all).'&amp;task=show&amp;view=team&amp;id='.$this->item->id.'">'.$text.'</a></span><div>&nbsp;</div>';		
?>
<?php endif; ?>


<div>
	<a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a>
</div>
