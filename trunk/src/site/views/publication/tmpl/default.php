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
<div class="divTable">
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('JRESEARCH_RESEARCH_AREAS').': ' ?></div>
		<div class="divTdl divTdl2"><?php echo JHTML::_('jresearchfrontend.researchareaslinks', $this->publication->getResearchAreas('names')); ?></div>
		<?php $year = $this->publication->year; ?>
		<?php if($year != null && $year != '0000' && !empty($year)): ?>
		<div class="divTd"><?php echo JText::_('JRESEARCH_YEAR').': ' ?></div>
		<div class="divTdl"><?php echo $this->publication->year; ?></div>
		<?php else: ?>
		<span></span>
		<?php endif; ?>
	    <div class="divEspacio" ></div>
	</div>
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></div>
		<div class="divTdl divTdl2"><?php echo JText::_('JRESEARCH_'.strtoupper($this->publication->pubtype)); ?></div>
		<?php $keywords = trim($this->publication->keywords); ?>
		<?php if(!empty($keywords)): ?>		
		<div class="divTd"><?php echo JText::_('JRESEARCH_KEYWORDS').': ' ?></div>		
		<div class="divTdl"><?php echo $this->publication->keywords; ?></div>
		<?php else: ?>
		<span></span>
		<?php endif; ?>
	    <div class="divEspacio" ></div>		
	</div>
	<?php $authors = $this->publication->getAuthors(); ?>
	<?php if(!empty($authors)): ?>
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('JRESEARCH_AUTHORS').': ' ?></div>
		
		<?php if($this->staff_list_arrangement == 'horizontal'): ?>
		<div class="divTdl">
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
		</div>		
		<?php else: ?>
		<div class="divTdl">
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
		</div>
		<?php endif; ?>
	    <div class="divEspacio" ></div>		
	</div>	
	<?php endif; ?>
	
	<?php require_once(JPATH_COMPONENT.DS.'views'.DS.'publication'.DS.'types'.DS.$this->publication->pubtype.'.php') ?>
	<div class="divTR">		
	
	<?php $colspan=4; ?>
	<?php $acceptance = trim($this->publication->journal_acceptance_rate); ?>
	<?php $impact_factor = trim($this->publication->impact_factor); ?>
	<?php if(!empty($acceptance) && ($this->params->get('show_journal_acceptance_rate') == 'yes')): ?>
		<?php $colspan = 2; ?>
		<div class="divTd"><?php echo JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE').': ' ?></div>		
		<div class="divTdl"><?php echo $acceptance; ?>%</div>
	<?php else: ?>
			<span></span>
	<?php endif; ?>
	<?php if(!empty($impact_factor) && ($this->params->get('show_journal_impact_factor') == 'yes')): ?>
		<?php $colspan -= 2; ?>	
		<div class="divTd"><?php echo JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR').': ' ?></div>		
		<div class="divTdl"><?php echo $impact_factor; ?></div>			
	<?php endif; ?>
	<?php if($colspan > 0): ?>
		<span></span>
	<?php endif; ?>
    	<div class="divEspacio" ></div>	
	</div>
		
	<?php $awards = trim($this->publication->awards); ?>
	<?php if(!empty($awards) && ($this->params->get('show_awards') == 'yes')): ?>
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('JRESEARCH_AWARDS').': '; ?></div>
		<div class="divTdl"><div style="text-align:justify;"><?php echo $awards; ?></div></div>
	    <div class="divEspacio" ></div>		
	</div>	
	<?php endif; ?>	
	
	<?php if($this->params->get('enable_export_frontend') == 'yes'): ?>	
		<?php if($this->params->get('show_bibtex') == "yes"): ?>
		<div class="divTR">
			<div class="divTd"><div style="text-align:justify;"><?php echo JText::_('BibTex').': '; ?></div></div>
		</div>
		<div class="divTR">
			<div><textarea rows="6" cols="45"><?php echo $output2; ?></textarea></div>
		</div>
		<?php endif; ?>
	    <div class="divEspacio" ></div>		
	<?php endif; ?>
	
	<?php $note = trim($this->publication->note); ?>	
	<?php if(!empty($note)): ?>
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('JRESEARCH_NOTE').': '; ?></div>
		<div class="divTdl"><div style="text-align:justify;"><?php echo $note; ?></div></div>	
	    <div class="divEspacio" ></div>		
	</div>
	<?php endif; ?>	
	
	<?php $abstract = trim($this->publication->abstract); ?>
	<?php if(!empty($abstract)): ?>
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('JRESEARCH_ABSTRACT').': '; ?></div>
		<div class="divTdl"><div style="text-align:justify;"><?php echo $abstract; ?></div></div>	
	    <div class="divEspacio" ></div>		
	</div>
	<?php endif; ?>	
	<?php $comments = trim($this->publication->comments); ?>	
	<?php if(!empty($comments)): ?>
	<div class="divTR">
		<div class="divTd"><?php echo JText::_('JRESEARCH_COMMENTS').': '; ?></div>
		<div class="divTdl"><div style="text-align:justify;"><?php echo $comments; ?></div></div>	
	    <div class="divEspacio" ></div>		
	</div>
	<?php endif; ?>	
	
	<div class="divTR">
	<div>
	<?php $url = str_replace('&', '&amp;', trim($this->publication->url));
              $n = $this->publication->countAttachments();
        ?>
        <?php if($n == 1):
            $attach = $this->publication->getAttachment(0, 'publications');
	    echo !empty($attach)?'<div><strong>'.JText::_('JRESEARCH_FULLTEXT').':</strong> '.JHTML::_('jresearchhtml.attachment', $attach).'</div>':'';
            endif;
         ?>

	<?php if(!empty($url)): ?> 
	<div><?php echo JHTML::_('link', $url, JText::_('JRESEARCH_ONLINE_VERSION')); ?></div>
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
	</div>
	    <div class="divEspacio" ></div>	
	</div>			
</div>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>