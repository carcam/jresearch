<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for adding/editing a single publication
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div style="text-align:center;"><h3><?php echo JText::_('JRESEARCH_'.strtoupper($this->pubtype).'_DEFINITION'); ?></h3></div>
<div class="divForm">
<form name="adminForm" id="adminForm" enctype="multipart/form-data" action="./" method="post" class="form-validate" onsubmit="return validate(this);">
<?php if(isset($this->publication)): ?>
	<div class="divChangeType">
		<?php echo $this->changeType; ?>
		<input type="button" onclick="
		if(document.adminForm.change_type.value == '0'){ 
			alert('<?php echo JText::_('JRESEARCH_SELECT_PUBTYPE'); ?>') 
		} 
		if(document.adminForm.change_type.value != '0' && document.adminForm.change_type.value != document.adminForm.pubtype.value && confirm('<?php echo JText::_('JRESEARCH_SURE_CHANGE_PUBTYPE')?>') ){
			msubmitform('changeType');
		}" 
		value="<?php echo JText::_('JRESEARCH_PUBLICATION_CHANGE_TYPE'); ?>" />
		<label for="keepold"><?php echo JText::_('JRESEARCH_KEEP_OLD_PUBLICATION').': '; ?><input type="checkbox" name="keepold" id="keepold" /></label>
	</div>
<?php endif; ?>	
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_BASIC')?></legend>
	<div class="divTable">
	    <div class="divTR">
	        <div class="divTd">
				<label for="title"><?php echo JText::_('Title').': '?></label>
			</div>
			<div class="divTdl">			
				<input name="title" id="title" size="50" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->title:'' ?>" class="required" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'title', JText::_('JRESEARCH_REQUIRE_PUBLICATION_TITLE')); ?>
			</div>
	        <div class="divEspacio" ></div>						
		</div>
		<div class="divTR">
	        <div class="divTd">
				<label for="original_title"><?php echo JText::_('JRESEARCH_ORIGINAL_TITLE').': '?></label>
			</div>
			<div class="divTdl">			
				<input name="original_title" id="original_title" size="50" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->original_title:'' ?>" />
			</div>
	        <div class="divEspacio" ></div>						
		</div>
	
	<div class="divTR">
	        <div class="divTd">
	            <label for="year"><?php echo JText::_('JRESEARCH_YEAR').'/'.JText::_('JRESEARCH_MONTH').'/'.JText::_('JRESEARCH_DAY').': '?></label>
	        </div>
	        <div class="divTdl divTdl2">
				<input maxlength="4" size="5" name="year" id="year" value="<?php echo isset($this->publication)?$this->publication->year:'' ?>" class="validate-year" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'year', JText::_('JRESEARCH_PROVIDE_VALID_YEAR')); ?>/
				<input maxlength="20" size="8" name="month" id="month" value="<?php echo isset($this->publication)?$this->publication->month:''; ?>" />/
				<input maxlength="10" size="5" name="day" id="day" value="<?php echo isset($this->publication)?$this->publication->day:''; ?>" />				
	        </div>		        
			<div class="divTd">
				<label for="country"><?php echo JText::_('JRESEARCH_COUNTRY').': ';?></label>
			</div>
			<div class="divTdl">
				<input maxlength="40" size="15" name="country" id="country" value="<?php echo isset($this->publication)?$this->publication->country:'' ?>" />								
			</div>			
	        <div class="divEspacio" ></div>						
			
	    </div>
		<div class="divTR">
	    	<div class="divTd">
	            <label for="url"><?php echo JText::_('URL').': '?></label>
	        </div>
	        <div class="divTdl">
				<input name="url" id="url" size="50" maxlength="255" class="validate-url" value="<?php echo isset($this->publication)?$this->publication->url:'' ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'url', JText::_('JRESEARCH_PROVIDE_VALID_URL')); ?>				
			</div>
			<div class="divEspacio" ></div>				
		</div>	
	   <div class="divTR"> 		
	        <div class="divTd">
	            <label for="keywords"><?php echo JText::_('JRESEARCH_KEYWORDS').': '?></label>
	        </div>
	        <div class="divTdl">
				<input name="keywords" id="keywords" size="30" maxlength="255" class="validate-keywords" value="<?php echo isset($this->publication)?$this->publication->keywords:'' ?>" /><span class="information"><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS'))?></span>
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'keywords', JText::_('JRESEARCH_PROVIDE_KEYWORDS')); ?>				
	        </div>
		  <div class="divEspacio" ></div>		        
		</div>
		
		<div class="divTR">
        	<div class="divTd">
            	<label for="headings"><?php echo JText::_('JRESEARCH_HEADINGS').': ' ?></label>
        	</div>
       	 	<div class="divTdl">
				<textarea name="headings" id="headings" cols="30" rows="4" ><?php echo isset($this->publication)?$this->publication->headings:'' ?></textarea>
        	</div>	    
	  		<div class="divEspacio" ></div>	        
    	</div>
		<div class="divTR">
			<div class="divTd">
				<label for="id_language"><?php echo JText::_('JRESEARCH_LANGUAGE').': ' ?></label>
			</div>
			<div class="divTdl divTdl2">
				<?php echo $this->languageList; ?>			
			</div>
	  		<div class="divEspacio" ></div>					
		</div>		
		<div class="divTR">
			<div class="divTd">
				<label for="status"><?php echo JText::_('JRESEARCH_PUBLICATION_STATUS').': ';?></label>
			</div>
			<div class="divTdl divTdl2">
				<?php echo $this->statusRadio; ?>				
			</div>
			<div class="divTd">
				<label for="recommended"><?php echo JText::_('JRESEARCH_RECOMMENDED').': ';?></label>
			</div>
			<div class="divTdl">
				<?php echo $this->recommendedRadio; ?>				
			</div>			
	  		<div class="divEspacio" ></div>			
		</div>
    	<div class="divTR">
        	<div class="divTd">
            	<label for="abstract"><?php echo JText::_('JRESEARCH_ABSTRACT').': ' ?></label>
        	</div>
       	 	<div class="divTdl">
				<textarea name="abstract" id="abstract" cols="70" rows="15" ><?php echo isset($this->publication)?$this->publication->abstract:'' ?></textarea>
        	</div>	    
	  		<div class="divEspacio" ></div>	        
    	</div>
    	<div class="divTR">
	        <div class="divTd">
	            <label for="original_abstract"><?php echo JText::_('JRESEARCH_ORIGINAL_ABSTRACT').': ' ?></label>
	        </div>
	        <div class="divTdl">
				<textarea name="original_abstract" id="original_abstract" cols="70" rows="15" ><?php echo isset($this->publication)?$this->publication->original_abstract:'' ?></textarea>
	        </div>	    
		  	<div class="divEspacio" ></div>	        
    	</div>    	
    </div>
</fieldset>	
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_AUTHORS'); ?></legend>
	<div class="divTable">
	    <div class="divTR">
	        <div class="divTd">
	            <label for="authors"><?php echo JText::_('JRESEARCH_AUTHORS').': '?></label>	            
	        </div>
			<?php echo $this->authors; ?>		
	        <div class="divEspacio"></div>
	    </div>
	</div>					
</fieldset>
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_SPECIFIC'); ?></legend>	
	<div class="divTable">
		<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'publication'.DS.'tmpl'.DS.$this->pubtype.'.php'); ?>
	</div>
</fieldset>
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_EXTRA'); ?></legend>
	<div class="divTR">
        <div class="divTd">
			<label for="alias"><?php echo JText::_('Alias').': '?></label>
		</div>
		<div class="divTdl">
			<input name="alias" id="alias" size="40" maxlength="255" class="validate-alias" value="<?php echo isset($this->publication)?$this->publication->alias:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'alias', JText::_('JRESEARCH_PROVIDE_VALID_ALIAS')); ?>
			<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_PUBLICATION_ALIAS_TOOLTIP')); ?>
		</div>
        <div class="divEspacio" ></div>				
	</div>
	<div class="divTR">
		<div class="divTd"><label for="file_url_0"><?php echo JText::_('JRESEARCH_FILE').': '; ?></label></div>
		<div class="divTdl"><?php echo $this->files; ?><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_PUBLICATION_FILES_TOOLTIP')); ?></div>
	  	<div class="divEspacio" ></div>		
	</div>
	<div class="divTR">
		<div class="divTd">
			<label for="internal"><?php echo JText::_('JRESEARCH_INTERNAL').': ' ?></label>
		</div>
		<div class="divTdl divTdl2">
			<?php echo $this->internalRadio; ?><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_PUBLICATION_INTERNAL_TOOLTIP')) ?>				
		</div>
		<div class="divTd">
		<label for="published"><?php echo JText::_('Published').': ' ?></label>
		</div>
		
        <div class="divTdl">
			<?php echo $this->publishedRadio; ?>						
			 <?php echo JHTML::_('tooltip', JText::_('JRESEARCH_PUBLICATION_PUBLISHED_TOOLTIP')); ?>			
        </div>
  		<div class="divEspacio" ></div>					
	</div>
    <div class="divTR">
		<div class="divTd">
        <label for="citekey"><?php echo JText::_('JRESEARCH_CITEKEY').': '?></label> 
        </div> 
        <div class="divTdl divTdl2">
			<input size="15" maxlength="255" name="citekey" id="citekey" value="<?php echo isset($this->publication)?$this->publication->citekey:'' ?>" /><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_CITEKEY_TOOLTIP')); ?>				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'citekey', JText::_('JRESEARCH_PROVIDE_CITEKEY')); ?>								
        </div>
  		<div class="divEspacio" ></div>		        
		</div>
	<div class="divTR">	        
			<div class="divTd"><label for="doi"><?php echo JText::_('JRESEARCH_DOI').': '?></label></div>
			<div class="divTdl divTdl2">
			<input size="15" maxlength="255" name="doi" id="doi" class="validate-doi" value="<?php echo isset($this->publication)?$this->publication->doi:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'doi', JText::_('JRESEARCH_PROVIDE_VALID_DOI')); ?>				
			<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_PUBLICATION_DOI_TOOLTIP')); ?>
			</div>
			<?php if(isset($this->publication)): ?>
				<div class="divTd"><label for="hits"><?php echo JText::_('Hits').': '?></label></div>
				<div class="divTdl"><?php echo JHTML::_('jresearchhtml.hitsControl', 'resethits', $this->publication->hits); ?></div>			
			<?php endif; ?>
	  		<div class="divEspacio" ></div>			
		</div>
	
	<div class="divTR">
		<div class="divTd">
			<label for="cover"><?php echo JText::_('JRESEARCH_COVER').': '?></label>
		</div>
		<div class="divTdl">
			<input name="cover" id="cover" size="30" maxlength="255" class="validate-url" value="<?php echo isset($this->publication)?$this->publication->cover:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'cover', JText::_('JRESEARCH_PROVIDE_VALID_URL')); ?>
			<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_PUBLICATION_COVER_TOOLTIP')); ?>							
		</div>
		<div class="divEspacio" ></div>
	</div>	
	<div class="divTR">
		<div class="divTd">
		<label for="journal_acceptance_rate"><?php echo JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE').': '?></label>
		</div>
		<div class="divTdl divTdl2">
			<input value="<?php echo isset($this->publication)?$this->publication->journal_acceptance_rate:'' ?>" size="10" name="journal_acceptance_rate" id="journal_acceptance_rate" maxlength="5" class="validate-numeric" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'journal_acceptance_rate', JText::_('JRESEARCH_PROVIDE_VALID_NUMBER')); ?>
							
		</div>
		<div class="divTd">
		<label for="impact_factor"><?php echo JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR').': ' ?></label>
		</div>
		<div class="divTdl">	
			<input value="<?php echo isset($this->publication)?$this->publication->impact_factor:'' ?>" size="10" name="impact_factor" id="impact_factor" maxlength="8" class="validate-numeric" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'impact_factor', JText::_('JRESEARCH_PROVIDE_VALID_NUMBER')); ?>				
		</div>
        <div class="divEspacio" ></div>		
	</div>
			
	<div class="divTR">
        <div class="divTd">
            <label for="awards"><?php echo JText::_('JRESEARCH_AWARDS').': '?></label>
        </div>
        <div class="divTdl">
			<textarea cols="50" rows="5" name="awards" id="awards"  ><?php echo isset($this->publication)?$this->publication->awards:''; ?></textarea>
        </div>	    
        <div class="divEspacio" ></div>	        
	    </div>		
    <div class="divTR">
        <div class="divTd">
            <label for="comments"><?php echo JText::_('JRESEARCH_COMMENTS').': '?></label>
        </div>
        <div class="divTdl">
			<textarea cols="50" rows="5" name="comments" id="comments"><?php echo isset($this->publication)?$this->publication->comments:''  ?></textarea>&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_TOOLTIP_COMMENTS')); ?>
        </div>
        <div class="divEspacio" ></div>
    </div>
    <div class="divTR">
        <div class="divTd">
            <label for="note"><?php echo JText::_('JRESEARCH_NOTE').': ' ?></label>
        </div>
        <div class="divTdl">
			<textarea name="note" id="note" cols="50" rows="5" ><?php echo isset($this->publication)?$this->publication->note:'' ?></textarea>&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_NOTE_TOOLTIP')); ?>
        </div>
        <div class="divEspacio" ></div>        	    
    </div>
</fieldset>

<input type="hidden" name="pubtype" id="pubtype" value="<?php echo $this->pubtype; ?>" />
<input type="hidden" name="id" value="<?php echo isset($this->publication)?$this->publication->id:'' ?>" />
<?php if(JRequest::getVar('modelkey')): ?>
	<input type="hidden" name="modelkey" value="<?php echo JRequest::getVar('modelkey'); ?>" />
<?php endif; ?>
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'publications'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>
<?php
	$Itemid = JRequest::getInt('Itemid', null);  
	if(!empty($Itemid)): ?>
		<input type="hidden" id="Itemid" name="Itemid" value="<?php echo $Itemid; ?>" />
<?php endif; ?>
<input type="hidden" name="id_research_area" id="id_research_area" value="0" />
</form>
</div>