<?php
/**
* @package JResearch
* @subpackage Teams
* Default view for showing a single team
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 
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
				<?php echo JHTML::_('jresearch.link', $this->leader, 'member', 'show', $this->leader->id); ?>
			</td>
		</tr>
		<?php 
		if($this->leader->position):
		?>
		<tr>
			<th><?php echo JText::_('Position').': ' ?></th>
			<td><?php echo $this->leader->position; ?></td>
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
			<th><?php echo JText::_('JRESEARCH_PHONE_OR_FAX').': ' ?></th>
			<td><?php echo $this->leader->phone_or_fax; ?></td>
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
<?php if(!empty($this->projects)): ?>
<div>&nbsp;&nbsp;</div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_PROJECTS'); ?></h3>
<ul>
<?php foreach($this->projects as $project): ?>
	<li><?php echo JHTML::_('jresearch.link', $project->title, 'project', 'show', $project->id); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php if(!empty($this->publications)): ?>
<div>&nbsp;&nbsp;</div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></h3>
<ul>
<?php foreach($this->publications as $publication): ?>
	<li><?php echo JHTML::_('jresearch.link', $publication->title, 'publication', 'show', $publication->id); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php if(!empty($this->theses)): ?>
<div>&nbsp;&nbsp;</div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_THESES'); ?></h3>
<ul>
<?php foreach($this->theses as $thesis): ?>
	<li><?php echo JHTML::_('jresearch.link', $thesis->title, 'thesis', 'show', $thesis->id); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<div>
	<a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a>
</div>