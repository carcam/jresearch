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
			<?php 
			//Show research area?
			if($this->params->get('show_researcharea') == 1):
			?>		
			<div>
				<strong><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '?></strong>
				<span><?php echo $researchArea->name;  ?></span>
			</div>
			<?php 
			endif;
			
			//Show members?
			if($this->params->get('show_members') == 1):
				$members = implode(', ',$project->getPrincipalInvestigators());
			?>			
			<div>
        		<strong><?php echo JText::_('JRESEARCH_PROJECT_LEADERS').':'?></strong>
        		<span><?php echo $members; ?></span>
			</div>
			<?php 
			endif;
			
			//Get values and financiers for project
          	$financiers = implode(', ', $project->getFinanciers());
          	
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
			
			if($project->finance_value > 0):
			?>
			<div>
				<strong><?php echo JText::_('JRESEARCH_PROJECT_FUNDING').': '?></strong>
				<span><?php echo $financiers ?></span>, <strong><?php echo $project->finance_currency." ".$value?></strong>
			</div>
			<?php
			endif;
			
			if(!empty($contentArray[0])):
			?>
				<div>&nbsp;</div>
				<p><?php echo $contentArray[0]; ?></p>
			<?php
			endif;
			?>
			<div style="text-align:left">
				<?php echo JFilterOutput::linkXHTMLSafe('<a href="index.php?option=com_jresearch&task=show&view=project&id='.$project->id.(isset($itemId)?'&Itemid='.$itemId:'').'">'.JText::_('JRESEARCH_READ_MORE').'</a>'); ?>
			</div>
		</div>

	</li>	
<?php endforeach; ?>
</ul>
<div style="text-align:center;"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>