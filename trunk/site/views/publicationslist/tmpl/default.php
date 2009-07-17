<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for showing a list of publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></h1>
<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch">
	<div style="text-align: left;">
		<?php echo $this->filter; ?>
		<div>&nbsp;<?php echo JHTML::_('Jresearch.icon','add','publications'); ?></div>						
	</div>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="task" value="display" />
	<input type="hidden" name="controller" value="publications"  />
	<input type="hidden" name="limitstart" value="0" />
	<input type="hidden" name="modelkey" value="default" />
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />	
</form>
<div style="clear: both;">
&nbsp;
<?php 
	require_once(JPATH_COMPONENT.DS.'views'.DS.'publicationslist'.DS.'tmpl'.DS.$this->layout.'.php');
?>
</div>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>