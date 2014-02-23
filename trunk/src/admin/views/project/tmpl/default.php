<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
$actions = JResearchAccessHelper::getActions();
?>
<form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
    <div class="width-60 fltlft">
        <fieldset class="panelform">
                <legend><?php echo JText::_( 'JRESEARCH_BASIC' ); ?></legend>
                <ul class="adminformlist">
                <?php foreach($this->form->getFieldset('basic') as $field): ?>
                	<?php 
                		if(($field->name == 'published')
                		&& !$actions->get('core.projects.edit.state'))
                			continue;
                	?>
                    <li>
                        <?php if (!$field->hidden): ?>
                                <?php echo $field->label; ?>
                        <?php endif; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>            	                
                </ul>
            	<div class="clr"></div>
            	<div><?php
            		$description = $this->form->getField('description');
            		echo $description->label;
            	?></div>
            	<div class="clr"></div>
            	<div><?php echo $description->input; ?></div>
            	<div class="clr"></div>                
        </fieldset>
    </div>
    <div class="width-40 fltrt">
        <?php echo JHtml::_('sliders.start','content-sliders', array('useCookie'=>1)); ?>
        <?php echo JHtml::_('sliders.panel',JText::_('JRESEARCH_PUBLICATIONS'), 'extra-details'); ?>
        <fieldset class="panelform">
            <ul class="adminformlist">
            
            	<li>
            		<?php $pubsField = $this->form->getField('publications'); 
            			  echo $pubsField->label;
            			  echo $pubsField->input;
            		?>
            	</li>
            	<?php
            			$hitsField = $this->form->getField('hits');
	            		$resetField = $this->form->getField('resethits');
	            		$hits = $hitsField->value;
	            		if(!empty($hits)):
	            			echo '<li>'.JText::_('JRESEARCH_HITS').': '.$hitsField->value.'</li>';
	            			echo '<li>';	            				
		            		echo $resetField->label;
		            		echo $resetField->input;	            		
		            		echo '</li>';
		            	endif;	
            		?>
            </ul>
        </fieldset>
        <?php echo JHtml::_('sliders.panel', JText::_('JRESEARCH_FILES'), 'attachments'); ?>        
        <fieldset class="panelform">
         	<?php $pubsField = $this->form->getField('files'); 
            	  echo $pubsField->input;
            ?>        
        </fieldset>        
	    <input type="hidden" name="task" value="edit" />
	    <input type="hidden" name="controller" value="projects" />    
        <?php echo JHtml::_('form.token'); ?>
    </div> 
</form>
<div class="clr"></div>