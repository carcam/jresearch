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
<h2 class="componentheading"><?php echo JText::_('JRESEARCH_MEMBERS'); ?></h2>
<br />
<br />
<table class="stafftable">
	<thead>
		<tr>
			<th><?php echo JText::_('JRESEARCH_NAME'); ?></th>
		<?php if($this->params->get('member_show_email') == 'yes'): ?>
			<th><?php echo JText::_('JRESEARCH_EMAIL'); ?></th>	
		<?php endif; ?>
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
			<?php if($this->params->get('member_show_email') == 'yes'): ?>
				<td><?php echo JHTML::_('email.cloak', $member->email); ?></td>
			<?php endif; ?>	
			<td>
				<?php if($researchArea->id > 1):?>
					<?php echo JHTML::_('jresearch.link', $researchArea->name, 'researcharea', 'show', $researchArea->id); ?>
				<?php else: ?>
					<?php echo $researchArea->name; ?>				
				<?php endif; ?>
			</td>
			<td><?php echo empty($member->position)?JText::_('JRESEARCH_NOT_SPECIFIED'):$member->getPosition(); ?></td>
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