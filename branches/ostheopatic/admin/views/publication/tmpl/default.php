<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for adding/editing a single publication
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<style type="text/css">
	.requiredspan{
		margin-bottom: 10px;
		color: red;
		font-weight: bold;
	}
</style>
<?php if(isset($this->publication)): ?>
<div style="text-align:center;"><h3><?php echo JText::_('JRESEARCH_'.strtoupper($this->publication->osteotype)); ?></h3></div>
<?php endif; ?>

<div class="divForm">
<div class="requiredspan">* = <?php echo JText::_('Required'); ?></div>
<form name="adminForm" id="adminForm" enctype="multipart/form-data" action="./" method="post" class="form-validate" onsubmit="return validate(this);">
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_BASIC')?></legend>
	<div class="divTable">
	    <div class="divTR">
	        <div class="divTd">
				<label for="title"><?php echo JText::_('Title').'* : '?></label>
			</div>
			<div class="divTdl">			
				<input name="title" id="title" size="60" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->title:'' ?>" class="required" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'title', JText::_('JRESEARCH_REQUIRE_PUBLICATION_TITLE')); ?>
			</div>
	        <div class="divEspacio" ></div>						
		</div>		
		<div class="divTR">
	        <div class="divTd">
				<label for="original_title"><?php echo JText::_('JRESEARCH_ORIGINAL_TITLE').': '?></label>
			</div>
			<div class="divTdl">			
				<input name="original_title" id="original_title" size="60" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->original_title:'' ?>" />
			</div>
	        <div class="divEspacio" ></div>						
		</div>
		<div class="divTR">
	        <div class="divTd">
	            <label for="authors"><?php echo JText::_('JRESEARCH_AUTHORS').': '?></label>	            
	        </div>
			<?php echo $this->authors; ?><span class="information"><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_FORMAT_FOR_AUTHORS'))?></span>		
	        <div class="divEspacio"></div>
	    </div>	
		<div class="divTR">
	        <div class="divTd">
	            <label for="year"><?php echo JText::_('JRESEARCH_PUBLICATION_DATE').': '; ?></label>
	        </div>
	        <div class="divTdl divTdl2">
				<input maxlength="4" size="4" name="year" id="year" value="<?php echo isset($this->publication)?$this->publication->year:'' ?>" class="validate-year" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'year', JText::_('JRESEARCH_PROVIDE_VALID_YEAR')); ?>/
				<input maxlength="20" size="2" name="month" id="month" value="<?php echo isset($this->publication)?$this->publication->month:''; ?>" class="validate-month" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'year', JText::_('JRESEARCH_PROVIDE_VALID_MONTH')); ?>			
	        </div>		        
			<div class="divTd">
				<label for="country"><?php echo JText::_('JRESEARCH_SOURCE').': ';?></label>
			</div>
			<div class="divTdl">
				<?php echo $this->sourcesList; ?>								
			</div>			
			<div class="divEspacio" ></div>	
		</div>
		   	<div class="divTR">
        	<div class="divTd">
            	<label for="abstract"><?php echo JText::_('JRESEARCH_ABSTRACT').': ' ?></label>
        	</div>
       	 	<div class="divTdl">
       	 		<?php $editor = JFactory::getEditor(); ?>
				<?php echo $editor->display( 'abstract',  isset($this->publication)?$this->publication->abstract:'' , '100%', '250', '75', '20' ) ; ?>
        	</div>	    
	  		<div class="divEspacio" ></div>	        
    	</div>
    	<div class="divTR">
	        <div class="divTd">
	            <label for="original_abstract"><?php echo JText::_('JRESEARCH_ORIGINAL_ABSTRACT').': ' ?></label>
	        </div>
	        <div class="divTdl">
				<?php echo $editor->display( 'original_abstract',  isset($this->publication)?$this->publication->original_abstract:'' , '100%', '250', '75', '20' ) ; ?>
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
	            <label for="keywords"><?php echo JText::_('JRESEARCH_KEYWORDS').': '?></label>
	        </div>
	        <div class="divTdl divTdl2">
				<input name="keywords" id="keywords" size="30" maxlength="255" class="validate-keywords" value="<?php echo isset($this->publication)?$this->publication->keywords:'' ?>" /><span class="information"><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS'))?></span>
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'keywords', JText::_('JRESEARCH_PROVIDE_KEYWORDS')); ?>				
	        </div>
	        <div class="divTd">
		        <label for="osteotype"><?php echo JText::_('JRESEARCH_PUBLICATION_TYPE').': '; ?></label>
	        </div>
	        <div class="divTdl">
		        <?php echo $this->osteotypeList; ?>
	        </div>	        
		  <div class="divEspacio" ></div>		        
		</div> 
        <div class="divTR"> 		
	        <div class="divTd">
	            <label for="id_institute"><?php echo JText::_('JRESEARCH_INSTITUTE').': '?></label>
	        </div>
	        <div class="divTdl divTdl2">
	        <?php echo $this->institutesList; ?>
	        </div>
		  <div class="divEspacio" ></div>		        
		</div> 		
	</div>
</fieldset>
<fieldset>
	<div class="divTable">
		<div class="divTR">	
			<div class="divTd">
				<label for="npages"><?php echo JText::_('JRESEARCH_NPAGES').': ';?></label>
			</div>
			<div class="divTdl divTdl2">
				<input value="<?php echo isset($this->publication)?$this->publication->npages:'' ?>" size="4" name="npages" id="npages" maxlength="5" class="validate-numeric" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'npages', JText::_('JRESEARCH_PROVIDE_VALID_NUMBER')); ?>				
			</div>			
			<div class="divTd">
				<label for="nimages"><?php echo JText::_('JRESEARCH_NIMAGES').': ';?></label>
			</div>
			<div class="divTdl">
				<input value="<?php echo isset($this->publication)?$this->publication->nimages:'' ?>" size="4" name="nimages" id="nimages" maxlength="5" class="validate-numeric" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'nimages', JText::_('JRESEARCH_PROVIDE_VALID_NUMBER')); ?>												
			</div>							
			<div class="divEspacio" ></div>					
	    </div>
		<div class="divTR">
	    	<div class="divTd">
	            <label for="url"><?php echo JText::_('JRESEARCH_AVAILABLE_AT').': '?></label>
	        </div>
	        <div class="divTdl">
				<input name="url" id="url" size="50" maxlength="255" class="validate-url" value="<?php echo isset($this->publication)?$this->publication->url:'' ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'url', JText::_('JRESEARCH_PROVIDE_VALID_URL')); ?>				
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
			<div class="divTd">
				<label for="country"><?php echo JText::_('JRESEARCH_COUNTRY').': ';?></label>
			</div>
			<div class="divTdl divTdl2">
				<?php echo $this->countriesList; ?>								
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
     </div>
</fieldset>
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_ARTICLE'); ?></legend>	
	<div class="divTable">
		<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'publication'.DS.'tmpl'.DS.'article.php'); ?>
	</div>
</fieldset>
<fieldset>	
	<legend><?php echo JText::_('JRESEARCH_BOOK'); ?></legend>	
	<div class="divTable">
		<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'publication'.DS.'tmpl'.DS.'book.php'); ?>
	</div>	
</fieldset>
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_EXTRA'); ?></legend>
	<div class="divTable">
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
		<div class="divTd"><label for="file_url_0"><?php echo JText::_('PDF').': '; ?></label></div>
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
    </div>
</fieldset>
<input type="hidden" name="pubtype" id="pubtype" value="<?php echo isset($this->publication)? JResearchPublication::osteoToJReseachType($this->publication->osteotype) : ''; ?>" />
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