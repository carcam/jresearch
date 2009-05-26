<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for showing a single project
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $Itemid = JRequest::getVar('Itemid'); 
	  $ItemidText = !empty($Itemid)?'&Itemid='.$Itemid:'';
	  	
?>
<h1 class="componentheading"><?php echo $this->project->title; ?></h1>
<table cellspacing="2" cellpadding="2">
<tbody>
	<tr>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>
		<td style="width:35%;">
			<?php if($this->area->id > 1): ?>
				<a href="index.php?option=com_jresearch&controller=researchAreas&task=show&view=researcharea&id=<?php echo $this->area->id; ?><?php echo $ItemidText ?>"><?php echo $this->area->name; ?></a>
			<?php else: ?>
				<?php echo $this->area->name; ?>	
			<?php endif; ?>	
		</td>
	   	<?php if(empty($this->project->url_project_image)): ?>
    		<td colspan="2"></td>
       	<?php else: ?>		
    		<td colspan="2"><img src="<?php echo $this->project->url_project_image; ?>" border="0" alt="<?php echo $this->project->title; ?>" /></td>
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
			<?php if($this->staff_list_arrangement == 'horizontal'): ?>
				<td style="width:35%;">
						<?php $n = count($authors); 
							  $i = 0; ?>
						<?php foreach($authors as $auth): ?>
								<?php if($auth instanceof JResearchMember): ?>
									<?php if($auth->published): ?>
										<a href="index.php?option=com_jresearch&view=member&task=show<?php echo $ItemidText ?>&id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a><?php echo $i == $n - 1?'':',' ?>
									<?php else: ?>
										<?php echo $auth->__toString(); ?><?php echo $i == $n - 1?'':',' ?>
									<?php endif; ?>	
								<?php else: ?>
										<?php echo $auth; ?><?php echo $i == $n - 1?'':',' ?>
								<?php endif; ?>
								<?php $i++; ?>
						<?php endforeach; ?>
				</td>		
			<?php else: ?>
				<td style="width:35%;">
					<ul style="margin:0px;padding:0px;">
						<?php foreach($authors as $auth): ?>
							<li style="list-style:none;">
								<?php if($auth instanceof JResearchMember): ?>
									<?php if($auth->published): ?>
										<a href="index.php?option=com_jresearch&view=member&task=show<?php echo $ItemidText ?>&id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a>
									<?php else: ?>
										<?php echo $auth->__toString(); ?>
									<?php endif; ?>	
								<?php else: ?>
										<?php echo $auth; ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</td>
			<?php endif; ?>	
			<?php if($flag): ?>
			<?php $nonleaders = $this->project->getNonPrincipalInvestigators(); ?>
			<?php if(!empty($nonleaders)): ?>
				<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_PROJECT_COLLABORATORS').': ' ?></td>
				<?php if($this->staff_list_arrangement == 'horizontal'): ?>
					<td style="width:35%;">
							<?php $n = count($nonleaders); 
								  $i = 0; ?>
							<?php foreach($nonleaders as $auth): ?>
									<?php if($auth instanceof JResearchMember): ?>
										<?php if($auth->published): ?>
											<a href="index.php?option=com_jresearch&view=member&task=show<?php echo $ItemidText ?>&id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a><?php echo $i == $n - 1?'':',' ?>
										<?php else: ?>
											<?php echo $auth->__toString(); ?><?php echo $i == $n - 1?'':',' ?>
										<?php endif; ?>	
									<?php else: ?>
											<?php echo $auth; ?><?php echo $i == $n - 1?'':',' ?>
									<?php endif; ?>
									<?php $i++; ?>
							<?php endforeach; ?>
					</td>
				<?php else: ?>
					<td style="width:35%;">
						<ul style="margin:0px;padding:0px;">
							<?php foreach($nonleaders as $auth): ?>
								<li style="list-style:none;">
									<?php if($auth instanceof JResearchMember): ?>
										<?php if($auth->published): ?>
											<a href="index.php?option=com_jresearch&view=member&task=show<?php echo $ItemidText ?>&id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a>
										<?php else: ?>
											<?php echo $auth->__toString(); ?>
										<?php endif; ?>	
									<?php else: ?>
											<?php echo $auth; ?>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</td>
				<?php endif; ?>
			<?php else: ?>
				<td colspan="2">&nbsp;</td>			
			<?php endif; ?>				
			<?php else: ?>
				<td colspan="2">&nbsp;</td>
			<?php endif; ?>	
		</tr>	
	<?php endif; ?>
	
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
	<?php $url = trim($this->project->url); ?>
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