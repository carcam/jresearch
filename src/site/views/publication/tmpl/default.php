<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for showing a single publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php $Itemid = JRequest::getVar('Itemid'); 
	  $ItemidText = !empty($Itemid)?'&amp;Itemid='.$Itemid:'';
	  
	jresearchimport('helpers.exporters.factory', 'jresearch.admin');
	$document = JFactory::getDocument(); 
	$format = "bibtex";		
	$exporter =& JResearchPublicationExporterFactory::getInstance($format);		
	$output2 = $exporter->parse($this->publication);				
 	$canDoPublications = JResearchAccessHelper::getActions(); 
	$canDo = JResearchAccessHelper::getActions('publication', $this->publication->id);
	$user = JFactory::getUser();
	if($canDo->get('core.publications.edit') || ($canDoPublications->get('core.publications.edit.own') && $this->publication->created_by == $user->get('id'))):	 
?>	 	
	<span>	
		<?php echo JHTML::_('jresearchfrontend.icon','edit', 'publications', $this->publication->id); ?> 
	</span>
<?php endif; ?>
<h1 class="componentheading"><?php echo $this->escape($this->publication->title); ?></h1>
<?php if($this->showHits): ?>
<div class="jresearchhits"><?php echo JText::_('Hits').': '.$this->publication->hits; ?></div>
<?php endif; ?>
<dl class="jresearchitem">
	<?php $researchAreaLinks = $this->publication->getResearchAreas('names'); 
		if(!empty($researchAreaLinks)):
	?>	
	<dt><?php echo JText::_('JRESEARCH_RESEARCH_AREAS').': ' ?></dt>
	<dd><?php echo JHTML::_('jresearchfrontend.researchareaslinks', $this->publication->getResearchAreas('names')); ?></dd>
	<?php endif; ?>
		
	<?php $year = $this->publication->year; ?>
	<?php if($year != null && $year != '0000' && !empty($year)): ?>
	<dt><?php echo JText::_('JRESEARCH_YEAR').': ' ?></dt>
	<dd><?php echo $this->publication->year; ?></dd>
	<?php endif; ?>
	<dt><?php echo JText::_('JRESEARCH_TYPE').': ' ?></dt>
	<dd><?php echo JText::_('JRESEARCH_'.strtoupper($this->publication->pubtype)); ?></dd>
	<?php $keywords = trim($this->publication->keywords); ?>
	<?php if(!empty($keywords)): ?>		
		<dt><?php echo JText::_('JRESEARCH_KEYWORDS').': ' ?></dt>		
		<dd><?php echo $this->publication->keywords; ?></dd>
	<?php endif; ?>
	<?php $authors = $this->publication->getAuthors(); ?>
	<?php if(!empty($authors)): ?>
	<dt>
		<?php echo JText::_('JRESEARCH_AUTHORS').': ' ?>
	</dt>
	<dd>		
	<?php if($this->staff_list_arrangement == 'horizontal'): ?>
				<?php $n = count($authors); 
					  $i = 0; ?>
				<?php foreach($authors as $auth): ?>
						<?php if($auth instanceof JResearchMember): ?>
							<?php if($auth->published): ?>
								<?php echo JHTML::_('jresearchfrontend.link', JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format), 'member', 'show', $auth->id)?><?php echo $i == $n - 1?'':';' ?>
							<?php else: ?>
								<?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?><?php echo $i == $n - 1?'':';' ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo JResearchPublicationsHelper::formatAuthor($auth, $this->format); ?><?php echo $i == $n - 1?'':';' ?>
						<?php endif; ?>
						<?php $i++; ?>
				<?php endforeach; ?>
		<?php else: ?>
			<ul>
				<?php foreach($authors as $auth): ?>
					<li style="list-style:none;">
						<?php if($auth instanceof JResearchMember): ?>
							<?php if($auth->published): ?>
								<?php echo JHTML::_('jresearchfrontend.link', JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format), 'member', 'show', $auth->id)?>
							<?php else: ?>
								<?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo JResearchPublicationsHelper::formatAuthor($auth, $this->format); ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</dd>	
	<?php endif; ?>
	
	<?php require_once(JPATH_COMPONENT.DS.'views'.DS.'publication'.DS.'types'.DS.$this->publication->pubtype.'.php') ?>
	
	<?php $acceptance = trim($this->publication->journal_acceptance_rate); ?>
	<?php $impact_factor = trim($this->publication->impact_factor); ?>
	<?php if(!empty($acceptance) && ($this->params->get('show_journal_acceptance_rate') == 1)): ?>
		<?php $colspan = 2; ?>
		<dt><?php echo JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE').': ' ?></dt>		
		<dd><?php echo $acceptance; ?>%</dd>
	<?php endif; ?>
	<?php if(!empty($impact_factor) && ($this->params->get('show_journal_impact_factor') == 1)): ?>
		<dt><?php echo JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR').': ' ?></dt>		
		<dd><?php echo $impact_factor; ?></dd>			
	<?php endif; ?>
		
	<?php $awards = trim($this->publication->awards); ?>
	<?php if(!empty($awards) && ($this->params->get('show_awards') == 1)): ?>
		<dt><?php echo JText::_('JRESEARCH_AWARDS').': '; ?></dt>
		<dd><?php echo $awards; ?></dd>
	<?php endif; ?>				

	<?php if($this->params->get('enable_export_frontend') == 1): ?>	
		<?php if($this->params->get('show_bibtex') == 1): ?>
		<dt>
			<?php echo JText::_('BibTex').': '; ?>
		</dt>
		<dd>
			<textarea rows="6" cols="45"><?php echo $output2; ?></textarea>
		</dd>
		<?php endif; ?>
	<?php endif; ?>
	
	<?php $note = trim($this->publication->note); ?>	
	<?php if(!empty($note)): ?>
		<dt><?php echo JText::_('JRESEARCH_NOTE').': '; ?></dt>
		<dd><?php echo $note; ?></dd>	
	<?php endif; ?>	
	
	<?php $abstract = trim($this->publication->abstract); ?>
	<?php if(!empty($abstract)): ?>
		<dt><?php echo JText::_('JRESEARCH_ABSTRACT').': '; ?></dt>
		<dd><?php echo $abstract; ?></dd>	
	<?php endif; ?>	
	<?php $comments = trim($this->publication->comments); ?>	
	<?php if(!empty($comments)): ?>
		<dt><?php echo JText::_('JRESEARCH_COMMENTS').': '; ?></dt>
		<dd><?php echo $comments; ?></dd>
	<?php endif; ?>	
</dl>	
<div class="divTR">
	<?php $url = JHTML::_('jresearchfrontend.getExternalLink', $this->publication->url);
              $n = $this->publication->countAttachments();
        ?>
        <?php if($n == 1):
            $attach = $this->publication->getAttachment(0, 'publications');
	    echo !empty($attach)?'<span><strong>'.JText::_('JRESEARCH_FULLTEXT').':</strong> '.JHTML::_('jresearchhtml.attachment', $attach).'</span>':'';
            endif;
         ?>
	<?php if(!empty($url)): ?> 
	<span><?php echo JHTML::_('link', $url, JText::_('JRESEARCH_ONLINE_VERSION'),'target="_blank"'); ?></span>
    <?php endif ?>
	<?php if($this->showBibtex): 
		echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=bibtex&amp;id='.$this->publication->id, '[Bibtex]').'</span>';		
	 endif;?>	
	<?php if($this->showRIS): 
		echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=ris&amp;id='.$this->publication->id, '[RIS]').'</span>';		
	 endif;?>
	 <?php if($this->showMODS): 
		echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=mods&amp;id='.$this->publication->id, '[MODS]').'</span>';		
	 endif;?>				
	<div class="divEspacio" ></div>	
</div>			

<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>