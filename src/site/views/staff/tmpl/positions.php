<?php
/**
 * @package JResearch
 * @subpackage Staff
 * @license	GNU/GPL
 * Default view for showing a list of staff members
 */

//@todo Change styling attributes for table to CSS
// no direct access
defined('_JEXEC') or die('Restricted access'); 
jresearchimport('helpers.memberpositions', 'jresearch.admin');

$jinput = JFactory::getApplication()->input;
$itemId = $jinput->getInt('Itemid', 0);
$showTitle = $this->params->get('staff_show_title', 'no');
$showEmail = $this->params->get('member_show_email', 1);
$showResearchArea = $this->params->get('staff_show_research_area', 1);
$showPhone = $this->params->get('staff_show_phone', 1);
$showLocation = $this->params->get('staff_show_location', 1);
$showPersonalPage = $this->params->get('staff_show_personal_page', 1);
$format = $this->params->get('staff_format', 'last_first');
if($this->params->get('show_page_heading', 1)): ?>
<h1 class="componentheading"><?php echo $this->escape($this->params->get('page_heading', JText::_('JRESEARCH_STAFF'))); ?></h1>
<?php endif; ?>

<?php $introText = $this->params->get('staff_introtext', ''); ?>
<?php if(!empty($introText)): ?>
<p>
    <?php echo $introText; ?>
</p>
<?php endif; ?>

<?php 

$positionObjs = JResearchMemberpositionsHelper::getMemberPositions();
$positionsOutputArray = array();
$id2position = array();
foreach ($positionObjs as $posObj) {
	$positionsOutputArray[$posObj['id']] = array();	
	$id2position[$posObj['id']] = $posObj['position'];
}

foreach($this->items as $member){
    $positionsOutputArray[$member->position][] = $member;
}
?>

<?php foreach($positionsOutputArray as $positionId => $group): ?>
    <?php
    	if (count($group) == 0) {
    		continue;
    	}
    ?>
    <h2><?php echo isset($id2position[$positionId]) ? $id2position[$positionId] : JText::_('JRESEARCH_NOT_SPECIFIED'); ?></h2>
    <?php if($this->params->get('staff_list_or_table', 'list') == 'list'): ?>
    <ul class="stafflist">
    <?php foreach($group as $member): ?>
        <li><a href="index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?php echo $member->id; ?><?php echo !empty($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo JResearchPublicationsHelper::formatAuthor($member->__toString(), $format); ?></a>
            <?php if($showEmail == 1 && !empty($member->email)) : ?>
                <span style="margin-left: 0px;"><?php echo '('.JHTML::_('email.cloak', $member->email).')'; ?></span>
            <?php endif; ?>			
        </li>
    <?php endforeach; ?>
    </ul>
    <?php else:?>	
    <table class="stafftable">
    <thead>
        <tr>
            <?php if($showTitle == 'own_column'): ?>
                    <th class="stafftitle"><?php echo JText::_('JRESEARCH_MEMBER_TITLE'); ?></th>
                    <th class="staffname"><?php echo JText::_('JRESEARCH_NAME'); ?></th>
            <?php else: ?>
                    <th class="staffname"><?php echo JText::_('JRESEARCH_NAME'); ?></th>		
            <?php endif;?>
    <?php if($showResearchArea == 1): ?>
        <th class="staffarea"><?php echo JText::_('JRESEARCH_RESEARCH_AREA'); ?></th>
    <?php endif; ?>
    <?php if($showLocation == 1): ?>
        <th class="stafflocation"><?php echo JText::_('JRESEARCH_LOCATION'); ?></th>
    <?php endif; ?>
        <?php if($showEmail == 1): ?>
            <th class="staffemail"><?php echo JText::_('JRESEARCH_EMAIL'); ?></th>
        <?php endif; ?>
    <?php if($showPhone == 1): ?>
            <th class="staffphone"><?php echo JText::_('JRESEARCH_PHONE'); ?></th>
     <?php endif; ?>		        
    <?php if($showPersonalPage == 1): ?>
            <th class="staffwebsite"></th>
    <?php endif; ?>        
            </tr>
    </thead>
    <tbody>
        <?php foreach($group as $member): ?>
            <tr>
            	<?php if($showTitle == 'own_column'): ?>
                	<td><?php echo $member->title; ?></td>
                <?php endif; ?>
                	<td><a href="<?php echo JURI::base(); ?>index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?php echo $member->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo $showTitle == 'next_to_name'? $member->title.' ' : ''; ?><?php echo JResearchPublicationsHelper::formatAuthor($member->__toString(), $this->params->get('staff_format', 'last_first')); ?></a></td>
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
    	<?php endforeach; ?>
    </tbody>
    </table>
    <?php endif; ?>
<?php endforeach; ?>