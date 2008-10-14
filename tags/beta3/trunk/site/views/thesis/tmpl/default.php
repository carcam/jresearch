<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo $this->thesis->title; ?></div>
<table cellspacing="2" cellpadding="2">
<tbody>
	<tr>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>
		<td style="width:35%;"><?php echo $this->area->name; ?></td>
    	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<?php $status = $this->statusArray[$this->thesis->status]; ?>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_STATUS').': ' ?></td>
		<td style="width:35%;"><?php echo $status; ?></td>
		<?php $degree = $this->degreeArray[$this->thesis->degree]; ?>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_DEGREE').': ' ?></td>
		<td style="width:35%;"><?php echo $degree; ?></td>
	</tr>
	
	<?php $directors = $this->thesis->getDirectors(); ?>
	<?php $students = $this->thesis->getStudents(); ?>
	<?php if(!empty($directors) || !empty($students)): ?>
	<tr>
		<?php if(!empty($directors)): ?>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_DIRECTORS').': ' ?></td>
		<td style="width:35%;">
			<ul style="margin:0px;padding:0px;">
				<?php foreach($directors as $dir): ?>
					<li style="list-style:none;">
						<?php if($dir instanceof JResearchMember): ?>
							<?php if($dir->published): ?>
								<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $dir->id ?>"><?php echo $dir; ?></a>
							<?php else: ?>
								<?php echo $dir; ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo $dir; ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</td>
		<?php endif; ?>
		<?php if(!empty($students)): ?>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_STUDENTS').': ' ?></td>
		<td style="width:35%;">
			<ul style="margin:0px;padding:0px;">
				<?php foreach($students as $stud): ?>
					<li style="list-style:none;">
						<?php if($stud instanceof JResearchMember): ?>
							<?php if($stud->published): ?>
								<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $stud->id ?>"><?php echo $stud; ?></a>
							<?php else: ?>
								<?php echo $stud; ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo $stud; ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</td>
		<?php else: ?>
		<td colspan="2">&nsbp;</td>
		<?php endif; ?>
	</tr>	
	<?php endif; ?>
	
	<tr>
		<?php $startDate = trim($this->thesis->start_date); ?>
		<?php $colspan = 4; ?>
		<?php if(!empty($startDate) && $startDate != '0000-00-00'): ?>
		<?php $colspan = 2; ?>
	  	<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></td>
	  	<td style="width:35%;"><?php echo $this->thesis->start_date; ?></td>
	  	<?php endif; ?>
		<?php $endDate = trim($this->thesis->end_date); ?>
		<?php if(!empty($endDate) && $endDate != '0000-00-00'):  ?>  	
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_DEADLINE').': '; ?></td>
		<td style="width:35%;"><?php echo $this->thesis->end_date; ?></td>  	
		<?php else: ?>
		<td colspan="<?php echo $colspan; ?>">&nbsp;</td>
		<?php endif; ?>	
	</tr>
	<tr><td>&nbsp;</td></tr>
	<?php $doc = trim($this->thesis->url); ?>
	<? if(!empty($doc)): ?>
		<tr><td colspan="4"><span><?php echo JHTML::_('link', $doc, JText::_('JRESEARCH_DIGITAL_VERSION')); ?></span></td></tr>	
	<?php endif; ?>	
	<?php $description = trim($this->thesis->description); ?>
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