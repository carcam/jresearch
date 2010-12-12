<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for showing a single publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
define('ENGLISH_ABSTRACT_ID', 4);
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'language.php');

?>
<style type="text/css">
	div#second_language{
		display:none;
	}
	
	h4{
		font-size: 13px;
	}
	
	h5{
		font-size: 12px;
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
<h1 class="componentheading"><?php echo $this->publication->title; ?></h1>
<table class="frontendsingleitem">
<tbody>
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
			<ul style="list-style-type:none;">
				<?php foreach($authors as $auth): ?>
					<li>
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
		<?php $keywords = trim($this->publication->keywords); ?>
		<?php if(!empty($keywords)): ?>		
		<th scope="row"><?php echo JText::_('JRESEARCH_KEYWORDS').': ' ?></th>		
		<td colspan="3"><?php echo $this->publication->keywords; ?></td>
		<?php endif; ?>
	</tr>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></th>
		<td colspan="3"><?php echo JText::_('JRESEARCH_'.strtoupper($this->publication->osteotype)); ?></td>
	</tr>
	<tr>
		<?php $institute = $this->publication->getInstitute();
		$colspan = 2; 
		 if(!empty($institute)):
		 	$colspan = 0;
		?>					
			<th scope="row"><?php echo JText::_('JRESEARCH_INSTITUTE').': ' ?></th>
			<td colspan="3"><a href="index.php?option=com_jresearch&amp;view=institute&amp;task=show&amp;id=<?php echo $institute->id ?><?php echo $ItemidText; ?>"><?php echo $institute->name; ?></a></td>
		<?php endif; ?>	
	</tr>
	<?php $colspan = 4; ?>
	<?php $month = trim($this->publication->month);  
		  $day = trim($this->publication->day);
	?>
	<?php $year = $this->publication->year; ?>
	<tr>	
	<?php if($year != null && $year != '0000' && !empty($year)): ?>
		<th scope="row"><?php echo JText::_('JRESEARCH_PUBLICATION_DATE').': ' ?></th>
		<td><?php echo $this->publication->year; ?>
		<?php $colspan-= 2; ?>
		<?php if(!empty($month)): ?>
			<?php if(empty($day)): ?>
				<?php echo ' '.JResearchPublicationsHelper::formatMonth($month); ?>
			<?php else: ?>
				<?php echo ' '.JResearchPublicationsHelper::formatMonth($month).', '.$day; ?>		
			<?php endif; ?>
		<?php endif; ?>
		</td>
	<?php endif; ?>
	<th scope="row"><?php echo JText::_('JRESEARCH_ENTRY_DATE').': ' ?></th>	
	<td>
	<?php $createDate = strtotime($this->publication->created);  
		  $colspan -= 2;
		  echo date('Y F, d', $createDate);
	?>
	</td>
	<?php if($colspan > 0): ?>	
		<td colspan="<?php echo $colspan; ?>"></td>	
	<?php endif; ?>	
	</tr>	
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_STATUS').': '; ?></th>
		<td><?php echo JText::_('JRESEARCH_'.strtoupper($this->publication->status)); ?></td>
		<th scope="row"><?php echo JText::_('JRESEARCH_RECOMMENDED').': '; ?></th>
		<td><?php echo $this->publication->recommended? JText::_('JRESEARCH_YES') : JText::_('JRESEARCH_NO'); ?></td>
	</tr>
	<?php $country = $this->publication->getCountry(); 
		  $language = $this->publication->getLanguage();
		  $colspan = 4;
		  if(!empty($country) || !empty($language)):
	?>	
	<tr>
		<?php if(!empty($country)): ?>
			<th scope="row"><?php echo JText::_('JRESEARCH_COUNTRY').': '; ?></th>
			<td><?php echo $country; ?></td>	
		<?php else: 
				$colspan -= 2; 
			  endif; 
			  if(!empty($language)): 
		?>			
			<th scope="row"><?php echo JText::_('JRESEARCH_LANGUAGE').': '; ?></th>
			<td><?php echo $language; $colspan -= 2; ?></td>
			<?php if($colspan > 0): ?><td colspan="2" /><?php endif; ?>
		<?php endif;?>
	</tr>
	<?php endif; ?>
	<?php require_once(JPATH_COMPONENT.DS.'views'.DS.'publication'.DS.'types'.DS.'article.php') ?>
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
	<?php $url = str_replace('&', '&amp;', trim($this->publication->url)); ?>	
	<?php if(!empty($url)): ?> 	
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_DIGITAL_VERSION').': '; ?></th>
		<td colspan="3"><?php echo JHTML::_('link', $url, $url); ?></td>
	</tr>
	<?php endif; ?>
	<tr>
	<td colspan="4">
	<?php if($this->showBibtex): 
		echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=bibtex&amp;id='.$this->publication->id, '[Bibtex]').'</span>';		
	 endif;?>	
	<?php if($this->showRIS): 
		echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=ris&amp;id='.$this->publication->id, '[RIS]').'</span>';		
	 endif;?>
	 <?php if($this->showMODS): 
		echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=mods&amp;id='.$this->publication->id, '[MODS]').'</span>';		
	 endif;?>				
	 <?php $n = $this->publication->countAttachments();
      		if($n == 1):
            	$attach = $this->publication->getAttachment(0, 'publications');
		    	echo !empty($attach)? '<span>'.JHTML::_('JResearchhtml.attachment', $attach).'</span>' : '';
            endif;
      ?>	 	
	</td>
	</tr>		
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
	<tr>
		<td style="padding:0px;" colspan="4">
		
			<?php $abstracts = $this->publication->getAbstracts();
			  if(!empty($abstracts)): ?>
				<h4><?php echo JText::_('JRESEARCH_ABSTRACT'); ?></h4>
			<?php endif; ?>
			<?php if(count($this->abstracts) > 1): ?>
				<?php $secondLang = JResearchLanguageHelper::getLanguage('id', $this->publication->id_language); ?>			
				<?php if(isset($this->abstracts[ENGLISH_ABSTRACT_ID])): ?>
					<div><h5><?php echo JText::_('JRESEARCH_ENGLISH_VERSION'); ?></h5></div>
					<div  style="text-align:justify;"><?php echo $this->abstracts[ENGLISH_ABSTRACT_ID]; //English ?></div>
				<?php endif; ?>				
				<div style="height:20px;"></div>
				<div><a id="ashow" href="javascript:showOriginalAbstract();"><?php echo JText::sprintf('JRESEARCH_SHOW_ORIGINAL_ABSTRACT', $secondLang['name']); ?></a></div>				
				<div id="second_language">
					<a id="ahide" href="javascript:hideOriginalAbstract();"><?php echo JText::_('JRESEARCH_HIDE_ORIGINAL_ABSTRACT'); ?></a>
					<h5><?php echo JText::sprintf('JRESEARCH_LANGUAGE_VERSION', $secondLang['name'] ); ?></h5>
					<?php $original_title = $this->publication->original_title; ?>
					<?php if(!empty($this->publication->original_title)): ?>		
						<span class="original_title_lbl"><?php echo JText::_('JRESEARCH_TITLE').': ' ?></span>
						<span class="original_title"><?php echo $this->publication->original_title; ?></span>
					<?php endif; ?>				
					<h5><?php echo JText::_('JRESEARCH_ABSTRACT'); ?></h5>
					<div>
						<div style="text-align:justify;"><?php echo $this->abstracts[$this->publication->id_language]; ?></div>
						<div style="height:20px;"></div>
					</div>					
				</div>
			<?php else: ?>
				<div style="text-align:justify;">
					<?php if(isset($this->abstracts[ENGLISH_ABSTRACT_ID])): ?>
						<?php echo $this->abstracts[ENGLISH_ABSTRACT_ID]; ?>					
					<?php elseif(isset($this->abstracts[$this->publication->id_language])): ?>
						<?php echo $this->abstracts[$this->publication->id_language]; ?>
					<?php endif; ?>
				</div>
				<?php $original_title = $this->publication->original_title; ?>
				<?php if(!empty($this->publication->original_title)): ?>
					<div><a id="ashow" href="javascript:showOriginalAbstract();"><?php echo JText::sprintf('JRESEARCH_SHOW_ORIGINAL_ABSTRACT', $secondLang['name']); ?></a></div>
					<div id="second_language">					
						<div><a id="ahide" href="javascript:hideOriginalAbstract();"><?php echo JText::_('JRESEARCH_HIDE_ORIGINAL_ABSTRACT'); ?></a></div>								
						<h5><?php echo JText::sprintf('JRESEARCH_LANGUAGE_VERSION', $secondLang['name'] ); ?></h5>					
						<span class="original_title_lbl"><?php echo JText::_('JRESEARCH_ORIGINAL_TITLE').': ' ?></span>
						<span class="original_title"><?php echo $this->publication->original_title; ?></span>
					</div>
				<?php endif; ?>										
			<?php endif; ?>				
		<div style="height:20px;"></div>
		</td>	
	</tr>		
	<?php $comments = trim($this->publication->comments); ?>	
	<?php if(!empty($comments)): ?>
	<tr>
		<th scope="row"><?php echo JText::_('JRESEARCH_COMMENTS').': '; ?></th>
		<td style="width:85%;" colspan="3"><div style="text-align:justify;"><?php echo $comments; ?></div></td>	
	</tr>
	<?php endif; ?>	

</tbody>
</table>
<form name="adminForm" id="adminForm" enctype="multipart/form-data" action="./" method="post" class="form-validate" >			
<input name="title" id="title" type="hidden" value="<?php echo isset($this->publication)?$this->publication->title:'' ?>" />
<input name="original_title" id="original_title" type="hidden" value="<?php echo isset($this->publication)?$this->publication->original_title:'' ?>" />
<?php 
	$maxAuthors = JRequest::getInt('nauthorsfield');
	$k = 0;
	for($j = 0; $j <= $maxAuthors; $j++){
		$value = JRequest::getVar("authorsfield".$j);
		if(!empty($value)){
			if(is_numeric($value)){
?>				
<input type="hidden" id="authorsfield<?php echo $j; ?>" name="authorsfield<?php echo $j; ?>" value="<?php echo $value; ?>"  />
<?php 				
			}else{
				$email = JRequest::getVar('emailauthorsfield'.$j);
?>
<input type="hidden" id="authorsfield<?php echo $j; ?>" name="authorsfield<?php echo $j; ?>" value="<?php echo $value; ?>"  />
<input type="hidden" id="emailauthorsfield<?php echo $j; ?>" name="emailauthorsfield<?php echo $j; ?>" value="<?php echo $email; ?>"  />

<?php		}
			
			$k++;
		}			
	}
?>
<input name="year" id="year" type="hidden" value="<?php echo isset($this->publication)?$this->publication->year:'' ?>"  />
<input name="month" id="month" type="hidden" value="<?php echo isset($this->publication)?$this->publication->month:''; ?>" />
<input name="source" id="source" type="hidden" value="<?php echo isset($this->publication)?$this->publication->source:''; ?>" />
<input name="abstract" id="abstract" type="hidden" value="<?php echo isset($this->publication)?$this->publication->abstract:''; ?>" />
<input name="original_abstract" id="original_abstract" type="hidden" value="<?php echo isset($this->publication)?$this->publication->original_abstract:''; ?>" />
<input name="headings" id="headings" type="hidden" value="<?php echo isset($this->publication)?$this->publication->headings:''; ?>" />
<input name="keywords" id="keywords" type="hidden" value="<?php echo isset($this->publication)?$this->publication->keywords:'' ?>" />
<input name="osteotype" id="osteotype" type="hidden" value="<?php echo isset($this->publication)?$this->publication->osteotype:'' ?>" />
<input name="id_institute" id="id_institute" type="hidden" value="<?php echo isset($this->publication)?$this->publication->id_institute:'' ?>" />
<input value="<?php echo isset($this->publication)?$this->publication->npages:'' ?>" size="4" name="npages" id="npages" type="hidden" />
<input value="<?php echo isset($this->publication)?$this->publication->nimages:'' ?>" size="4" name="nimages" id="nimages" type="hidden" />
<input value="<?php echo isset($this->publication)?$this->publication->files:'' ?>" size="4" name="files" id="files" type="hidden" />
<input name="url" id="url" type="hidden" value="<?php echo isset($this->publication)?$this->publication->url:'' ?>" />
<input name="id_language" id="id_language" type="hidden" value="<?php echo isset($this->publication)?$this->publication->id_language:'' ?>" />
<input name="id_country" id="id_country" type="hidden" value="<?php echo isset($this->publication)?$this->publication->id_country:'' ?>" />
<?php if(isset($this->publication)):
		$recomm = $this->publication->recommended ? 'on' : 'off';
	  endif;	
?>
<input name="nauthorsfield" id="nauthorsfield" type="hidden" value="<?php echo JRequest::getVar('nauthorsfield'); ?>" />
<input name="recommended" id="recommended" type="hidden" value="<?php echo isset($this->publication)?$recomm:'' ?>" />
<input name="status" id="status" type="hidden" value="<?php echo isset($this->publication)?$this->publication->status:'' ?>" />
<input type="hidden" name="internal" id="internal" value="<?php echo isset($this->publication)? $this->publication->internal : 1; ?>" />
<input type="hidden" name="published" id="published" value="<?php echo isset($this->publication)? $this->publication->published : 1; ?>" />	
<input type="hidden" name="citekey" id="citekey" value="<?php echo isset($this->publication)?$this->publication->citekey:'' ?>" />								
<input type="hidden" name="pubtype" id="pubtype" value="<?php echo isset($this->publication)? JResearchPublication::osteoToJReseachType($this->publication->osteotype) : ''; ?>" />
<input type="hidden" name="id" value="<?php echo isset($this->publication)?$this->publication->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'publications'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>
<?php
	$Itemid = JRequest::getInt('Itemid', null);  
	if(!empty($Itemid)): ?>
		<input type="hidden" id="Itemid" name="Itemid" value="<?php echo $Itemid; ?>" />
<?php endif; ?>
<input type="hidden" name="id_research_area" id="id_research_area" value="0" />
<input type="hidden" name="modelkey" id="modelkey" value="preview" />
</form>
<div class="buttonsfooter">
	<div>
	<button type="button" onclick="javascript:msubmitform('save');"><?php echo JText::_('Save'); ?></button>
	<button type="button" onclick="javascript:msubmitform('backToEdit')"><?php echo JText::_('JRESEARCH_BACK_EDIT') ?></button>
	</div>
</div>