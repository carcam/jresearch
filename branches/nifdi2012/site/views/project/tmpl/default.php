<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for showing a single project
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
jresearchimport('helpers.publications', 'jresearch.admin')
?>
<?php $Itemid = JRequest::getVar('Itemid'); 
	  $ItemidText = !empty($Itemid)?'&amp;Itemid='.$Itemid:'';
	  	
?>
<h1 class="componentheading"><?php echo $this->project->title; ?></h1>
<?php if($this->showHits): ?>
<div class="small"><?php echo JText::_('JRESEARCH_HITS').': '.$this->project->hits; ?></div>
<?php endif; ?>
<dl class="jresearchitem">
	<?php 
		$researchAreas = $this->project->getResearchAreas();
		$researchAreasNames = array();
		foreach($researchAreas as $area){
			if($area->id > 1){
				if($area->published)
					$researchAreasNames[] = JHTML::_('jresearchfrontend.link', $area->name, 'researcharea', 'display', $area->id, $itemId);
				else
					$researchAreasNames[] = $area->name;
			}
		} 
	?>
	<?php if(!empty($researchAreasNames)) : ?>			
	<dt><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></dt>		
		<dd>					
			<span><?php echo implode(', ', $researchAreasNames); ?></span>
		</dd>
   <?php endif; ?> 			
   	<?php
   	if(!empty($this->project->logo)): 
       ?>		
    	<dd class="projectlogo">
    		<img src="<?php echo $this->project->logo; ?>" border="0" alt="<?php echo $this->project->title; ?>" />
    	</dd>
    <?php endif; ?>	

	<?php $status = $this->statusArray[$this->project->status]; ?>
	<dt><?php echo JText::_('JRESEARCH_STATUS').': ' ?></dt>
	<dd><?php echo $status; ?></dd>
	
	<?php $authors = $this->project->getPrincipalInvestigators(); ?>
	<?php $label = JText::_('JRESEARCH_PROJECT_LEADERS'); ?>
	<?php $flag = true; ?>
	
	<?php if(empty($authors)): ?>
		<?php $label = JText::_('JRESEARCH_MEMBERS'); ?>
		<?php $authors = $this->project->getAuthors(); ?>
		<?php $flag = false; ?>
	<?php endif; ?>	
	<?php if(!empty($authors)): ?>
	<dt><?php echo $label.': ' ?></dt>
		<?php if($this->staff_list_arrangement == 'horizontal'): ?>
		<dd>
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
		</dd>		
		<?php else: ?>
			<dd>
				<ul style="margin:0px;padding:0px;">
					<?php foreach($authors as $auth): ?>
						<li style="list-style:none;">
							<?php if($auth instanceof JResearchMember): ?>
								<?php if($auth->published): ?>
									<a href="index.php?option=com_jresearch&amp;view=member&amp;task=show<?php echo $ItemidText ?>&amp;id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a>
								<?php else: ?>
									<?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?>
								<?php endif; ?>	
							<?php else: ?>
									<?php echo JResearchPublicationsHelper::formatAuthor($auth, $this->format); ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</dd>
			<?php endif; ?>	
			<?php if($flag): ?>
			<?php $nonleaders = $this->project->getNonPrincipalInvestigators(); ?>
			<?php if(!empty($nonleaders)): ?>
				<dt><?php echo JText::_('JRESEARCH_PROJECT_COLLABORATORS').': ' ?></dt>
				<?php if($this->staff_list_arrangement == 'horizontal'): ?>
					<dd>
						<?php $n = count($nonleaders); 
							  $i = 0; ?>
						<?php foreach($nonleaders as $auth): ?>
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
					</dd>
				<?php else: ?>
					<dd>
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
					</dd>
				<?php endif; ?>			
			<?php endif; ?>				
		<?php endif; ?>	
	<?php endif; ?>
	<?php $startDate = trim($this->project->start_date); ?>
	<?php if(!empty($startDate) && $startDate != '0000-00-00'): ?>
	  	<dt><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></dt>
	  	<dd><?php echo $this->project->start_date; ?></dd>
	<?php endif; ?>
	<?php $endDate = trim($this->project->end_date); ?>
	<?php if(!empty($endDate) && $endDate != '0000-00-00'):  ?>  	
		<dt><?php echo JText::_('JRESEARCH_DEADLINE').': '; ?></dt>
		<dd><?php echo $this->project->end_date; ?></dd>  	
	<?php endif; ?>	
	<?php $n = $this->project->countAttachments(); ?>
	<?php if($n > 0): ?>
		<dt><?php echo JText::_('JRESEARCH_FILES').': ' ?></dt>
		<dd><ul>
		<?php for($i=0; $i<$n; $i++): ?>
			<?php $attach = $this->project->getAttachment($i, 'projects'); ?>
			<?php echo !empty($attach)?'<li>'.JHTML::_('JResearchhtml.attachment', $attach).'</li>':''; ?>
		<?php endfor; ?>
		</ul></dd>
	<?php endif; ?>	
	<?php $url = str_replace('&', '&amp;', trim($this->project->url)); ?>
	<?php if(!empty($url)): ?>
		<dd><?php echo !empty($url)? JHTML::_('link',$url, JText::_('JRESEARCH_PROJECT_PAGE') ):''; ?></dd>
	<?php endif; ?>		
	<?php $itemId = JRequest::getVar('Itemid'); ?>
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
	  			<a href="index.php?option=com_jresearch&amp;publications_view_all=1&amp;task=show&amp;view=project&amp;id=<?php echo $this->project->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>#pubslist"><?php echo JText::_('JRESEARCH_VIEW_ALL'); ?></a>
	  	<?php else: ?>
	  			<?php if($this->publications_view_all): ?>		
	  				<a href="index.php?option=com_jresearch&amp;publications_view_all=0&amp;task=show&amp;view=project&amp;id=<?php echo $this->project->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>#pubslist"><?php echo JText::_('JRESEARCH_VIEW_LESS'); ?></a>
	  			<?php endif; ?>
	  	<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php if(!empty($this->description)): ?>
	<h2 class="contentheading">
		<?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?>
	</h2>
	<div><?php echo $this->description; ?></div>
	<?php endif; ?>	
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>