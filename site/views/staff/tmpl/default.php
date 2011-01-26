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
$showTitle = $this->params->get('staff_show_title', 'no');
$showEmail = $this->params->get('member_show_email', 'yes');
$showResearchArea = $this->params->get('staff_show_research_area', 'yes');
$showPosition = $this->params->get('staff_show_position', 'yes');
$showPhoneOrFax = $this->params->get('staff_show_phone_or_fax', 'yes');

?>
<h2 class="componentheading"><?php echo JText::_('JRESEARCH_MEMBERS'); ?></h2>
<br />
<br />
<table class="stafftable">
        <?php $nCols = 1; ?>
	<thead>
		<tr>
		<?php if($showTitle == 'own_column'): ?>
			<th class="stafftitle"><?php echo JText::_('JRESEARCH_MEMBER_TITLE'); ?></th>
		<?php   $nCols++;
					endif; ?>
		

			<th class="staffname"><?php echo JText::_('JRESEARCH_NAME'); ?></th>
		<?php if($showEmail == 'yes'): ?>
			<th class="staffemail"><?php echo JText::_('JRESEARCH_EMAIL'); ?></th>
                        <?php $nCols++; ?>
		<?php endif; ?>
       	<?php if($showResearchArea == 'yes'): ?>
			<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA'); ?></th>
                        <?php $nCols++; ?>
        <?php endif; ?>
        <?php if($showPosition == 'yes'): ?>
			<th><?php echo JText::_('JRESEARCH_POSITION'); ?></th>
                        <?php $nCols++; ?>
        <?php endif; ?>
        <?php if($showPhoneOrFax == 'yes'): ?>
			<th><?php echo JText::_('JRESEARCH_PHONE_OR_FAX'); ?></th>
                        <?php $nCols++; ?>
         <?php endif; ?>
		</tr>
	</thead>
	<tfoot align="center">
		<tr><td colspan="<?php echo $nCols; ?>"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></td></tr>
	</tfoot>
    <?php
    $itemId = JRequest::getVar('Itemid');
    ?>
	<tbody>
	    <?php
	    foreach($this->items as $member): 
		    $researchArea = $this->areaModel->getItem($member->id_research_area);
	    ?>
		<tr>
			<?php if($showTitle == 'own_column'): ?>
				<td><?php echo $member->title; ?></td>
			<?php endif; ?>
			<td><a href="<?php echo JURI::base(); ?>index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?php echo $member->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo $showTitle == 'next_to_name'? $member->title.' ' : ''; ?><?php echo JResearchPublicationsHelper::formatAuthor($member->__toString(), $this->format); ?></a></td>
			<?php if($showEmail == 'yes'): ?>
				<td><?php echo JHTML::_('email.cloak', $member->email); ?></td>
			<?php endif; ?>	
            <?php if($showResearchArea == 'yes'): ?>
			<td>
			
				<?php if($researchArea->id > 1):?>
					<?php echo JHTML::_('jresearch.link', $researchArea->name, 'researcharea', 'show', $researchArea->id); ?>
				<?php else: ?>
					<?php echo $researchArea->name; ?>							
				<?php endif; ?>
			</td>				
             <?php endif; ?>
			
              <?php if($showPosition == 'yes'): ?>
                    <td><?php echo empty($member->position)?JText::_('JRESEARCH_NOT_SPECIFIED'):$member->getPosition(); ?></td>
               <?php endif; ?>
               <?php if($showPhoneOrFax == 'yes'): ?>
                     <td><?php echo $member->phone_or_fax; ?></td>
                <?php endif; ?>
		</tr>
        <?php
        endforeach;        
        ?>
	</tbody>
</table>