<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for showing a single publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'language.php');

?>
<style type="text/css">
	div#second_language{
		display:none;
	}
</style>
<script type="text/javascript">
	function showOriginalAbstract(){
		secondLanguage = document.getElementById('second_language');
		secondLanguage.style.display = 'block';
		aShow = document.getElementById('ashow');
		aShow.style.display = 'none';
	}

	function hideOriginalAbstract(){
		secondLanguage = document.getElementById('second_language');
		secondLanguage.style.display = 'none';
		aShow = document.getElementById('ashow');
		aShow.style.display = 'block';		
	}
</script>
<?php $Itemid = JRequest::getVar('Itemid'); 
	  $ItemidText = !empty($Itemid)?'&amp;Itemid='.$Itemid:'';
	  	
?>
<div style="float: right;"><?php echo JHTML::_('Jresearch.icon','edit','publications', $this->publication->id); ?></div>
<h1 class="componentheading"><?php echo $this->publication->title; ?></h1>
<table class="frontendsingleitem">
<tbody>
	<?php $original_title = $this->publication->original_title; ?>
	<?php if(!empty($this->publication->original_title)): ?>		
		<tr>		
			<th scope="row"><?php echo JText::_('JRESEARCH_ORIGINAL_TITLE').': ' ?></th>
			<td colspan="3"><?php echo $this->publication->original_title; ?></td>
		</tr>	
	<?php endif; ?>
	<?php $colspan = 4; ?>
	<?php $month = trim($this->publication->month);  
		  $day = trim($this->publication->day);
	?>
	<?php $year = $this->publication->year; ?>
	<?php if($year != null && $year != '0000' && !empty($year)): ?>
		<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_YEAR').': ' ?></th>
		<td><?php echo $this->publication->year; ?></td>
		<?php $colspan-= 2; ?>
		<?php if(!empty($month)): ?>
			<?php if(empty($day)): ?>
				<?php $colspan -= 2; ?>
				<th scope="row"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></th>		
				<td><?php echo JResearchPublicationsHelper::formatMonth($month); ?></td>
			<?php else: ?>
				<th scope="row"><?php echo JText::_('JRESEARCH_DATE').': ' ?></th>		
				<td><?php echo JResearchPublicationsHelper::formatMonth($month).', '.$day; ?></td>		
			<?php endif; ?>
		<?php else: ?>
			<td colspan="<?php echo $colspan; ?>"></td>	
		<?php endif; ?>
		</tr>
	<?php endif; ?>
	
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></th>
		<td><?php echo JResearchText::_($this->publication->pubtype); ?></td>
		<?php $keywords = trim($this->publication->keywords); ?>
		<?php if(!empty($keywords)): ?>		
		<th scope="row"><?php echo JText::_('JRESEARCH_KEYWORDS').': ' ?></th>		
		<td><?php echo $this->publication->keywords; ?></td>
		<?php else: ?>
		<td colspan="2"></td>
		<?php endif; ?>
	</tr>
	<?php $authors = $this->publication->getAuthors(true); ?>
	<?php if(!empty($authors)): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_AUTHORS').': ' ?></th>
		
		<?php if($this->staff_list_arrangement == 'horizontal'): ?>
		<td colspan="3">
				<?php $n = count($authors); 
					  $i = 0;  ?>
				<?php foreach($authors as $auth): ?>
						<?php if($auth instanceof JResearchMember): ?>
							<?php if($auth->published): ?>
								<?php echo JHTML::_('jresearch.link', JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format), 'member', 'show', $auth->id)?><?php echo $i == $n - 1?'':';' ?>
							<?php else: ?>
								<?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?><?php echo $i == $n - 1?'':';' ?>
							<?php endif; ?>	
						<?php else: ?>
								<?php if(!empty($auth['author_email'])): ?>
									<a href="mailto:<?php echo $auth['author_email']; ?>"><?php echo JResearchPublicationsHelper::formatAuthor($auth['author_name'], $this->format); ?></a>
								<?php else: ?>
									<?php echo JResearchPublicationsHelper::formatAuthor($auth['author_name'], $this->format); ?>								
								<?php endif; ?>
								<?php echo $i == $n - 1?'':';' ?>
						<?php endif; ?>
						<?php $i++; ?>
				<?php endforeach; ?>
		</td>		
		<?php else: ?>
		<td colspan="3">
			<ul>
				<?php foreach($authors as $auth): ?>
					<li style="list-style:none;">
						<?php if($auth instanceof JResearchMember): ?>
							<?php if($auth->published): ?>
								<?php echo JHTML::_('jresearch.link', JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format), 'member', 'show', $auth->id)?>
							<?php else: ?>
								<?php echo JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format); ?>
							<?php endif; ?>	
						<?php else: ?>						
								<?php echo JResearchPublicationsHelper::formatAuthor($auth['author_name'], $this->format); ?>
								<?php if(!empty($auth['author_email'])): ?>
									(<a href="mailto:<?php echo $auth['author_email']; ?>"><?php echo $auth['author_email']; ?></a>)
								<?php endif; ?>	
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</td>
		<?php endif; ?>
	</tr>	
	<?php endif; ?>	
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_SOURCE').': ' ?></th>
		<td><?php echo JText::_('JRESEARCH_'.strtoupper($this->publication->source)); ?></td>
		<th scope="row"><?php echo JText::_('JRESEARCH_COUNTRY').': '; ?></th>
		<td><?php echo $this->publication->getCountry(); ?></td>
	</tr>
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
	<?php if(!empty($this->publication->npages) || !empty($this->publication->nimages)): ?>
	<tr>
		<?php $colspan = 4; ?>
		<?php if(!empty($this->publication->npages)): ?>
			<th scope="row"><?php echo JText::_('JRESEARCH_NPAGES').': ' ?></th>
			<td><?php echo $this->publication->npages; ?></td>
			<?php $colspan -= 2; ?>
		<?php endif; ?>	
		<?php if(!empty($this->publication->nimages)): ?>
			<th scope="row"><?php echo JText::_('JRESEARCH_NIMAGES').': '; ?></th>
			<td><?php echo $this->publication->nimages; ?></td>
			<?php $colspan -= 2; ?>
		<?php endif; ?>
		<?php if($colspan > 0): ?>
			<td colspan="<?php echo $colspan; ?>" />
		<?php endif; ?>
	</tr>
	<?php endif; ?>
		
	<?php $awards = trim($this->publication->awards); ?>
	<?php if(!empty($awards) && ($this->params->get('show_awards') == 'yes')): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_AWARDS').': '; ?></th>
		<td style="width:85%;" colspan="3"><div style="text-align:justify;"><?php echo $awards; ?></div></td>
	</tr>
	<?php endif; ?>	
	
	<?php $note = trim($this->publication->note); ?>	
	<?php if(!empty($note)): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_NOTE').': '; ?></th>
		<td style="width:85%;" colspan="3"><div style="text-align:justify;"><?php echo $note; ?></div></td>	
	</tr>
	<?php endif; ?>	
	

	<?php $abstracts = $this->publication->getAbstracts(); 
	?>
	<?php if(!empty($abstracts)): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_ABSTRACT').': '; ?></th>	
	</tr>
	<tr>
		<td style="width:100%;" colspan="4">
		<div>
			<?php if(count($this->abstracts) > 1): ?>
				<?php if(isset($this->abstracts[4])): ?>
					<div><strong><i><?php echo JText::_('JRESEARCH_ENGLISH_VERSION'); ?></i></strong></div>
					<div  style="text-align:justify;"><?php echo $this->abstracts[4]; //English ?></div>
				<?php endif; ?>				
				<div style="height:20px;"></div>
				<div id="second_language">
					<?php $secondLang = JResearchLanguageHelper::getLanguage('id', $this->publication->id_language); ?>
					<div><strong><i><?php echo JText::_('JRESEARCH_ORIGINAL_ABSTRACT').' ('.$secondLang['name'].')'; ?></i></strong></div>
					<div  style="text-align:justify;"><?php echo $this->abstracts[$this->publication->id_language]; ?></div>
					<div style="height:20px;"></div>					
					<div><a id="ahide" href="javascript:hideOriginalAbstract();"><?php echo JText::_('JRESEARCH_HIDE_ORIGINAL_ABSTRACT'); ?></a></div>			
				</div>
				<div><a id="ashow" href="javascript:showOriginalAbstract();"><?php echo JText::_('JRESEARCH_SHOW_ORIGINAL_ABSTRACT'); ?></a></div>
			<?php else: ?>
				<div style="text-align:justify;">
					<?php if(isset($this->abstracts[4])): ?>
						<?php echo $this->abstracts[4]; ?>					
					<?php elseif(isset($this->abstracts[$this->publication->id_language])): ?>
						<?php echo $this->abstracts[$this->publication->id_language]; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>	
		</div>
		<div style="height:20px;"></div>		
		</td>	
	</tr>
	<?php endif; ?>	
	
	
	<?php $comments = trim($this->publication->comments); ?>	
	<?php if(!empty($comments)): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_COMMENTS').': '; ?></th>
		<td style="width:85%;" colspan="3"><div style="text-align:justify;"><?php echo $comments; ?></div></td>	
	</tr>
	<?php endif; ?>	
	
	<tr><td colspan="4">
	<?php $url = str_replace('&', '&amp;', trim($this->publication->url)); ?>	
	<?php if(!empty($url)): ?> 
		<span><?php echo JHTML::_('link', $url, JText::_('JRESEARCH_DIGITAL_VERSION')); ?></span>
	<?php endif; ?>
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
				<a href="index.php?option=com_jresearch&amp;view=publication&amp;task=show&amp;showcomm=1&amp;id=<?php echo $this->publication->id; ?>&amp;limitstart=<?php echo ($this->limitstart - $this->limit); ?>&amp;limit=<?php echo $this->limit; ?>"><?php echo JText::_('Prev'); ?></a>
			<?php endif; ?>
			</span>
			<span>&nbsp;&nbsp;</span>
			<span>
			<?php if($this->limitstart + $this->limit < $this->total ): ?>
				<a href="index.php?option=com_jresearch&amp;view=publication&amp;task=show&amp;showcomm=1&amp;id=<?php echo $this->publication->id; ?>&amp;limitstart=<?php echo ($this->limitstart + $this->limit); ?>&amp;limit=<?php echo $this->limit; ?>"><?php echo JText::_('Next'); ?></a>
			<?php endif; ?>
			</span>
		</div>	
		<?php endif; ?>
	</div>
<?php endif; ?>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>