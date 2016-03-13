<?php
/**
 * @package JResearch
 * @subpackage Projects
 * @license	GNU/GPL
 * Default view for showing a single project
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');
jresearchimport('helpers.publications', 'jresearch.admin');
$jinput = JFactory::getApplication()->input;
$Itemid = $jinput->getInt('Itemid'); 
$ItemidText = !empty($Itemid)?'&amp;Itemid='.$Itemid:'';
	  	
?>
<h1 class="componentheading"><?php echo $this->project->title; ?></h1>
<?php if($this->showHits): ?>
<div class="small"><?php echo JText::_('JRESEARCH_HITS').': '.$this->project->hits; ?></div>
<?php endif; ?>
<dl class="jresearchitem">
    <?php 
        $researchAreas = $this->project->getResearchAreas('basic'); 
        if(!empty($researchAreas)) : ?>			
    <dt><?php echo JText::_('JRESEARCH_RESEARCH_AREAS').': ' ?></dt>		
    <dd><?php echo JHTML::_('jresearchfrontend.researchareaslinks', $researchAreas); ?></dd>
<?php endif; ?> 			
    <?php if(!empty($this->project->logo)): ?>		
    <dd class="projectlogo">
        <img src="<?php echo $this->project->logo; ?>" border="0" alt="<?php echo $this->project->title; ?>" />
    </dd>
<?php endif; ?>	

    <?php $status = $this->statusArray[$this->project->status]; ?>
    <dt><?php echo JText::_('JRESEARCH_STATUS').': ' ?></dt>
    <dd><?php echo $status; ?></dd>

    <?php $leaders = $this->project->getLeaders(); ?>
    <?php $authors = $this->project->getMembers(); ?>
    <?php if (!empty($leaders)) : ?>
        <dt><?php echo JText::_('JRESEARCH_PROJECT_LEADERS').': ' ?></dt>
        <dd>		
            <?php echo JHTML::_('jresearchfrontend.authorsList', $leaders, $this->format, $this->staff_list_arrangement) ?>    
        </dd>
    <?php endif; ?>

    <?php if(!empty($authors)): ?>
    <dt><?php echo JText::_('JRESEARCH_PROJECT_MEMBERS').': ' ?></dt>
    <dd>		
        <?php echo JHTML::_('jresearchfrontend.authorsList', $authors, $this->format, $this->staff_list_arrangement) ?>    
    </dd>	
    <?php endif; ?>
    <?php $startDate = $this->project->getStartDate(); ?>
    <?php if($startDate != '-'): ?>
        <dt><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></dt>
        <dd><?php echo $this->project->start_date; ?></dd>
    <?php endif; ?>
    <?php $endDate = $this->project->getStartDate(); ?>
    <?php if($endDate != '-'):  ?>  	
        <dt><?php echo JText::_('JRESEARCH_DEADLINE').': '; ?></dt>
        <dd><?php echo $this->project->end_date; ?></dd>  	
    <?php endif; ?>	
    <?php if(!empty($this->publications)): ?>
</dl>	
    <h2 class="contentheading" id="pubslist"><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></h2>
    <ul>
    <?php foreach($this->publications as $publication): ?>
            <?php if($this->applyStyle): ?>
            <li>
                    <?php  
                            $styleObj =& JResearchCitationStyleFactory::getInstance($this->style, $publication->pubtype);
                            echo $styleObj->getReferenceHTMLText($publication, true); 
                    ?>
                    <?php echo JHTML::_('jresearchfrontend.link', JText::_('JRESEARCH_MORE'), 'publication', 'show', $publication->id); ?>&nbsp;
            </li>
            <?php else: ?>
            <li>
                    <?php echo JHTML::_('jresearchfrontend.link', $publication->title, 'publication', 'show', $publication->id); ?>
            </li>
            <?php endif; ?>
    <?php endforeach; ?>
    </ul>
    <div>
            <?php if($this->npublications > count($this->publications)): ?>
                    <?php $link = JRoute::_("index.php?option=com_jresearch&amp;publications_view_all=1&amp;task=show&amp;view=project&amp;id=".$this->project->id.$ItemidText."#pubslist"); ?>
                    <a href="<?php echo $link ?>"><?php echo JText::_('JRESEARCH_VIEW_ALL'); ?></a>
            <?php else: ?>
                    <?php if($this->publications_view_all): ?>
                        <?php $link = JRoute::_("index.php?option=com_jresearch&amp;publications_view_all=0&amp;task=show&amp;view=project&amp;id=".$this->project->id.$ItemidText."#pubslist"); ?>
                        <a href="<?php $link = JRoute::_("index.php?option=com_jresearch&amp;publications_view_all=1&amp;task=show&amp;view=project&amp;id=".$this->project->id.$ItemidText."#pubslist"); ?>"><?php echo JText::_('JRESEARCH_VIEW_LESS'); ?></a>
                    <?php endif; ?>
            <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if(!empty($this->description)): ?>
        <dt><?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?></dt>
        <dd><?php echo $this->description; ?></dd>
    <?php endif; ?>
    <div class="divTR">
	<?php
            $attachments = $this->project->getAttachments();
            $url = str_replace('&', '&amp;', trim($this->project->url));		
            $entry = array();
            $entry['url'] = $url;
            $entry['tag'] = JText::_('JRESEARCH_PROJECT_WEBSITE');
            $attachments[] = $entry;

            if (count($attachments) > 0) {
                echo JHTML::_('jresearchfrontend.attachments', $attachments, 'horizontal');
            }
         ?>
    <div class="divEspacio"></div>	
</div>        
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>