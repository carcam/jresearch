<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo $this->area->name; ?></div>
<h1><?php echo JText::_('JRESEARCH_DESCRIPTION'); ?></h1>
<div><?php echo str_replace('<hr id="system-readmore" />', '', $this->area->description);  ?></div>
<div>&nbsp;&nbsp;</div>
<?php $itemId = JRequest::getVar('Itemid'); ?>
<?php if(!empty($this->members)): ?>
<h1><?php echo JText::_('JRESEARCH_MEMBERS'); ?></h1>
<ul>
<?php foreach($this->members as $member): ?>
	<li><a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $member->id ?><?php echo isset($itemId)?'&Itemid='.$itemId:''; ?>" ><?php echo $member; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if(!empty($this->publications)): ?>
<div>&nbsp;&nbsp;</div>
<h1><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></h1>
<ul>
<?php foreach($this->publications as $publication): ?>
	<li><a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $publication->id; ?><?php echo isset($itemId)?'&Itemid='.$itemId:''; ?>" ><?php echo $publication->title; ?></a></li>
<?php endforeach; ?>
</ul>
<div>
  	<?php if($this->npublications > count($this->publications)): ?>
  			<a href="index.php?option=com_jresearch&publications_view_all=1&theses_view_all=<?php echo $this->theses_view_all; ?>&projects_view_all=<?php echo $this->projects_view_all; ?>&task=show&view=researcharea&id=<?php echo $this->area->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_ALL'); ?></a>
  	<?php else: ?>
  			<?php if($this->publications_view_all): ?>		
  				<a href="index.php?option=com_jresearch&publications_view_all=0&theses_view_all=<?php echo $this->theses_view_all; ?>&projects_view_all=<?php echo $this->projects_view_all; ?>&task=show&view=researcharea&id=<?php echo $this->area->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_LESS'); ?></a>
  			<?php endif; ?>
  	<? endif; ?>
</div>
<?php endif; ?>

<?php if(!empty($this->projects)): ?>
<div>&nbsp;&nbsp;</div>
<h1><?php echo JText::_('JRESEARCH_PROJECTS'); ?></h1>
<ul>
<?php foreach($this->projects as $project): ?>
	<li><a href="index.php?option=com_jresearch&view=project&task=show&id=<?php echo $project->id; ?><?php echo isset($itemId)?'&Itemid='.$itemId:''; ?>" ><?php echo $project->title; ?></a></li>
<?php endforeach; ?>
</ul>
  <div>
  	<?php if($this->nprojects > count($this->projects)): ?>
  			<a href="index.php?option=com_jresearch&projects_view_all=1&theses_view_all=<?php echo $this->theses_view_all; ?>&publications_view_all=<?php echo $this->publications_view_all; ?>&task=show&view=researcharea&id=<?php echo $this->area->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_ALL'); ?></a>
  	<?php else: ?>
  			<?php if($this->projects_view_all): ?>		
  				<a href="index.php?option=com_jresearch&projects_view_all=0&theses_view_all=<?php echo $this->theses_view_all; ?>&publications_view_all=<?php echo $this->publications_view_all; ?>&task=show&view=researcharea&id=<?php echo $this->area->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_LESS'); ?></a>
  			<?php endif; ?>
  	<? endif; ?>
  </div>
<?php endif; ?>

<?php if(!empty($this->theses)): ?>
<div>&nbsp;&nbsp;</div>
<h1><?php echo JText::_('JRESEARCH_THESES'); ?></h1>
<ul>
<?php foreach($this->theses as $thesis): ?>
	<li><a href="index.php?option=com_jresearch&view=thesis&task=show&id=<?php echo $thesis->id; ?><?php echo isset($itemId)?'&Itemid='.$itemId:''; ?>" ><?php echo $thesis->title; ?></a></li>
<?php endforeach; ?>
</ul>
  <div>

  	<?php if($this->ntheses > count($this->theses)): ?>
  			<a href="index.php?option=com_jresearch&theses_view_all=1&projects_view_all=<?php echo $this->projects_view_all; ?>&publications_view_all=<?php echo $this->publications_view_all; ?>&task=show&view=researcharea&id=<?php echo $this->area->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_ALL'); ?></a>
  	<?php else: ?>
  			<?php if($this->theses_view_all): ?>		
  				<a href="index.php?option=com_jresearch&projects_view_all=<?php echo $this->projects_view_all; ?>&publications_view_all=<?php echo $this->publications_view_all; ?>&theses_view_all=0&task=show&view=researcharea&id=<?php echo $this->area->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_LESS'); ?></a>
  			<?php endif; ?>
  	<?php endif; ?>
  </div>
<?php endif; ?>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>


