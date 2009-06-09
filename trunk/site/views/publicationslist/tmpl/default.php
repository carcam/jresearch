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
<div style="float: right;"><?php JHTML::_('Jresearch.icon','add','publications'); ?></div>
<div>
<?php 
	$layout = JRequest::getVar('filter_order', 'year');
	require_once(JPATH_COMPONENT.DS.'views'.DS.'publicationslist'.DS.'tmpl'.DS.$layout.'.php');
?>
</div>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>