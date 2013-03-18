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
$showEmail = $this->params->get('member_show_email', 1);
$showResearchArea = $this->params->get('staff_show_research_area', 1);
$showPosition = $this->params->get('staff_show_position', 1);
$showPhone = $this->params->get('staff_show_phone', 1);
$showLocation = $this->params->get('staff_show_location', 1);
$showPersonalPage = $this->params->get('staff_show_personal_page', 1);
$nCols = 0;
if($this->params->get('show_page_heading', 1)): ?>
<h1 class="componentheading"><?php echo $this->escape($this->params->get('page_heading', JText::_('JRESEARCH_STAFF'))); ?></h1>
<?php endif; ?>

<?php $introText = $this->params->get('staff_introtext', ''); ?>
<?php if(!empty($introText)): ?>
<p>
    <?php echo $introText; ?>
</p>
<?php endif; ?>
<table class="stafftable">
	<thead>
		<tr>
		<?php if($showTitle == 'own_column'): ?>
			<th class="stafftitle"><?php echo JText::_('JRESEARCH_MEMBER_TITLE'); ?></th>
			<th class="staffname"><?php echo JText::_('JRESEARCH_NAME'); ?></th>
			<?php $nCols += 2; ?>
		<?php else: ?>
			<th class="staffname"><?php echo JText::_('JRESEARCH_NAME'); ?></th>		
			<?php $nCols++; ?>			
		<?php endif;?>
        <?php if($showPosition == 1): ?>
			<th><?php echo JText::_('JRESEARCH_POSITION'); ?></th>
			<?php $nCols++; ?>			
        <?php endif; ?>			
       	<?php if($showResearchArea == 1): ?>
			<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA'); ?></th>
			<?php $nCols++; ?>			
        <?php endif; ?>
        <?php if($showLocation == 1): ?>
			<th><?php echo JText::_('JRESEARCH_LOCATION'); ?></th>
			<?php $nCols++; ?>			
        <?php endif; ?>
		<?php if($showEmail == 1): ?>
			<th class="staffemail"><?php echo JText::_('JRESEARCH_EMAIL'); ?></th>
			<?php $nCols++; ?>			
		<?php endif; ?>
        <?php if($showPhone == 1): ?>
			<th style="width: 15%;"><?php echo JText::_('JRESEARCH_PHONE_OR_FAX'); ?></th>
			<?php $nCols++; ?>
         <?php endif; ?>		        
        <?php if($showPersonalPage == 1): $nCols++; ?>
			<th></th>
        <?php endif; ?>        
		</tr>
	</thead>
    <?php
    $itemId = JRequest::getVar('Itemid');
    $twoGroups = $this->params->get('staff_former_grouping') == 1 && $this->params->get('staff_filter') == 'all';
    $previousMember = null;
    ?>
	<tbody>
	    <?php foreach($this->items as $member): ?>
	    <?php if($twoGroups && $previousMember != null && $previousMember->former_member != $member->former_member): ?>
	    	<tr><th colspan="<?php echo $nCols;?>"><?php echo JText::_('JRESEARCH_FORMER_MEMBERS'); ?></th></tr>
	    <?php endif; ?>
		<tr>
			<?php if($showTitle == 'own_column'): ?>
				<td><?php echo $member->title; ?></td>
			<?php endif; ?>
			<td><a href="<?php echo JURI::base(); ?>index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?php echo $member->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo $showTitle == 'next_to_name'? $member->title.' ' : ''; ?><?php echo JResearchPublicationsHelper::formatAuthor($member->__toString(), $this->params->get('staff_format', 'last_first')); ?></a></td>
            <?php if($showPosition == 1): ?>
            	<td><?php echo empty($member->position)?JText::_('JRESEARCH_NOT_SPECIFIED'):$member->getPositionObj(); ?></td>
            <?php endif; ?>
            <?php if($showResearchArea == 1): ?>
				<td><?php echo JHTML::_('jresearchfrontend.researchareaslinks', $member->getResearchAreas('names'), 'inline'); ?></td>				
            <?php endif; ?>	
			<?php if($showLocation == 1): ?>
				<td><?php echo $member->location; ?></td>
			<?php endif; ?>	            		            
			<?php if($showEmail == 1): ?>
				<td><?php echo JHTML::_('email.cloak', $member->email); ?></td>
			<?php endif; ?>	
            <?php if($showPhone == 1): ?>
            	<td><?php echo $member->phone; ?></td>
            <?php endif; ?>			
        	<?php if($showPersonalPage == 1): ?>
				<td><?php echo !empty($member->url_personal_page) ? JHTML::link($member->url_personal_page, JText::_('JRESEARCH_PERSONAL_PAGE')) : ''; ?></td>
        	<?php endif; ?>        			
		</tr>
        <?php
        $previousMember = $member;
        endforeach; ?>
	</tbody>
</table>
<div class="frontendPagination"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>