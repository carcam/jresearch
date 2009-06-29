<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for showing a single project
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
?>
<?php $Itemid = JRequest::getVar('Itemid'); 
	  $ItemidText = !empty($Itemid)?'&Itemid='.$Itemid:'';
	  	
?>
<h1 class="componentheading"><?php echo $this->project->title; ?></h1>
<?php if($this->showHits): ?>
<div class="small"><?php echo JText::_('Hits').': '.$this->project->hits; ?></div>
<?php endif; ?>
<table cellspacing="2" cellpadding="2" width="100%">
<tbody>
	<tr>
		<td style="width:20%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>
		<td>
			<?php if($this->area->id > 1): ?>
				<a href="index.php?option=com_jresearch&amp;controller=researchAreas&amp;task=show&amp;view=researcharea&amp;id=<?php echo $this->area->id; ?><?php echo $ItemidText ?>"><?php echo $this->area->name; ?></a>
			<?php else: ?>
				<?php echo $this->area->name; ?>	
			<?php endif; ?>	
		</td>
	   	<?php
	   	if(empty($this->project->url_project_image)): 
	   	?>
    		<td colspan="2"></td>
       	<?php 
       	else: 
       		$url = JResearch::getUrlByRelative($this->project->url_project_image);
       		$thumb = ($this->params->get('thumbnail_enable', 1) == 1)?JResearch::getThumbUrlByRelative($this->project->url_project_image):$url;
       	?>		
    		<td colspan="2">
    			<a href="<?php echo $url?>" class="modal" rel="{handler: 'image'}">
    				<img src="<?php echo $thumb; ?>" border="0" alt="<?php echo $this->project->title; ?>" />
    			</a>
    		</td>
    	<?php endif; ?>	
	</tr>
	<tr>
		<?php $status = $this->statusArray[$this->project->status]; ?>
		<td style="width:20%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_STATUS').': ' ?></td>
		<td style="width:30%;"><?php echo $status; ?></td>
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
			<td style="width:20%;" class="publicationlabel"><?php echo $label.': ' ?></td>
			<?php if($this->staff_list_arrangement == 'horizontal'): ?>
				<td style="width:30%;">
						<?php $n = count($authors); 
							  $i = 0; ?>
						<?php foreach($authors as $auth): ?>
								<?php if($auth instanceof JResearchMember): ?>
									<?php if($auth->published): ?>
										<a href="index.php?option=com_jresearch&amp;view=member&amp;task=show<?php echo $ItemidText ?>&amp;id=<?php echo $auth->id ?>"><?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?></a><?php echo $i == $n - 1?'':';' ?>
									<?php else: ?>
										<?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?><?php echo $i == $n - 1?'':';' ?>
									<?php endif; ?>	
								<?php else: ?>
										<?php echo JResearchPublicationsHelper::formatAuthor($auth, $this->format); ?><?php echo $i == $n - 1?'':';' ?>
								<?php endif; ?>
								<?php $i++; ?>
						<?php endforeach; ?>
				</td>		
			<?php else: ?>
				<td style="width:30%;">
					<ul style="margin:0px;padding:0px;">
						<?php foreach($authors as $auth): ?>
							<li style="list-style:none;">
								<?php if($auth instanceof JResearchMember): ?>
									<?php if($auth->published): ?>
										<a href="index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;<?php echo $ItemidText ?>id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a>
									<?php else: ?>
										<?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?>
									<?php endif; ?>	
								<?php else: ?>
										<?php echo JResearchPublicationsHelper::formatAuthor($auth, $this->format); ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</td>
			<?php endif; ?>	
			<?php if($flag): ?>
			<?php $nonleaders = $this->project->getNonPrincipalInvestigators(); ?>
			<?php if(!empty($nonleaders)): ?>
				<td style="width:20%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_PROJECT_COLLABORATORS').': ' ?></td>
				<?php if($this->staff_list_arrangement == 'horizontal'): ?>
					<td style="width:30%;">
							<?php $n = count($nonleaders); 
								  $i = 0; ?>
							<?php foreach($nonleaders as $auth): ?>
									<?php if($auth instanceof JResearchMember): ?>
										<?php if($auth->published): ?>
											<a href="index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;<?php echo $ItemidText ?>id=<?php echo $auth->id ?>"><?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?></a><?php echo $i == $n - 1?'':';' ?>
										<?php else: ?>
											<?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?><?php echo $i == $n - 1?'':';' ?>
										<?php endif; ?>	
									<?php else: ?>
											<?php echo JResearchPublicationsHelper::formatAuthor($auth, $this->format); ?><?php echo $i == $n - 1?'':';' ?>
									<?php endif; ?>
									<?php $i++; ?>
							<?php endforeach; ?>
					</td>
				<?php else: ?>
					<td style="width:30%;">
						<ul style="margin:0px;padding:0px;">
							<?php foreach($nonleaders as $auth): ?>
								<li style="list-style:none;">
									<?php if($auth instanceof JResearchMember): ?>
										<?php if($auth->published): ?>
											<a href="index.php?option=com_jresearch&amp;view=member&amp;task=show<?php echo $ItemidText ?>&amp;id=<?php echo $auth->id ?>"><?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?></a>
										<?php else: ?>
											<?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?>
										<?php endif; ?>	
									<?php else: ?>
											<?php echo JResearchPublicationsHelper::formatAuthor($auth, $this->format); ?>
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
	
	<?php 
	$coops = $this->project->getCooperations();
	
	if(!empty($coops)):
	?>
	<tr>
		<th class="field"><?php echo JText::_('JRESEARCH_COOPERATION_WITH'); ?></th>
		<td colspan="3">
			<ul>
				<?php
				foreach($coops as $coop):
				?>
					<li>
						<?php echo $coop->name; ?>
					</li>
				<?php
				endforeach;
				?>
			</ul>
		</td>
	</tr>
	<?php
	endif;
	?>
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
	<?php
	if($this->project->countFinanciers() > 0):
	?>
	<tr>
		<td><?php echo JText::_('JRESEARCH_FUNDED_BY').': '; ?></td>
		<td>
			<?php 
			$financiers = $this->project->getFinanciers();
			echo implode('<br />', $financiers);
			?>
		</td>
		<td colspan="2"><?php echo $this->project->finance_value.' '.$this->project->finance_currency; ?></td>
	</tr>
	<?php
	endif;
	?>
	<?php $url = str_replace('&', '&amp;', trim($this->project->url)); ?>
	<?php if(!empty($url)): ?>
		<tr><td colspan="4"><span><?php echo !empty($url)? JHTML::_('link',$url, JText::_('JRESEARCH_PROJECT_PAGE') ):''; ?></span></td></tr>
	<?php endif; ?>	
	<?php $n = $this->project->countAttachments(); ?>
	<?php if($n > 0): ?>
		<tr><td class="publicationlabel"><?php echo JText::_('JRESEARCH_FILES').': ' ?></td><td colspan="3"><ul style="list-style:none;">
		<?php for($i=0; $i<$n; $i++): ?>
			<?php $attach = $this->project->getAttachment($i, 'projects'); ?>
			<?php echo !empty($attach)?'<li>'.JHTML::_('JResearch.attachment', $attach).'<li>':''; ?>
		<?php endfor; ?>
		</ul>
		</td></tr>
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