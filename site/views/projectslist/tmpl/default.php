<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for showing a list of projects
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_PROJECTS'); ?></h1>
<ul style="padding-left:0px;">
 
<?php foreach($this->items as $project): ?>
	<?php $researchArea = $this->areaModel->getItem($project->id_research_area); ?>
	<li class="liresearcharea">
		<div>
			<?php $contentArray = explode('<hr id="system-readmore" />', $project->description); ?>
			<?php $itemId = JRequest::getVar('Itemid'); ?>
			<h2 class="contentheading"><?php echo $project->title; ?></h2>
			<div>&nbsp;</div>
			<?php 
			//Show research area?
			if($this->params->get('show_researcharea') == 1)
			{
			?>		
			<div>
				<strong><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '?></strong>
				<span><?php echo $researchArea->name;  ?></span>
			</div>
			<?php 
			}
			
			//Show members?
			if($this->params->get('show_members') == 1)
			{
				$members = implode(', ',$project->getPrincipalInvestigators());
			?>			
			<div>
        	<strong><?php echo JText::_('JRESEARCH_PROJECT_LEADERS').':'?></strong><span>
        	<?php foreach($members as $member){ 
                 if($member instanceof JResearchMember)
                  	$text .= ' '.$member->__toString().',';
                 else
                  	$text .= ' '.$member.',';
              	
                 echo rtrim($text,',');
              }
        	?>
			</span>
			</div>
			<?php 
			}
			
			//Get values and financiers for project
          	$financiers = $project->getFinanciers();
          	$financiersText = '';

          	foreach($financiers as $financier){
            	$financiersText .= $financier->__toString().', ';
          	}
          	$financiersText = rtrim($financiersText);
          	$financiersText = rtrim($financiersText, ',');
          	
			$value = str_replace(array(",00",".00"), ",-", $project->finance_value); //Replace ,/.00 with ,-
			
			//Convert value to format 1.000.000,xx
			$aFloat = substr($value, strpos($value, ","));
			$cValue = array_reverse(str_split(strrev(substr($value, 0, strpos($value, ","))), 3));
			
			$convertedArray = array();
			foreach($cValue as $val)
			{
				$convertedArray[] = strrev($val);
			}
			
			$value = implode(".",$convertedArray).$aFloat;
			?>
			<div><strong><?php echo JText::_('JRESEARCH_PROJECT_FUNDING').': '?></strong><span><?php echo $financiersText ?></span>, <strong><?php echo $project->finance_currency." ".$value?></strong></div>
			<?php
			if($contentArray[0] != "")
			{
			?>
				<div>&nbsp;</div>
				<p><?php echo $contentArray[0]; ?></p>
			<?php
			}
			
			if(count($contentArray) > 1)
			{
			?>
				<div style="text-align:left"><a href="index.php?option=com_jresearch&task=show&view=project&id=<?php echo $project->id; ?><?php echo isset($itemId)?'&Itemid='.$itemId:''; ?>" ><?php echo JText::_('JRESEARCH_READ_MORE'); ?></a></div>
			<?php 
			}
			?>
		</div>

	</li>	
<?php endforeach; ?>
</ul>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>