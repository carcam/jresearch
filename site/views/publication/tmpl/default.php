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
	  
	//BibTex show in frontend; Pablo Moncada
	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'exporters'.DS.'factory.php');		
	$document = JFactory::getDocument(); 
	$id = JRequest::getInt('id');
	$format = "bibtex";		
	$model = &$this->getModel('Publication', 'JResearchModel');		
	$publication = $model->getItem($id);		
	$exporter =& JResearchPublicationExporterFactory::getInstance($format);		
	$output2 = $exporter->parse($publication);				
	//End Pablo Moncada
	  	
?>
<div style="float: right;"><?php echo JHTML::_('Jresearch.icon','edit','publications', $this->publication->id); ?></div>
<h2 class="componentheading"><?php echo $this->publication->title; ?></h2>
<table class="frontendsingleitem">
<tbody>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></th>
		<td><?php if($this->area->id > 1): ?>
			<?php echo JHTML::_('jresearch.link', $this->area->name, 'researcharea', 'show', $this->area->id)?>
		<?php else: ?>
			<?php echo $this->area->name; ?>	
		<?php endif; ?>	
		</td>
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
								<?php echo JHTML::_('jresearch.link', JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format), 'member', 'show', $auth->id)?><?php echo $i == $n - 1?'':';' ?>
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
								<?php echo JHTML::_('jresearch.link', JResearchPublicationsHelper::formatAuthor($auth->__toString(), $this->format), 'member', 'show', $auth->id)?>
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
	<?php if(!empty($acceptance)): ?>
		<?php $colspan = 2; ?>
		<th scope="row"><?php echo JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE').': ' ?></th>		
		<td><?php echo $acceptance; ?>%</td>
	<?php else: ?>
			<td colspan="<?php echo $colspan; ?>"></td>
	<?php endif; ?>
	<?php if(!empty($impact_factor)): ?>
		<?php $colspan -= 2; ?>	
		<th scope="row"><?php echo JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR').': ' ?></th>		
		<td><?php echo $impact_factor; ?></td>			
	<?php endif; ?>
	<?php if($colspan > 0): ?>
		<td colspan="<?php echo $colspan; ?>"></td>
	<?php endif; ?>
	</tr>
		
	<?php $awards = trim($this->publication->awards); ?>
	<?php if(!empty($awards)): ?>
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
	    echo !empty($attach)?'<div><strong>'.JText::_('JRESEARCH_FULLTEXT').':</strong> '.JHTML::_('JResearchhtml.attachment', $attach).'</div>':'';
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