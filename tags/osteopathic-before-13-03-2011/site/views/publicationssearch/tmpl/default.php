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
<form name="adminForm" method="post" id="adminForm" action="index.php">
<div style="float:left;">
<a href="index.php?option=com_jresearch&controller=publications&task=advancedsearch&newSearch=1&Itemid=<?php echo JRequest::getInt('Itemid'); ?>"><?php echo JText::_('JRESEARCH_NEW_SEARCH'); ?></a>
<a href="index.php?option=com_jresearch&controller=publications&task=advancedsearch&Itemid=<?php echo JRequest::getInt('Itemid'); ?>"><?php echo JText::_('JRESEARCH_EDIT_CURRENT_SEARCH'); ?></a>
</div>
<div style="float:right;">
<span><strong><?php echo JText::_('JRESEARCH_ORDER_BY').': '; ?></strong></span><?php echo $this->filter_order; ?>
</div>
<div style="clear:both;"></div>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="task" value="search" />
	<input type="hidden" name="controller" value="publications"  />
	<input type="hidden" name="view" value="publicationssearch"  />
	<input type="hidden" name="limitstart" value="" />
	<input type="hidden" name="limit" value="20" />
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />	
	<input type="hidden" name="key" value="<?php echo JRequest::getVar('key', ''); ?>" />
</form>
<?php if($this->nitems > 0): ?>
<?php if($this->nitems > 1): ?>
<div><?php echo JText::sprintf('JRESEARCH_ITEMS_RETRIEVED', $this->nitems); ?></div>
<?php else: ?>
<div><?php echo JText::sprintf('JRESEARCH_ITEM_RETRIEVED', $this->nitems); ?></div>
<?php endif; ?>
<?php endif; ?>
<div>
<?php 
	foreach($this->items as $pub): ?>
    <ul style="list-style:none;padding-left:0px;">
    <li>
    	<div><strong><?php echo JText::_('JRESEARCH_TITLE').': '; ?></strong>
    	<?php if($pub->status == 'in_progress' || $pub->status == 'protocol'): ?>
	    	<span><?php echo $pub->title; ?></span>	    	
		<?php else: ?>
	    	<span><?php echo JHTML::_('jresearch.link', $pub->title, 'publication', 'show', $pub->id); ?></span>	
		<?php endif; ?>
    	</div>    
    	<div><strong><?php echo JText::_('JRESEARCH_AUTHORS').': '?></strong>
    	<?php 
    		$authors = $pub->getAuthors();
			global $mainframe;
    		$params = $mainframe->getParams('com_jresearch');
    		$format = $params->get('staff_format') == 'last_first'?0:1;
    	    echo JResearchPublicationsHelper::formatAuthorsArray($authors, $format);
    	?>
    	</div>
    	<?php if(!empty($pub->status)): ?>
    	<div>
    		<span><strong><?php echo JText::_('JRESEARCH_STATUS').': '; ?></strong></span>
    		<span><?php echo JText::_('JRESEARCH_'.strtoupper($pub->status)); ?></span>    		
    	</div>
    	<?php endif; ?>
    	<div>		
    	<?php 
			if(isset($pub->journal) && !empty($pub->journal))
				echo $pub->journal.'. ';
				
			if(!empty($pub->year)){
				echo $pub->year;
				if(!empty($pub->month)){
					$tm=mktime(0,0,0,$pub->month,1,$pub->year);
					echo ', '.date("M",$tm); 					
					if(!empty($pub->day)){
						echo ' '.$pub->day;					
					}
				}
				
			}
			
			if(isset($pub->volume) && !empty($pub->volume)){
				if(isset($pub->number) && !empty($pub->number)){
					echo '; '.$pub->volume.'('.$pub->number.')';
				}				
			}
			
			if(isset($pub->pages) && !empty($pub->pages))
				echo ': '.$pub->pages;

			echo ' ';	
		?>
		<?php if($pub->status != 'in_progress'): ?>    	
	    	<span><a href="index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=bibtex&amp;id=<?php echo $pub->id; ?>" title="Bibtex">[Bibtex]</a></span>
    	<?php endif; ?>
    	</div>
	</li>
	</ul>
<?php endforeach ?>
</div>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>