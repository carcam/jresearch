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
<div>&nbsp;&nbsp;</div>
<table summary="<?php echo JText::_('JRESEARCH_TEAM_SUMMARY'); ?>">
	<tbody>
		<tr>
			<th><?php echo JText::_('JRESEARCH_TEAM_LEADER');?>:</th>
			<td>
				<?php echo $this->leader->published? JHTML::_('jresearch.link', $this->leader, 'member', 'show', $this->leader->id) : $this->leader->__toString(); ?>
			</td>
		</tr>
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
			<td><?php echo $this->leader->phone; ?></td>
		</tr>
		<?php 
		endif;
		
		if($this->leader->email):
		?>
		<tr>
			<th><?php echo JText::_('Email').' :' ?></th>
			<td><?php echo JHTML::_('email.cloak',$this->leader->email, false); ?></td>
		</tr>
		<?php 
		endif;
		?>
		<tr>
			<th><?php echo JText::_('JRESEARCH_TEAM_MEMBERS');?>:</th>
			<td>
				<ul><li><?php echo implode("</li><li> ", $this->memberLinks)?></li></ul>
			</td>
		</tr>
		<?php
		//Show description only if description exists
		if(count($this->description) > 0 && !empty($this->description[0])):
		?>
		<tr>
			<th colspan="2" scope="colgroup">
				<?php echo JText::_('Description')?>:
			</th>
		</tr>
		<tr>
			<td colspan="2">
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
?>
<?php if(!empty($this->projects)): ?>
<div>&nbsp;&nbsp;</div>
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
	echo '<span><a href="index.php?option=com_jresearch&amp;Itemid='.$Itemid.'&amp;publications_view_all='.$pub_view_all.'&amp;projects_view_all='.(!$pro_view_all).'&amp;theses_view_all='.$the_view_all.'&amp;task=show&amp;view=team&amp;id='.$this->item->id.'">'.$text.'</a></span><div>&nbsp;</div>';		
?>
<?php endif; ?>

<?php if(!empty($this->publications)): ?>
<div>&nbsp;&nbsp;</div>
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
	echo '<span><a href="index.php?option=com_jresearch&amp;Itemid='.$Itemid.'&amp;publications_view_all='.(!($pub_view_all)).'&amp;projects_view_all='.$pro_view_all.'&amp;theses_view_all='.$the_view_all.'&amp;task=show&amp;view=team&amp;id='.$this->item->id.'">'.$text.'</a></span><div>&nbsp;</div>';		
?>
<?php endif; ?>
<?php if(!empty($this->theses)): ?>
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
	echo '<span><a href="index.php?option=com_jresearch&amp;Itemid='.$Itemid.'&amp;publications_view_all='.$pub_view_all.'&amp;projects_view_all='.$pro_view_all.'&amp;theses_view_all='.(!$the_view_all).'&amp;task=show&amp;view=team&amp;id='.$this->item->id.'">'.$text.'</a></span><div>&nbsp;</div>';		

?>
<?php endif; ?>
<div>&nbsp;&nbsp;</div>
<div>
	<a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a>
</div>
