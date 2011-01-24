<?php
/**
 * @package JResearch
 * @subpackage ResearchAreas
 * Default view for showing a single research area
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h2 class="componentheading"><?php echo $this->area->name; ?></h2>
<?php if(!empty($this->description)): ?>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_DESCRIPTION'); ?></h3>
<?php echo $this->description;  ?>
<div class="divEspacio"></div>
<?php endif; ?>
<?php $itemId = JRequest::getVar('Itemid'); ?>

<?php if(!empty($this->teams)): ?>
<div class="divEspacio"></div>
<h3 class="contentheading"><?php echo JText::_('JRESEARCH_TEAMS'); ?></h3>
<ul>
<?php foreach($this->teams as $team): ?>
	<li><a href="index.php?option=com_jresearch&amp;view=team&amp;task=show&amp;id=<?php echo $team->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>" ><?php echo $team->name; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>