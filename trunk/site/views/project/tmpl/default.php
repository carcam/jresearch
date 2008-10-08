<?php // no direct access
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
	
	<?php $authors = $this->project->getAuthors(); ?>
	<?php if(!empty($authors)): ?>
	<tr>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_AUTHORS').': ' ?></td>
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
		<td colspan="2">&nbsp;</td>
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
	<?php $url = trim($this->project->url_project_page); ?>
	<?php $doc = trim($this->project->url_digital_version); ?>
	<? if(!empty($url) || !empty($doc)): ?>
		<tr><td colspan="4"><span><?php echo !empty($url)? JHTML::_('link',$url, JText::_('JRESEARCH_PROJECT_PAGE') ):''; ?></span>&nbsp;<span><?php echo !empty($doc)?JHTML::_('link', $doc, JText::_('JRESEARCH_DIGITAL_VERSION')):'' ?></span></td></tr>	
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