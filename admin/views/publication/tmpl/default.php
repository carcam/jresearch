<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<div style="text-align:center;"><h3><?php echo JText::_('JRESEARCH_'.strtoupper($this->pubtype).'_DEFINITION'); ?></h3></div>
<?php 
	$actions = JResearchAccessHelper::getActions();

?>
<form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
   
    <div class="width-60 fltlft">
        <fieldset class="panelform">
                <legend><?php echo JText::_( 'JRESEARCH_BASIC' ); ?></legend>
                <ul class="adminformlist">
                <?php foreach($this->form->getFieldset('basic') as $field): ?>
                	<?php 
                		if(($field->name == 'published' || $field->name == 'internal')
                		&& !$actions->get('core.publications.edit.state'))
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
        </fieldset>
    </div>
    <div class="width-40 fltrt">
        <?php echo JHtml::_('sliders.start','content-sliders', array('useCookie'=>1)); ?>

        <?php echo JHtml::_('sliders.panel',JText::_('JRESEARCH_SPECIFIC'), 'specific-details'); ?>
        <fieldset class="panelform">
            <ul>
                <?php foreach($this->form->getFieldset('specific') as $field): ?>
                    <li>
                        <?php if (!$field->hidden): ?>
                                <?php echo $field->label; ?>
                        <?php endif; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
        <?php echo JHtml::_('sliders.panel',JText::_('Extra'), 'extra-details'); ?>
        <fieldset class="panelform">
            <ul>
            
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
            		
                <?php foreach($this->form->getFieldset('extra') as $field): ?>
                        <li>
                            <?php if (!$field->hidden): ?>
                                    <?php echo $field->label; ?>
                            <?php endif; ?>
                            <?php echo $field->input; ?>
                        </li>
            <?php endforeach; ?>
            </ul>
        </fieldset>
        <?php echo JHtml::_('sliders.panel',JText::_('JRESEARCH_PUBLICATION_CHANGE_TYPE'), 'change-type'); ?>
            <?php if(!isset($this->publication)): ?>
                <div class="divChangeType">
                        <?php echo $this->changeType; ?>
                </div>
			<?php endif; ?>
		<?php echo JHtml::_('sliders.end'); ?>
	    <input type="hidden" name="task" value="edit" />
	    <input type="hidden" name="controller" value="publications" />    
        <?php echo JHtml::_('form.token'); ?>
    </div> 
</form>
<div class="clr"></div>