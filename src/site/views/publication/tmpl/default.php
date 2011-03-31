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
	  	
?>
<div style="float: right;"><?php echo JHTML::_('jresearchfrontend.icon','edit','publications', $this->publication->id); ?></div>
<h2 class="componentheading"><?php echo $this->publication->title; ?></h2>
<?php if($this->showHits): ?>
<div class="jresearchhits"><?php echo JText::_('Hits').': '.$this->publication->hits; ?></div>
<?php endif; ?>
<table class="frontendsingleitem">
<tbody>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_RESEARCH_AREAS').': ' ?></th>
		<td><?php echo JHTML::_('jresearchfrontend.researchareaslinks', $this->publication->getResearchAreas('names')); ?></td>
		<?php $year = $this->publication->year; ?>
		<?php if($year != null && $year != '0000' && !empty($year)): ?>
		<th scope="row"><?php echo JText::_('JRESEARCH_YEAR').': ' ?></th>
		<td><?php echo $this->publication->year; ?></td>
		<?php else: ?>
		<td colspan="2"></td>
		<?php endif; ?>
	</tr>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></th>
		<td><?php echo JText::_('JRESEARCH_'.strtoupper($this->publication->pubtype)); ?></td>
		<?php $keywords = trim($this->publication->keywords); ?>
		<?php if(!empty($keywords)): ?>		
		<th scope="row"><?php echo JText::_('JRESEARCH_KEYWORDS').': ' ?></th>		
		<td><?php echo $this->publication->keywords; ?></td>
		<?php else: ?>
		<td colspan="2"></td>
		<?php endif; ?>
	</tr>
	<?php $authors = $this->publication->getAuthors(); ?>
	<?php if(!empty($authors)): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_AUTHORS').': ' ?></th>
		
		<?php if($this->staff_list_arrangement == 'horizontal'): ?>
		<td style="width:85%;" colspan="3">
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
		</td>		
		<?php else: ?>
		<td>
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
		</td>
		<?php endif; ?>
		<td colspan="2"></td>		
	</tr>	
	<?php endif; ?>
	
	<?php require_once(JPATH_COMPONENT.DS.'views'.DS.'publication'.DS.'types'.DS.$this->publication->pubtype.'.php') ?>
	<tr>		
	
	<?php $colspan=4; ?>
	<?php $acceptance = trim($this->publication->journal_acceptance_rate); ?>
	<?php $impact_factor = trim($this->publication->impact_factor); ?>
	<?php if(!empty($acceptance) && ($this->params->get('show_journal_acceptance_rate') == 'yes')): ?>
		<?php $colspan = 2; ?>
		<th scope="row"><?php echo JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE').': ' ?></th>		
		<td><?php echo $acceptance; ?>%</td>
	<?php else: ?>
			<td colspan="<?php echo $colspan; ?>"></td>
	<?php endif; ?>
	<?php if(!empty($impact_factor) && ($this->params->get('show_journal_impact_factor') == 'yes')): ?>
		<?php $colspan -= 2; ?>	
		<th scope="row"><?php echo JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR').': ' ?></th>		
		<td><?php echo $impact_factor; ?></td>			
	<?php endif; ?>
	<?php if($colspan > 0): ?>
		<td colspan="<?php echo $colspan; ?>"></td>
	<?php endif; ?>
	</tr>
		
	<?php $awards = trim($this->publication->awards); ?>
	<?php if(!empty($awards) && ($this->params->get('show_awards') == 'yes')): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_AWARDS').': '; ?></th>
		<td style="width:85%;" colspan="3"><div style="text-align:justify;"><?php echo $awards; ?></div></td>
	</tr>
	<?php endif; ?>	
	
	<?php if($this->params->get('enable_export_frontend') == 'yes'): ?>	
		<?php if($this->params->get('show_bibtex') == "yes"): ?>
		<tr>
			<th scope="row"><div style="text-align:justify;"><?php echo JText::_('BibTex').': '; ?></div></th>
		</tr>
		<tr>
			<td colspan="4" align="left"><textarea rows="6" cols="45"><?php echo $output2; ?></textarea></td>
		</tr>
		<?php endif; ?>
	<?php endif; ?>
	
	<?php $note = trim($this->publication->note); ?>	
	<?php if(!empty($note)): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_NOTE').': '; ?></th>
		<td style="width:85%;" colspan="3"><div style="text-align:justify;"><?php echo $note; ?></div></td>	
	</tr>
	<?php endif; ?>	
	
	<?php $abstract = trim($this->publication->abstract); ?>
	<?php if(!empty($abstract)): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_ABSTRACT').': '; ?></th>
		<td style="width:85%;" colspan="3"><div style="text-align:justify;"><?php echo $abstract; ?></div></td>	
	</tr>
	<?php endif; ?>	
	<?php $comments = trim($this->publication->comments); ?>	
	<?php if(!empty($comments)): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_COMMENTS').': '; ?></th>
		<td style="width:85%;" colspan="3"><div style="text-align:justify;"><?php echo $comments; ?></div></td>	
	</tr>
	<?php endif; ?>	
	
	<tr><td colspan="4" style="padding-left: 0px;">
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
	</td></tr>			
	
</tbody>
</table>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>