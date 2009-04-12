<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for showing a single publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div style="float: right;"><?php JHTML::_('Jresearch.icon','edit','publications', $this->publication->id); ?></div>
<div class="componentheading"><?php echo $this->publication->title; ?></div>
<table cellspacing="2" cellpadding="2">
<tbody>
	<tr>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>
		<td style="width:35%;"><?php echo $this->area->name; ?></td>
		<?php $year = $this->publication->year; ?>
		<?php if($year != null && $year != '0000' && !empty($year)): ?>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_YEAR').': ' ?></td>
		<td style="width:35%;"><?php echo $this->publication->year; ?></td>
		<?php else: ?>
		<td colspan="2">&nbsp;</td>
		<?php endif; ?>
	</tr>
	<tr>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></td>
		<td style="width:35%;"><?php echo JResearchText::_($this->publication->pubtype); ?></td>
		<?php $keywords = trim($this->publication->keywords); ?>
		<?php if(!empty($keywords)): ?>		
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_KEYWORDS').': ' ?></td>		
		<td style="width:35%;"><?php echo $this->publication->keywords; ?></td>
		<?php else: ?>
		<td colspan="2">&nbsp;</td>
		<?php endif; ?>
	</tr>
	<?php $authors = $this->publication->getAuthors(); ?>
	<?php if(!empty($authors)): ?>
	<tr>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_AUTHORS').': ' ?></td>
		
		<?php if($this->staff_list_arrangement == 'horizontal'): ?>
		<td style="width:85%;" colspan="3">
				<?php $n = count($authors); 
					  $i = 0; ?>
				<?php foreach($authors as $auth): ?>
						<?php if($auth instanceof JResearchMember): ?>
							<?php if($auth->published): ?>
								<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $auth->id ?>"><?php echo $auth; ?></a><?php echo $i == $n - 1?'':',' ?>
							<?php else: ?>
								<?php echo $auth->__toString(); ?><?php echo $i == $n - 1?'':',' ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo $auth->__toString(); ?><?php echo $i == $n - 1?'':',' ?>
						<?php endif; ?>
						<?php $i++; ?>
				<?php endforeach; ?>
		</td>		
		<?php else: ?>
		<td style="width:35%;">
			<ul style="margin:0px;padding:0px;">
				<?php foreach($authors as $auth): ?>
					<li style="list-style:none;">
						<?php if($auth instanceof JResearchMember): ?>
							<?php if($auth->published): ?>
								<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $auth->id ?>"><?php echo $auth->__toString(); ?></a>
							<?php else: ?>
								<?php echo $auth->__toString(); ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php echo $auth; ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</td>
		<?php endif; ?>
		<td colspan="2">&nbsp;</td>		
	</tr>	
	<?php endif; ?>
	
	<?php require_once(JPATH_COMPONENT.DS.'views'.DS.'publication'.DS.'types'.DS.$this->publication->pubtype.'.php') ?>
	<tr>		
	
	<?php $colspan=4; ?>
	<?php $acceptance = trim($this->publication->journal_acceptance_rate); ?>
	<?php $impact_factor = trim($this->publication->impact_factor); ?>
	<?php if(!empty($acceptance) && ($this->params->get('show_journal_acceptance_rate') == 'yes')): ?>
		<?php $colspan = 2; ?>
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE').': ' ?></td>		
		<td style="width:35%;"><?php echo $acceptance; ?>%</td>
	<?php else: ?>
			<td colspan="<?php echo $colspan; ?>">&nbsp;</td>
	<?php endif; ?>
	<?php if(!empty($impact_factor) && ($this->params->get('show_journal_impact_factor') == 'yes')): ?>
		<?php $colspan -= 2; ?>	
		<td style="width:15%;" class="publicationlabel"><?php echo JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR').': ' ?></td>		
		<td style="width:35%;"><?php echo $impact_factor; ?></td>			
	<?php endif; ?>
	<?php if($colspan > 0): ?>
		<td colspan="<?php echo $colspan; ?>">&nbsp;</td>
	<?php endif; ?>
	</tr>
		
	<?php $awards = trim($this->publication->awards); ?>
	<?php if(!empty($awards) && ($this->params->get('show_awards') == 'yes')): ?>
	<tr>
		<td colspan="4" align="left" class="publicationlabel"><div style="text-align:justify;"><?php echo JText::_('JRESEARCH_AWARDS').': '; ?></div></td>
	</tr>
	<tr>
		<td colspan="4" align="left"><?php echo $awards; ?></td>
	</tr>
	<?php endif; ?>	
	
	<?php $note = trim($this->publication->note); ?>	
	<?php if(!empty($note)): ?>
	<tr>
		<td colspan="4" align="left" class="publicationlabel"><?php echo JText::_('JRESEARCH_NOTE').': '; ?></td>
	</tr>
	<tr>
		<td colspan="4" align="left"><div style="text-align:justify;"><?php echo $note; ?></div></td>	
	</tr>
	<?php endif; ?>	
	
	<?php $abstract = trim($this->publication->abstract); ?>
	<?php if(!empty($abstract)): ?>
	<tr>
		<td colspan="4" align="left" class="publicationlabel"><?php echo JText::_('JRESEARCH_ABSTRACT').': '; ?></td>
	</tr>
	<tr>
		<td colspan="4" align="left" ><div style="text-align:justify"><?php echo $abstract; ?></div></td>
	</tr>
	<?php endif; ?>	
	<?php $comments = trim($this->publication->comments); ?>	
	<?php if(!empty($comments)): ?>
	<tr>
		<td colspan="4" align="left" class="publicationlabel"><?php echo JText::_('JRESEARCH_COMMENTS').': '; ?></td>
	</tr>
	<tr>
		<td colspan="4" align="left"><div style="text-align:justify;"><?php echo $comments; ?></div></td>	
	</tr>
	<?php endif; ?>	
	
	<?php $url = trim($this->publication->url); ?>
	<?php if(!empty($url)): ?> 
		<tr><td colspan="4"><span><?php echo JHTML::_('link', $url, JText::_('JRESEARCH_DIGITAL_VERSION')); ?></span></td></tr>		
	<?php endif; ?>
	
</tbody>
</table>
<div>&nbsp;&nbsp;&nbsp;</div>
<?php if($this->commentsAllowed): ?>
	<?php if($this->showComments): ?>
	<div><span><a id="showComments" href="javascript:showComments(0, <?php echo "'".JText::_('JRESEARCH_SHOW_COMMENTS')."','".JText::_('JRESEARCH_HIDE_COMMENTS')."'"; ?>);"><?php echo JText::_('JRESEARCH_HIDE_COMMENTS') ?></a></span>&nbsp;&nbsp;&nbsp;<span><a id="postComment" href="javascript:postComment();"><?php echo JText::_('JRESEARCH_POST_COMMENT'); ?></a></span></div>
	<?php else: ?>
	<div><span><a id="showComments" href="javascript:showComments(1, <?php echo "'".JText::_('JRESEARCH_SHOW_COMMENTS')."','".JText::_('JRESEARCH_HIDE_COMMENTS')."'"; ?>);"><?php echo JText::_('JRESEARCH_SHOW_COMMENTS') ?></a></span>&nbsp;&nbsp;&nbsp;<span><a id="postComment" href="javascript:postComment();"><?php echo JText::_('JRESEARCH_POST_COMMENT'); ?></a></span></div>
	<?php endif; ?>
	<?php $user =& JFactory::getUser(); ?>
	<div id="commentForm" style="display:none;">
		<form id="form" name="form" action="index.php" method="POST" class="form-validate" onSubmit="return validateCommentForm(this);">
			<div><span style="margin-right:10px;"><?php echo JText::_('JRESEARCH_SUBJECT').': '; ?></span>
			<span><input type="text" size="30" maxlength="255" name="subject" id="subject"  /></span></div>
			<div><span style="margin-right:22px;"><?php echo JText::_('JRESEARCH_FROM').': '; ?></span>
			<span><input type="text" name="author" id="author" size="30" maxlength="255" value="<?php echo (!$user->guest)?$user->name:''; ?>" class="required"  /></span><br />
			<label for="author" class="labelform"><?php echo JText::_('JRESEARCH_FIELD_NOT_EMPTY');  ?></label>
			</div>
			<div><div><?php echo JText::_('JRESEARCH_CONTENT').': ' ?></div>
			<textarea name="content" id="content" rows="5" cols="43" class="required"></textarea><br />
			<label for="content" class="labelform"><?php echo JText::_('JRESEARCH_FIELD_NOT_EMPTY'); ?></label></div>
			<div><img src="<?php echo JURI::base() ?>components/com_jresearch/views/publication/captcha/<?php echo $this->captcha['file']; ?>" /></div>
			<div>
				<?php echo JText::_('JRESEARCH_ENTER_TEXT_IN_IMAGE').': ' ?>
				<input name="<?php echo $this->captcha['id'] ?>" id="<?php echo $this->captcha['id'] ?>" type="text" size="10" class="required" /><br />
				<label for="<?php echo $this->captcha['id'] ?>" class="labelform"><?php echo JText::_('JRESEARCH_FIELD_NOT_EMPTY'); ?></label>
			</div>		
			
			<input type="hidden" name="id_publication" id="id_publication" value="<?php echo $this->publication->id; ?>" />
			<input type="hidden" name="task" id="task" value="saveComment" />
			<input type="hidden" name="option" value="com_jresearch"  />
			<input type="hidden" name="view" value="publication"  />		
			<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />
			<input type="hidden" name="showcomm" id="showcomm" value="1" />	
			<div><input type="submit" name="submit" value="<?php echo JText::_('JRESEARCH_POST_COMMENT'); ?>" /></div>
		</form>
	</div>
	<div id="divcomments" <?php if(!$this->showComments): ?> style="display:none;" <?php endif; ?> >
		<?php if(!empty($this->comments)): ?>
		<ul class="comments">
		<?php $j=0; ?>
		<?php foreach($this->comments as $comment):  ?>
			<li class="comments">
				<div class="subjectComment"><?php echo $comment->subject.' '.JText::_('JRESEARCH_FROM_L').' '.$comment->author.' ('.$comment->datetime.')'; ?></div>
				<div><?php echo $comment->content; ?></div>
			</li>
			<?php $j++; ?>
		<?php endforeach; ?>
		</ul>
		<div style="width:100%;text-align:center;">
			<span>
			<?php if($this->limitstart > 0): ?>	
				<a href="index.php?option=com_jresearch&view=publication&task=show&showcomm=1&id=<?php echo $this->publication->id; ?>&limitstart=<?php echo ($this->limitstart - $this->limit); ?>&limit=<?php echo $this->limit; ?>"><?php echo JText::_('Prev'); ?></a>
			<?php endif; ?>
			</span>
			<span>&nbsp;&nbsp;</span>
			<span>
			<?php if($this->limitstart + $this->limit < $this->total ): ?>
				<a href="index.php?option=com_jresearch&view=publication&task=show&showcomm=1&id=<?php echo $this->publication->id; ?>&limitstart=<?php echo ($this->limitstart + $this->limit); ?>&limit=<?php echo $this->limit; ?>"><?php echo JText::_('Next'); ?></a>
			<?php endif; ?>
			</span>
		</div>	
		<?php endif; ?>
	</div>
<?php endif; ?>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>