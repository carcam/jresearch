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
<h1 class="componentheading"><?php echo $this->thesis->title; ?></h1>
<?php if($this->showHits): ?>
<div class="small"><?php echo JText::_('Hits').': '.$this->publication->hits; ?></div>
<?php endif; ?>
<table cellspacing="2" cellpadding="2">
<tbody>
	<tr>
		<th scope="row" style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></th>
		<td style="width:35%;"><?php echo $this->area->name; ?></td>
    	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<?php $status = $this->statusArray[$this->thesis->status]; ?>
		<th scope="row" style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_STATUS').': ' ?></th>
		<td style="width:35%;"><?php echo $status; ?></td>
		<?php $degree = $this->degreeArray[$this->thesis->degree]; ?>
		<th scope="row" style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_DEGREE').': ' ?></th>
		<td style="width:35%;"><?php echo $degree; ?></td>
	</tr>
	
	<?php $directors = $this->thesis->getDirectors(); ?>
	<?php $students = $this->thesis->getStudents(); ?>
	<?php if(!empty($directors) || !empty($students)): ?>
	<tr>
		<?php if(!empty($directors)): ?>
		<th scope="row" style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_DIRECTORS').': ' ?></th>
		<td style="width:35%;">
			<?php if($this->staff_list_arrangement == 'vertical'): ?>
				<ul style="margin:0px;padding:0px;">
					<?php foreach($directors as $dir): ?>
						<li style="list-style:none;">
							<?php if($dir instanceof JResearchMember): ?>
								<?php if($dir->published): ?>
									<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $dir->id ?>"><?php echo $dir->__toString(); ?></a>
								<?php else: ?>
									<?php echo $dir->__toString(); ?>
								<?php endif; ?>	
							<?php else: ?>
									<?php echo $dir; ?>
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
								<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a><?php echo $i == $n - 1?'':',' ?>
							<?php else: ?>
								<?php echo $auth->__toString(); ?><?php echo $i == $n - 1?'':',' ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo $auth; ?><?php echo $i == $n - 1?'':',' ?>
						<?php endif; ?>
						<?php $i++; ?>
				<?php endforeach; ?>				
			<?php endif; ?>
		</td>
		<?php endif; ?>
		<?php if(!empty($students)): ?>
		<th scope="row" style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_STUDENTS').': ' ?></th>
		<td style="width:35%;">
			<?php if($this->staff_list_arrangement == 'vertical'): ?>	
				<ul style="margin:0px;padding:0px;">
					<?php foreach($students as $stud): ?>
						<li style="list-style:none;">
							<?php if($stud instanceof JResearchMember): ?>
								<?php if($stud->published): ?>
									<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $stud->id ?>"><?php echo $stud->__toString(); ?></a>
								<?php else: ?>
									<?php echo $stud->__toString(); ?>
								<?php endif; ?>	
							<?php else: ?>
									<?php echo $stud; ?>
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
								<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a><?php echo $i == $n - 1?'':',' ?>
							<?php else: ?>
								<?php echo $auth->__toString(); ?><?php echo $i == $n - 1?'':',' ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo $auth; ?><?php echo $i == $n - 1?'':',' ?>
						<?php endif; ?>
						<?php $i++; ?>
				<?php endforeach; ?>				
			<?php endif; ?>
		</td>
		<?php else: ?>
		<td colspan="2">&nbsp;</td>
		<?php endif; ?>
	</tr>	
	<?php endif; ?>
	
	<tr>
		<?php $startDate = trim($this->thesis->start_date); ?>
		<?php $colspan = 4; ?>
		<?php if(!empty($startDate) && $startDate != '0000-00-00'): ?>
		<?php $colspan = 2; ?>
	  	<th scope="row" style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></th>
	  	<td style="width:35%;"><?php echo $this->thesis->start_date; ?></td>
	  	<?php endif; ?>
		<?php $endDate = trim($this->thesis->end_date); ?>
		<?php if(!empty($endDate) && $endDate != '0000-00-00'):  ?>  	
		<th scope="row" style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_DEADLINE').': '; ?></th>
		<td style="width:35%;"><?php echo $this->thesis->end_date; ?></td>  	
		<?php else: ?>
		<td colspan="<?php echo $colspan; ?>">&nbsp;</td>
		<?php endif; ?>	
	</tr>
	<tr><td>&nbsp;</td></tr>
	<?php $doc = trim($this->thesis->url); ?>
	<?php if(!empty($doc)): ?>
		<tr><td colspan="4"><span><?php echo JHTML::_('link', $doc, JText::_('JRESEARCH_DIGITAL_VERSION')); ?></span></td></tr>	
	<?php endif; ?>
	<?php $n = $this->thesis->countAttachments(); ?>
	<?php if($n > 0): ?>
		<tr><td class="publicationlabel"><?php echo JText::_('JRESEARCH_FILES').': ' ?></td><td colspan="3"><ul style="list-style:none;">
		<?php for($i=0; $i<$n; $i++): ?>
			<?php $attach = $this->thesis->getAttachment($i, 'theses'); ?>
			<?php echo !empty($attach)?'<li>'.JHTML::_('JResearch.attachment', $attach).'<li>':''; ?>
		<?php endfor; ?>
		</ul>
		</td></tr>
	<?php endif; ?>			
	<?php $description = trim($this->thesis->description); ?>
	<?php if(!empty($description)): ?>
	<tr>
		<th scope="col" colspan="4" align="left" class="publicationlabel"><?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?></th>
	</tr>
	<tr>
		<td colspan="4" align="left" ><div style="text-align:justify"><?php echo str_replace('<hr id="system-readmore" />', '', $description); ?></div></td>
	</tr>
	<?php endif; ?>	
</tbody>
</table>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>