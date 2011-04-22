<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<h1 class="componentheading">
	<?php 
	$task = JRequest::getVar('task');
	if($task != 'new' && $task != 'edit')
		$task = 'edit';
	echo JText::_('JRESEARCH_'.JString::strtoupper($task).'_PUBLICATION');?>
</h1>
<div style="float: right;">
	<button type="button" onclick="javascript:Joomla.submitbutton('save')"><?php echo JText::_('JRESEARCH_SAVE_AND_CLOSE') ?></button>
	<button type="button" onclick="javascript:Joomla.submitbutton('apply')"><?php echo JText::_('JRESEARCH_SAVE') ?></button>
	<button type="button" onclick="javascript:Joomla.submitbutton('cancel')"><?php echo JText::_('JRESEARCH_CANCEL') ?></button>	
</div>
<div style="clear: right;"></div>
<div style="text-align:center;"><?php echo JText::_('JRESEARCH_'.JRequest::getVar('pubtype', 'article').'_DEFINITION'); ?></div>
<?php 
$id = JRequest::getVar('id', 0);
if(!empty($id)): ?>
<div class="divChangeType">
</div>
<?php endif; ?>
<div class="frontendform">
<form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
	<fieldset class="panelform">
    	<h2><?php echo JText::_( 'JRESEARCH_BASIC' ); ?></h2>
                <?php foreach($this->form->getFieldset('basic') as $field): ?>
                	<?php if($field->fieldname != 'authors'): ?>
	                    <div class="formelm">
    	                    <?php if (!$field->hidden): ?>
        	                        <?php echo $field->label; ?>
            	            <?php endif; ?>
                	        <?php echo $field->input; ?>
                    	</div>
                    <?php endif; ?>
                <?php endforeach; ?>                
				<div style="clear:left;"></div>
                <?php
            		$fileInputField = $this->form->getField('file_url');
            		$filesField = $this->form->getField('files');
     			?> 
     				<div class="formelm">
                        <?php if (!$fileInputField->hidden): ?>
                                <?php echo $fileInputField->label; ?>
                        <?php endif; ?>
                        <?php echo $fileInputField->input; ?>
                    </div>
     			 <?php 
     			 	$attachment = $filesField->value;
            		if(!empty($attachment)){
            			$deleteAttachField = $this->form->getField('delete_url'); 
            			echo '<div class="formelm">'.$filesField->input.$deleteAttachField->label.' '.$deleteAttachField->input.'</div>';
            		}	
            	?>
            	
            	<?php $auField = $this->form->getField('authors'); ?>
            	<div class="formelm"><?php echo $auField->label; ?></div>
                	<div class="formelm"><?php echo $auField->input; ?></div>
            	
            	
        </fieldset>
        <fieldset class="panelform">
        	<h2><?php echo JText::_('JRESEARCH_SPECIFIC'); ?></h2>
                <?php foreach($this->form->getFieldset('specific') as $field): ?>
                    <div class="formelm">
                        <?php if (!$field->hidden): ?>
                                <?php echo $field->label; ?>
                        <?php endif; ?>
                        <?php echo $field->input; ?>
                    </div>
                <?php endforeach; ?>
        </fieldset>
        <fieldset class="panelform">
        	<h2><?php echo JText::_('Extra'); ?></h2>
            	<?php
            			$hitsField = $this->form->getField('hits');
	            		$resetField = $this->form->getField('resethits');
	            		$hits = $hitsField->value;
	            		if(!empty($hits)):
	            			echo '<div class="formelm">'.JText::_('JRESEARCH_HITS').': '.$hitsField->value.'</div>';
	            			echo '<div class="formelm">';	            				
		            		echo $resetField->label;
		            		echo $resetField->input;	            		
		            		echo '</div>';
		            	endif;	
            		?>
            		
                <?php foreach($this->form->getFieldset('extra') as $field): ?>
                        <div class="formelm">
                            <?php if (!$field->hidden): ?>
                                    <?php echo $field->label; ?>
                            <?php endif; ?>
                            <?php echo $field->input; ?>
                        </div>
            <?php endforeach; ?>
        </fieldset>
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid', 0); ?>" />
	    <input type="hidden" name="task" value="edit" />
	    <input type="hidden" name="controller" value="publications" />    
        <?php echo JHtml::_('form.token'); ?>
</form>
</div>
<div class="clr"></div>