<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for adding/editing a single publication
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$folder = 'components'.DS.'com_jresearch'.DS.'files'.DS.'publications';
$folder_url = 'administrator'.DS.'components'.DS.'com_jresearch'.DS.'files'.DS.'publications'.DS;
if(!eregi("administrator",$_SERVER['SCRIPT_FILENAME'])){
	$folder = $folder_url;
	$completar = 'administrator'.DS.'components'.DS.'com_jresearch'.DS;	
	}
else
	$completar = 'components'.DS.'com_jresearch'.DS;
	
$nombre = date("Ymdgi").".pdf";

function comprobar($str,$folder,$n=1) {
	if(file_exists($folder.$str)) 
		comprobar($n."_".$str,$folder,$n++);
	else
		return $str;
}	
$nombre = comprobar($nombre,$folder);
?>


<link href="<?php echo $completar.'css'.DS.'default.css'; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo $completar.'css'.DS.'uploadify.css'; ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $completar.'scripts'.DS.'jquery-1.3.2.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $completar.'scripts'.DS.'swfobject.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $completar.'scripts'.DS.'jquery.uploadify.v2.1.0.min.js'; ?>"></script>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function() {
	jQuery("#uploadify").uploadify({
		'uploader'       : '<?php echo $completar."scripts".DS."uploadify.swf"; ?>',
		'script'         : '<?php echo $completar."scripts".DS."uploadify.php"; ?>',
		'cancelImg'      : '<?php echo $completar."scripts".DS."cancel.png"; ?>',
		'folder'         : '<?php echo $folder;?>',
		'queueSizeLimit' : '1',
		'auto'           : true,
		'fileDesc'       : 'File',
		'fileExt'        : '*.pdf;',
		'buttonText'     : '<?php echo JText::_('JRESEARCH_FILE') ?>',
		'onComplete': function(a, b, c, d, e){ 
			jQuery("#resultado").html('File <a href=\"<?php echo $folder; ?><?php echo $nombre ; ?>" target=\"_blank\"><?php echo $nombre ; ?></a> succesfuly uploaded');
			jQuery("#urlPDF").html('<input type=\"hidden\" name=\"url\" id=\"url\"  value=\"<?php echo $folder_url; ?><?php echo $nombre ; ?>\" />');

		;}
	});
});


</script>

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
				<label for="alias"><?php echo JText::_('Alias').': '?></label>
			</div>
			<div class="divTdl">
				<input name="alias" id="alias" size="40" maxlength="255" class="required validate-alias" value="<?php echo isset($this->publication)?$this->publication->alias:'' ?>" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'alias', JText::_('JRESEARCH_PROVIDE_VALID_ALIAS')); ?>
			</div>
	        <div class="divEspacio" ></div>				
		</div>	
	<div class="divTR">
	        <div class="divTd">
	            <label for="year"><?php echo JText::_('JRESEARCH_YEAR').': '?></label>
	        </div>
	        <div class="divTdl divTdl2">
				<input maxlength="4" size="5" name="year" id="year" value="<?php echo isset($this->publication)?$this->publication->year:'' ?>" class="validate-year" />
				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'year', JText::_('JRESEARCH_PROVIDE_VALID_YEAR')); ?>
	        </div>
    		<div class="divTd">
	            <label for="id_research_area"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '?></label>
	        </div>
	        <div class="divTdl">
				<?php echo $this->areasList; ?>
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
	        <label for="citekey"><?php echo JText::_('JRESEARCH_CITEKEY').': '?></label> 
	        </div> 
	        <div class="divTdl divTdl2">
				<input size="15" maxlength="255" name="citekey" id="citekey" class="required" value="<?php echo isset($this->publication)?$this->publication->citekey:'' ?>" /><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_CITEKEY_TOOLTIP')); ?>				<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'citekey', JText::_('JRESEARCH_PROVIDE_CITEKEY')); ?>								
	        </div>
	  		<div class="divEspacio" ></div>		        
		</div>
		<div class="divTR">	        
			<div class="divTd"><label for="doi"><?php echo JText::_('JRESEARCH_DOI').': '?></label></div>
			<div class="divTdl divTdl2">
			<input size="15" maxlength="255" name="doi" id="doi" class="validate-doi" value="<?php echo isset($this->publication)?$this->publication->doi:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'doi', JText::_('JRESEARCH_PROVIDE_VALID_DOI')); ?>				
			</div>
			<?php if(isset($this->publication)): ?>
				<div class="divTd"><label for="hits"><?php echo JText::_('Hits').': '?></label></div>
				<div class="divTdl"><?php echo JHTML::_('jresearchhtml.hitsControl', 'resethits', $this->publication->hits); ?></div>			
			<?php endif; ?>
	  		<div class="divEspacio" ></div>			
		</div>
		<div class="divTR">
			<div class="divTd">
				<label for="internal"><?php echo JText::_('JRESEARCH_INTERNAL').': ' ?></label>
			</div>
			<div class="divTdl divTdl2">
				<?php echo $this->internalRadio; ?><?php echo JHTML::_('tooltip', JText::_('JRESEARCH_INTERNAL_TOOLTIP')) ?>				
			</div>
			<div class="divTd">
			<label for="published"><?php echo JText::_('Published').': ' ?></label>
			</div>
			
	        <div class="divTdl">
				<?php echo $this->publishedRadio; ?>						
	        </div>
	  		<div class="divEspacio" ></div>					
		</div>
    	<div class="divTR">
        <div class="divTd">
            <label for="note"><?php echo JText::_('JRESEARCH_ABSTRACT').': ' ?></label>
        </div>
        <div class="divTdl">
			<textarea name="abstract" id="abstract" cols="50" rows="4" ><?php echo isset($this->publication)?$this->publication->abstract:'' ?></textarea>
        </div>	    
	  	<div class="divEspacio" ></div>	        
    </div>
	    <div class="divTR">
			<div class="divTd"><?php echo JText::_('JRESEARCH_DIGITAL_VERSION').' (Url) ' ?></div>
			<div class="divTdl">
				<div id="resultado"></div>
				<div id="urlPDF">
				<input name="url" id="url" size="20" maxlength="255" value="<?php echo $this->publication?$this->publication->url:'' ?>" />
				</div>
				<br />
				<input type="file" name="uploadify" id="uploadify" />
				<?php if(!is_writable($folder)) echo JText::_('JRESEARCH_DIRECTORY')." $folder ".JText::_('JRESEARCH_NOT_WRITABLE'); ?>			
				<br />
				<label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>
			</div>
		</div>
	  	<div class="divEspacio" ></div>		
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
			<label for="cover"><?php echo JText::_('JRESEARCH_COVER').': '?></label>
		</div>
		<div class="divTdl">
			<input name="cover" id="cover" size="30" maxlength="255" class="validate-url" value="<?php echo isset($this->publication)?$this->publication->cover:'' ?>" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'cover', JText::_('JRESEARCH_PROVIDE_VALID_URL')); ?>				
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
</form>
</div>