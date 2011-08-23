<?php
/**
 * @package JResearch
 * @subpackage ResearchAreas
 * Default view for showing a single research area
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'publications.php');
?>
<h2 class="componentheading"><?php echo $this->area->name; ?></h2>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_DESCRIPTION'); ?></h3>
<?php echo $this->description;  ?>
<div>&nbsp;&nbsp;</div>
<?php if(!empty($this->facilities)): ?>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_FACILITIES'); ?></h3>
<ul class="float">
<?php foreach($this->facilities as $facility): ?>
	<li>
		<?php echo JHTML::_('jresearch.link', $facility->name, 'facility', 'show', $facility->id); ?>
	</li>
<?php endforeach; ?>
</ul>
<div>&nbsp;&nbsp;</div>
<?php endif; ?>
<?php $itemId = JRequest::getVar('Itemid'); ?>
<?php if(!empty($this->members)): ?>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_MEMBERS'); ?></h3>
<ul>
<?php foreach($this->members as $member): ?>
	<li><?php echo JHTML::_('jresearch.link', JResearchPublicationsHelper::formatAuthor($member->__toString(), $this->format), 'member', 'show', $member->id); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if(!empty($this->publications)): ?>
<div>&nbsp;&nbsp;</div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></h3>
<ul>
<?php foreach($this->publications as $publication): ?>
	<?php if($this->applyStyle): ?>
  	<li>
  		<?php  
  			$styleObj =& JResearchCitationStyleFactory::getInstance($this->style, $publication->pubtype);
  			echo $styleObj->getReferenceHTMLText($publication, true); 
  		?>
  		<?php echo JHTML::_('jresearch.link', JText::_('JRESEARCH_MORE'), 'publication', 'show', $publication->id); ?>&nbsp;
  	</li>
	<?php else: ?>
	<li>
		<?php echo JHTML::_('jresearch.link', $publication->title, 'publication', 'show', $publication->id); ?>
	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<div>
  	<?php if($this->npublications > count($this->publications)): ?>
  			<a href="index.php?option=com_jresearch&amp;publications_view_all=1&amp;theses_view_all=<?php echo $this->theses_view_all; ?>&amp;projects_view_all=<?php echo $this->projects_view_all; ?>&amp;task=show&amp;view=researcharea&amp;id=<?php echo $this->area->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_ALL'); ?></a>
  	<?php else: ?>
  			<?php if($this->publications_view_all): ?>		
  				<a href="index.php?option=com_jresearch&amp;publications_view_all=0&amp;theses_view_all=<?php echo $this->theses_view_all; ?>&amp;projects_view_all=<?php echo $this->projects_view_all; ?>&amp;task=show&amp;view=researcharea&amp;id=<?php echo $this->area->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_LESS'); ?></a>
  			<?php endif; ?>
  	<?php endif; ?>
</div>
<?php endif; ?>

<?php if(!empty($this->projects)): ?>
<div>&nbsp;&nbsp;</div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_PROJECTS'); ?></h3>
<ul>
<?php foreach($this->projects as $project): ?>
	<li><a href="index.php?option=com_jresearch&amp;view=project&amp;task=show&amp;id=<?php echo $project->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>" ><?php echo $project->title; ?></a></li>
<?php endforeach; ?>
</ul>
  <div>
  	<?php if($this->nprojects > count($this->projects)): ?>
  			<a href="index.php?option=com_jresearch&amp;projects_view_all=1&amp;theses_view_all=<?php echo $this->theses_view_all; ?>&amp;publications_view_all=<?php echo $this->publications_view_all; ?>&amp;task=show&amp;view=researcharea&amp;id=<?php echo $this->area->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_ALL'); ?></a>
  	<?php else: ?>
  			<?php if($this->projects_view_all): ?>		
  				<a href="index.php?option=com_jresearch&amp;projects_view_all=0&amp;theses_view_all=<?php echo $this->theses_view_all; ?>&amp;publications_view_all=<?php echo $this->publications_view_all; ?>&amp;task=show&amp;view=researcharea&amp;id=<?php echo $this->area->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_LESS'); ?></a>
  			<?php endif; ?>
  	<?php endif; ?>
  </div>
<?php endif; ?>

<?php if(!empty($this->theses)): ?>
<div>&nbsp;&nbsp;</div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_THESES'); ?></h3>
<ul>
<?php foreach($this->theses as $thesis): ?>
	<li><a href="index.php?option=com_jresearch&amp;view=thesis&amp;task=show&amp;id=<?php echo $thesis->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>" ><?php echo $thesis->title; ?></a></li>
<?php endforeach; ?>
</ul>
  <div>

  	<?php if($this->ntheses > count($this->theses)): ?>
  			<a href="index.php?option=com_jresearch&amp;theses_view_all=1&amp;projects_view_all=<?php echo $this->projects_view_all; ?>&amp;publications_view_all=<?php echo $this->publications_view_all; ?>&amp;task=show&amp;view=researcharea&amp;id=<?php echo $this->area->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_ALL'); ?></a>
  	<?php else: ?>
  			<?php if($this->theses_view_all): ?>		
  				<a href="index.php?option=com_jresearch&amp;projects_view_all=<?php echo $this->projects_view_all; ?>&amp;publications_view_all=<?php echo $this->publications_view_all; ?>&amp;theses_view_all=0&amp;task=show&amp;view=researcharea&amp;id=<?php echo $this->area->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_VIEW_LESS'); ?></a>
  			<?php endif; ?>
  	<?php endif; ?>
  </div>
<?php endif; ?>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>