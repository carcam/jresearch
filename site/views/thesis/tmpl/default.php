<?php
/**
 * @package JResearch
 * @subpackage Theses
 * Default view for showing a single theses
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'html'.DS.'jresearch.php');
?>
<?php $Itemid = JRequest::getVar('Itemid'); 
	  $ItemidText = !empty($Itemid)?'&amp;Itemid='.$Itemid:'';
	  	
?>
<h2 class="componentheading"><?php echo $this->thesis->title; ?></h2>
<?php if($this->showHits): ?>
<div class="small"><?php echo JText::_('Hits').': '.$this->publication->hits; ?></div>
<?php endif; ?>
<table class="frontendsingleitem">
<tbody>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></th>
		<td>
		<?php if($this->area->id > 1): ?>
			<a href="index.php?option=com_jresearch&amp;controller=researchAreas&amp;task=show&amp;view=researcharea&amp;id=<?php echo $this->area->id; ?><?php echo $ItemidText ?>"><?php echo $this->area->name; ?></a>
		<?php else: ?>
			<?php echo $this->area->name; ?>	
		<?php endif; ?>
		</td>	
    	<td colspan="2"></td>
	</tr>
	<tr>
		<?php $status = $this->statusArray[$this->thesis->status]; ?>
		<th scope="row"><?php echo JText::_('JRESEARCH_STATUS').': ' ?></th>
		<td><?php echo $status; ?></td>
		<?php $degree = $this->degreeArray[$this->thesis->degree]; ?>
		<th scope="row"><?php echo JText::_('JRESEARCH_DEGREE').': ' ?></th>
		<td><?php echo $degree; ?></td>
	</tr>
	
	<?php $directors = $this->thesis->getDirectors(); ?>
	<?php $students = $this->thesis->getStudents(); ?>
	<?php if(!empty($directors) || !empty($students)): ?>
	<tr>
		<?php if(!empty($directors)): ?>
		<th scope="row"><?php echo JText::_('JRESEARCH_DIRECTORS').': ' ?></th>
		<td>
			<?php if($this->staff_list_arrangement == 'vertical'): ?>
				<ul>
					<?php foreach($directors as $dir): ?>
						<li>
							<?php if($dir instanceof JResearchMember): ?>
								<?php if($dir->published): ?>
									<a href="index.php?option=com_jresearch&amp;view=member&amp;task=show<?php echo $ItemidText ?>&amp;id=<?php echo $dir->id ?>"><?php echo JResearchPublicationsHelper::formatAuthor($dir->__toString(), $this->format); ?></a>
								<?php else: ?>
									<?php echo JResearchPublicationsHelper::formatAuthor($dir->__toString(), $this->format); ?>
								<?php endif; ?>	
							<?php else: ?>
									<?php echo JResearchPublicationsHelper::formatAuthor($dir, $this->format); ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<?php $n = count($directors); 
					  $i = 0; ?>
				<?php foreach($directors as $auth): ?>
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
			<?php endif; ?>
		</td>
		<?php endif; ?>
		<?php if(!empty($students)): ?>
		<th scope="row"><?php echo JText::_('JRESEARCH_STUDENTS').': ' ?></th>
		<td>
			<?php if($this->staff_list_arrangement == 'vertical'): ?>	
				<ul>
					<?php foreach($students as $stud): ?>
						<li>
							<?php if($stud instanceof JResearchMember): ?>
								<?php if($stud->published): ?>
									<a href="index.php?option=com_jresearch&amp;view=member&amp;task=show<?php echo $ItemidText ?>&amp;id=<?php echo $stud->id ?>"><?php echo JResearchPublicationsHelper::formatAuthor($stud->__toString(), $this->format); ?></a>
								<?php else: ?>
									<?php echo JResearchPublicationsHelper::formatAuthor($stud->__toString(), $this->format); ?>
								<?php endif; ?>	
							<?php else: ?>
									<?php echo JResearchPublicationsHelper::formatAuthor($stud, $this->format); ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<?php $n = count($students); 
					  $i = 0; ?>
				<?php foreach($students as $auth): ?>
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
			<?php endif; ?>
		</td>
		<?php else: ?>
		<td colspan="2"></td>
		<?php endif; ?>
	</tr>	
	<?php endif; ?>
	
	<tr>
		<?php $startDate = trim($this->thesis->start_date); ?>
		<?php $colspan = 4; ?>
		<?php if(!empty($startDate) && $startDate != '0000-00-00'): ?>
		<?php $colspan = 2; ?>
	  	<th scope="row"><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></th>
	  	<td><?php echo $this->thesis->start_date; ?></td>
	  	<?php endif; ?>
		<?php $endDate = trim($this->thesis->end_date); ?>
		<?php if(!empty($endDate) && $endDate != '0000-00-00'):  ?>  	
		<th scope="row"><?php echo JText::_('JRESEARCH_DEADLINE').': '; ?></th>
		<td><?php echo $this->thesis->end_date; ?></td>  	
		<?php else: ?>
		<td colspan="<?php echo $colspan; ?>"></td>
		<?php endif; ?>	
	</tr>
	<?php $doc = trim($this->thesis->url); ?>
	<?php if(!empty($doc)): ?>
		<tr><td colspan="4"><span><?php echo JHTML::_('link', $doc, JText::_('JRESEARCH_DIGITAL_VERSION')); ?></span></td></tr>	
	<?php endif; ?>
	<?php $n = $this->thesis->countAttachments(); ?>
	<?php if($n > 0): ?>
		<tr><th scope="row"><?php echo JText::_('JRESEARCH_FILES').': ' ?></th><td colspan="3"><ul style="list-style:none;">
		<?php for($i=0; $i<$n; $i++): ?>
			<?php $attach = $this->thesis->getAttachment($i, 'theses'); ?>
			<?php echo !empty($attach)?'<li>'.JHTML::_('JResearchhtml.attachment', $attach).'<li>':''; ?>
		<?php endfor; ?>
		</ul>
		</td></tr>
	<?php endif; ?>			
	<?php if(!empty($this->description)): ?>
	<tr>
		<th scope="col" colspan="4" align="left" class="publicationlabel"><?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?></th>
	</tr>
	<tr>
		<td colspan="4"><div style="text-align:justify;padding:0px;"><?php echo $this->description; ?></div></td>
	</tr>
	<?php endif; ?>	
</tbody>
</table>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>