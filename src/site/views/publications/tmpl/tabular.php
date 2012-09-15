<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for listing publications per group
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
$canDoPublications = JResearchAccessHelper::getActions(); 
$user = JFactory::getUser();
?>
<?php if($this->showHeader): ?>
<h1 class="componentheading"><?php echo $this->escape($this->header); ?></h1>
<?php endif; 
$nCols = 2;
if($this->showResearchAreas) $nCols++;
if($this->showYear) $nCols++;
if($this->showScore) $nCols++;
$exportColumn = false;
if($this->showBibtex || $this->showRis || $this->showMods || $this->showDigitalVersion || $this->showFulltext){ $nCols++; $exportColumn = true; }
if($this->showAuthors) $nCols++;	
if($this->showHits) $nCols++;

?>
<?php if($this->exportAll): ?>
<div style="text-align: right;">
<?php 		
		echo '<span>'.JText::_('JRESEARCH_PUBLICATIONS_EXPORT_ALL').': </span>';
		echo '<span>';
?>
<?php if($this->exportAllFormat == 'all'): 
		$exportAllLinks = array();
		$exportAllLinks[] = JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportAll&format=bibtex'), 'Bibtex');
		$exportAllLinks[] = JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportAll&format=ris'), 'RIS');
		$exportAllLinks[] = JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportAll&format=mods'), JText::_('MODS'));		
		echo implode(', ', $exportAllLinks);
		echo '</span>';
	else:
		echo '<span>'.JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportAll&format='.$this->exportAllFormat), $this->exportAllFormat == 'bibtex'? JString::ucfirst($this->exportAllFormat) : JString::strtoupper($this->exportAllFormat)).'</span>';		
	endif;
?>
</div>
<?php endif; ?>
<form name="adminForm" method="post" id="adminForm" action="<?php echo JURI::current(); ?>">
	<div style="text-align:left">
		<?php echo $this->filter; ?>
	</div>
	<table style="clear: both;width:100%;">
		<thead>
		<tr>		
			<th style="width:3%;">#</th>
			<th style="text-align:center;width:25%;"><?php echo JText::_('JRESEARCH_TITLE'); ?></th>
			<?php if($this->showAuthors): ?>
				<th style="text-align:center;width:25%;"><?php echo JText::_('JRESEARCH_AUTHORS'); ?></th>
			<?php endif; ?>
			<?php if($this->showYear): ?>
				<th style="text-align:center;width:10%;"><?php echo JText::_('JRESEARCH_YEAR'); ?></th>
			<?php endif; ?>
			<?php if($this->showResearchAreas): ?>
				<th style="text-align:center;width:20%;"><?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?></th>			
			<?php endif; ?>
			<?php if($this->showScore): ?>
				<th style="text-align:center;width:10%;"><?php echo $this->fieldForPunctuation == 'journal_acceptance_rate'?JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE'):JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR'); ?></th>
			<?php endif; ?>
			<?php if($this->showHits): ?>
				<th style="text-align:center;width:5%;"><?php echo JText::_('JRESEARCH_HITS'); ?></th>
			<?php endif;?>
			<?php $user = JFactory::getUser(); 
				  if(!$user->guest): 
				  		$nCols++; ?>
						<th style="width:2%;"></th>						
			<?php endif; ?>			
			<?php if($exportColumn): ?>
			<th><?php $nCols++; ?></th>
			<?php endif?>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="<?php echo $nCols; ?>">
					<div class="frontendPagination"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></div>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
		<?php 
			$n = count($this->items);
			
			if($n > 0):
			$Itemid = JRequest::getVar('Itemid');
			$outIndex = JRequest::getInt('limitstart', 0) + 1;
			for($i=0; $i<$n; $i++){
	          		if($this->showAuthors){
						$authors = $this->items[$i]->getAuthors();
	        	  		$text = JResearchPublicationsHelper::formatAuthorsArray($authors, $this->format);
	          		}
		?>
				<tr class="<?php $k = i%2; echo "row$k"; ?>">
					<td style="text-align:center;"><?php echo $outIndex; ?></td>
					<td>					
						<?php echo JHTML::_('jresearchfrontend.link', $this->items[$i]->title ,'publication', 'show', $this->items[$i]->id); ?>
					 <?php 		
						$canDo = JResearchAccessHelper::getActions('publication', $this->items[$i]->id);
						if($canDo->get('core.publications.edit') || ($canDoPublications->get('core.publications.edit.own') && $this->items[$i]->created_by == $user->get('id'))):	 
					 ?>	 	
					 	<span>	
							<?php echo JHTML::_('jresearchfrontend.icon','edit', 'publications', $this->items[$i]->id); ?> 
						</span>
					 <?php endif; ?>
					<?php if($canDoPublications->get('core.publications.delete')): ?>
							<?php echo JHTML::_('jresearchfrontend.icon','remove', 'publications', $this->items[$i]->id); ?>
					<?php endif; ?>	
					
					</td>
					<?php if($this->showAuthors): ?>
						<td style="text-align:center"><?php echo $text; ?></td>
					<?php endif; ?>
					<?php if($this->showYear): ?>
						<td style="text-align:center"><?php echo $this->items[$i]->year; ?></td>
					<?php endif; ?>	
					<?php if($this->showResearchAreas): ?>
						<td style="text-align:center"><?php echo JHTML::_('jresearchfrontend.researchareaslinks', $this->items[$i]->getResearchAreas('names'), 'inline'); ?></td>
					<?php endif; ?>
					<?php if($this->showScore): ?>
						<td style="text-align:center"><?php echo !empty($this->items[$i]->journal_acceptance_rate)?$this->items[$i]->journal_acceptance_rate:'--'; ?></td>
					<?php endif; ?>
					<?php if($this->showHits): ?>
						<td style="text-align:center"><?php echo $this->items[$i]->hits; ?></td>					
					<?php endif;?>
					<?php if(!$user->guest): ?>
						<td><?php JHTML::_('jresearchfrontend.icon', 'edit', 'publications', $this->items[$i]->id); ?></td>
					<?php endif; ?>
					<?php if($exportColumn): ?>
						<td style="text-align:center;">
						<?php 
							$exportLinks = array();
							if($this->showBibtex):
								$exportLinks[] = JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportSingle&format=bibtex&id='.$this->items[$i]->id), 'Bibtex');	
							endif;						
							
							if($this->showRis):
								$exportLinks[] = JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportSingle&format=mods&id='.$this->items[$i]->id), 'MODS');	
							endif;
								
							if($this->showMods):
								$exportLinks[] = JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportSingle&format=ris&id='.$this->items[$i]->id), 'RIS');
							endif;
							
							if($this->showDigitalVersion && !empty($this->items[$i]->url)):							
								$exportLinks[] = JHTML::_('link', str_replace('&', '&amp;', $this->items[$i]->url), JText::_('JRESEARCH_ONLINE_VERSION'));
							endif;
							
							if($this->showFulltext):
								$attach = $this->items[$i]->getAttachment(0, 'publications');
								if(!empty($attach))
									$exportLinks[] = JHTML::_('link', $attach, JText::_('JRESEARCH_FULLTEXT'));
							endif;

							echo implode(' , ', $exportLinks);
						?>
						</td>			
					<?php endif; ?>
				</tr>
				<?php $outIndex++; ?>
			<?php
			}
		else:
			?>
			<tr><td colspan="<?php echo $nCols; ?>" ></td>
		<?php endif;?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="task" value="filtered" />
	<input type="hidden" name="controller" value="publications"  />
	<input type="hidden" name="limitstart" value="0" />
	<input type="hidden" name="modelkey" value="tabular" />
	<input type="hidden" name="layout" value="tabular" />	
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />	
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
