<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for showing a list of publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'publications.php');
?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_SEARCH_RESULTS_FOR').': '; ?><strong><?php echo JRequest::getVar('key'); ?></strong></h1>
<a href="index.php?option=com_jresearch&controller=publications&task=advancedsearch&newSearch=1&Itemid=<?php echo JRequest::getInt('Itemid'); ?>"><?php echo JText::_('JRESEARCH_NEW_SEARCH'); ?></a>
<a href="index.php?option=com_jresearch&controller=publications&task=advancedsearch&Itemid=<?php echo JRequest::getInt('Itemid'); ?>"><?php echo JText::_('JRESEARCH_EDIT_CURRENT_SEARCH'); ?></a>
<form name="adminForm" method="post" id="adminForm" action="index.php">
	
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="task" value="search" />
	<input type="hidden" name="controller" value="publications"  />
	<input type="hidden" name="view" value="publicationssearch"  />
	<input type="hidden" name="tmpl" value="advancedsearch"  />	
	<input type="hidden" name="limitstart" value="" />
	<input type="hidden" name="limit" value="20" />
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />	
</form>
<div>
<?php 
	foreach($this->items as $pub): ?>
    <ul style="list-style:none;padding-left:0px;">
    <li>
    	<div><strong><?php echo JText::_('JRESEARCH_AUTHORS').': '?></strong>
    	<?php 
    		$authors = $pub->getAuthors();
    	    echo JResearchPublicationsHelper::formatAuthorsArray($authors, 1);
    	?>
    	</div>
    	<div><strong><?php echo JText::_('JRESEARCH_TITLE').': '; ?></strong>
    	<span><?php echo JHTML::_('jresearch.link', $pub->title, 'publication', 'show', $pub->id); ?></span>
    	</div>
    	<div>
    		<strong><?php echo JText::_('JRESEARCH_STATUS').': '; ?></strong>
    		<?php echo JText::_('JRESEARCH_'.strtoupper($pub->status)); ?>
    	</div>
	</li>
	</ul>
<?php endforeach ?>
</div>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>