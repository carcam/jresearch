<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for showing a list of publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<?php if($this->showHeader): ?>
<h1 class="componentheading"><?php echo $this->escape($this->header); ?></h1>
<?php endif; ?>
<form name="adminForm" method="post" id="adminForm" action="<?php echo JURI::current(); ?>">
	<div style="text-align: left;">
		<?php echo $this->filter; ?>
		<?php 
			$actions = JResearchAccessHelper::getActions('publications', $this->publication->id);
			if($actions->get('core.publications.create')):
		?>
				<div><?php echo JHTML::_('jresearchfrontend.icon','new','publications'); ?></div>						
			<?php endif; ?>
	</div>
	
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="task" value="list" />
	<input type="hidden" name="view" value="publications"  />	
	<input type="hidden" name="controller" value="publications"  />
	<input type="hidden" name="limitstart" value="0" />
	<input type="hidden" name="modelkey" value="default" />
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />	
</form>
<div style="clear: both;">
<?php if($this->exportAll): ?>
<div style="text-align: right;"><a title="<?php echo JText::_('JRESEARCH_EXPORT_ALL_TITLE')?>" href="index.php?option=com_jresearch&amp;controller=publications&amp;task=exportAll&amp;format=<?php echo $this->showAllFormat; ?>"><?php echo JText::_('JRESEARCH_EXPORT_ALL'); ?></a></div>
<?php endif; ?>
<?php 
	require_once(JRESEARCH_COMPONENT_SITE.DS.'views'.DS.'publications'.DS.'tmpl'.DS.$this->layout.'.php');
?>
</div>
<div class="frontendPagination"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>