<?php
/**
 * @package JResearch
 * @subpackage Staff
 * Default view for showing a list of staff members
 */

//@todo Change styling attributes for table to CSS
// no direct access
defined('_JEXEC') or die('Restricted access'); 
$itemId = JRequest::getVar('Itemid');
?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_MEMBERS'); ?></h1>
<table class="stafftable">
	<thead>
		<tr>
			<th><?php echo JText::_('JRESEARCH_NAME'); ?></th>
			<th><?php echo JText::_('JRESEARCH_EMAIL'); ?></th>	
			<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA'); ?></th>
			<th><?php echo JText::_('JRESEARCH_POSITION'); ?></th>												
		</tr>
	</thead>
	<tfoot align="center">
		<tr><td colspan="4"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></td></tr>
	</tfoot>
    <?php
    $itemId = JRequest::getVar('Itemid');
    ?>
	<tbody>
	    <?php
	    if(count($this->items) > 0):
	    foreach($this->items as $member): 
		    $researchArea = $this->areaModel->getItem($member->id_research_area);
	    ?>
		<tr>
			<td><a href="<?php echo JURI::base(); ?>index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?php echo $member->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo JResearchPublicationsHelper::formatAuthor($member->__toString(), $this->format); ?></a></td>
			<td><?php echo JHTML::_('email.cloak', $member->email); ?></td>
			<td>
				<?php if($researchArea->id > 1):?>
					<a href="index.php?option=com_jresearch&amp;view=researcharea&amp;id=<?php echo $researchArea->id;?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo $researchArea->name; ?></a>
				<?php else: ?>
					<?php echo $researchArea->name; ?>				
				<?php endif; ?>
			</td>
			<td><?php echo $member->position; ?></td>
		</tr>
        <?php
        endforeach;
        
        else:
        ?>
        <tr><td colspan="4"></td></tr>
        <?php
        endif;
        ?>
	</tbody>
</table>