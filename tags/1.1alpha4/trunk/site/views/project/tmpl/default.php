<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for showing a single project
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo $this->project->title; ?></div>
<table cellspacing="2" cellpadding="2">
<tbody>
	<tr>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>
		<td style="width:35%;"><?php echo $this->area->name; ?></td>
	   	<?php if(empty($this->project->url_project_image)): ?>
    		<td colspan="2" rowspan="3"></td>
       	<?php else: ?>		
    		<td colspan="2" rowspan="3"><img src="<?php echo $this->project->url_project_image; ?>" border="0" alt="<?php echo $this->project->title; ?>" /></td>
    	<?php endif; ?>	
	</tr>
	<tr>
		<?php $status = $this->statusArray[$this->project->status]; ?>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_STATUS').': ' ?></td>
		<td style="width:35%;"><?php echo $status; ?></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	
	<?php $authors = $this->project->getPrincipalInvestigators(); ?>
	<?php $label = JText::_('JRESEARCH_PROJECT_LEADERS'); ?>
	<?php $flag = true; ?>
	
	<?php if(empty($authors)): ?>
		<?php $label = JText::_('JRESEARCH_MEMBERS'); ?>
		<?php $authors = $this->project->getAuthors(); ?>
		<?php $flag = false; ?>
	<?php endif; ?>	
	<?php if(!empty($authors)): ?>
		<tr>
			<td style="width:15%;" class="publicationlabel"><?php echo $label.': ' ?></td>
			<td style="width:35%;">
				<ul style="margin:0px;padding:0px;">
					<?php foreach($authors as $auth): ?>
						<li style="list-style:none;">
							<?php if($auth instanceof JResearchMember): ?>
								<?php if($auth->published): ?>
									<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $auth->id ?>"><?php echo $auth; ?></a>
								<?php else: ?>
									<?php echo $auth; ?>
								<?php endif; ?>	
							<?php else: ?>
									<?php echo $auth; ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</td>
			<?php if($flag): ?>
			<?php $nonleaders = $this->project->getNonPrincipalInvestigators(); ?>
			<?php if(!empty($nonleaders)): ?>
				<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_PROJECT_COLLABORATORS').': ' ?></td>
				<td style="width:35%;">
					<ul style="margin:0px;padding:0px;">
						<?php foreach($nonleaders as $auth): ?>
							<li style="list-style:none;">
								<?php if($auth instanceof JResearchMember): ?>
									<?php if($auth->published): ?>
										<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $auth->id ?>"><?php echo $auth; ?></a>
									<?php else: ?>
										<?php echo $auth; ?>
									<?php endif; ?>	
								<?php else: ?>
										<?php echo $auth; ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</td>
			<?php else: ?>
				<td colspan="2">&nbsp;</td>			
			<?php endif; ?>				
			<?php else: ?>
				<td colspan="2">&nbsp;</td>
			<?php endif; ?>	
		</tr>	
	<?php endif; ?>
	<?php
	//Get values and financiers for project
	$financiers = $this->project->getFinanciers();
	$value = str_replace(array(",00",".00"), ",-", $this->project->finance_value); //Replace ,/.00 with ,-
	
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
	<tr>
		<td style="width:15%;" class="publicationlabel"><?=JText::_('JRESEARCH_PROJECT_FUNDING').': '?></td>
		<td colspan="2">
			<?php
			if(count($financiers) > 0)
			{
				echo "<ul>";
				foreach($financiers as $financier)
				{
					echo "<li>".$financier."</li>";
				}
				echo "</ul>";
			}
			?>
		</td>
		<td><?=$this->project->finance_currency." ".$value?></td>
	</tr>
	
	<tr>
		<?php $startDate = trim($this->project->start_date); ?>
		<?php $colspan = 4; ?>
		<?php if(!empty($startDate) && $startDate != '0000-00-00'): ?>
		<?php $colspan = 2; ?>
	  	<td width="20%" class="field"><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></td>
	  	<td><?php echo $this->project->start_date; ?></td>
	  	<?php endif; ?>
		<?php $endDate = trim($this->project->end_date); ?>
		<?php if(!empty($endDate) && $endDate != '0000-00-00'):  ?>  	
		<td width="20%" class="field"><?php echo JText::_('JRESEARCH_DEADLINE').': '; ?></td>
		<td><?php echo $this->project->end_date; ?></td>  	
		<?php else: ?>
		<td colspan="<?php echo $colspan; ?>">&nbsp;</td>
		<?php endif; ?>	
	</tr>
	<?php $url = trim($this->project->url_project_page); ?>
	<? if(!empty($url)) : ?>
		<tr><td colspan="4"><span><?php echo !empty($url)? JHTML::_('link',$url, JText::_('JRESEARCH_PROJECT_PAGE') ):''; ?></span>
	<?php endif; ?>	
	<?php $description = trim($this->project->description); ?>
	<?php if(!empty($description)): ?>
	<tr>
		<td colspan="4" align="left" class="publicationlabel"><?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?></td>
	</tr>
	<tr>
		<td colspan="4" align="left" ><div style="text-align:justify"><?php echo str_replace('<hr id="system-readmore" />', '', $description); ?></div></td>
	</tr>
	<?php endif; ?>	
</tbody>
</table>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>