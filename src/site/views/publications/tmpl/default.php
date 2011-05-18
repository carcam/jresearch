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
<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch&amp;view=publicationslist&amp;task=list&amp;modelkey=default">
	<div style="text-align: left;">
		<?php echo $this->filter; ?>
		<div><?php echo JHTML::_('jresearchfrontend.icon','add','publications'); ?></div>						
	</div>
	
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="task" value="display" />
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
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>