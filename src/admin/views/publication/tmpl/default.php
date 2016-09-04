<?php
/**
* @package      JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license	GNU/GPL
*/
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<div style="text-align:center;"><h3><?php echo JText::_('JRESEARCH_'.strtoupper($this->pubtype).'_DEFINITION'); ?></h3></div>
<?php 
	$actions = JResearchAccessHelper::getActions();

?>
<form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basic')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'basic', JText::_('JRESEARCH_BASIC', true)); ?>
        <div class="row-fluid">
            <fieldset>
            <ul class="adminformlist">
            <?php foreach($this->form->getFieldset('basic') as $field): ?>                    
                    <?php
                            // Keep the abstract in its own tab
                            if ($field->fieldname == 'abstract') {
                                continue;
                            }
                            
                            if (($field->fieldname == 'published' || $field->fieldname == 'internal') 
                            && !$actions->get('core.publications.edit.state')) {
                                    continue;
                            }
                    ?>
                <li>
                    <div class="control-label">
                    <?php if (!$field->hidden): ?>
                            <?php echo $field->label; ?>
                    <?php endif; ?>
                    </div>
                    <div class="controls">
                    <?php echo $field->input; ?>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
            </fieldset>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>        
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'abstract', JText::_('JRESEARCH_ABSTRACT', true)); ?>
            <?php $abstractField = $this->form->getField('abstract'); ?>
            <?php echo $abstractField->input; ?>
        <?php echo JHtml::_('bootstrap.endTab'); ?>        
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'specific', JText::_('JRESEARCH_SPECIFIC', true)); ?>
        <div class="row-fluid">
            <fieldset>
            <ul class="adminformlist">
                <?php foreach($this->form->getFieldset('specific') as $field): ?>
                    <li>
                        <div class="control-label">
                            <?php if (!$field->hidden): ?>
                                    <?php echo $field->label; ?>
                            <?php endif; ?>
                        </div>
                        <div class="controls">
                            <?php echo $field->input; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            </fieldset>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>         
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'extra', JText::_('JRESEARCH_EXTRA', true)); ?>
        <div class="row-fluid">
            <fieldset>
            <ul class="adminformlist">            
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
                            <div class="control-label">
                                <?php if (!$field->hidden): ?>
                                    <?php echo $field->label; ?>
                                <?php endif; ?>
                            </div>
                            <div class="controls">
                                <?php echo $field->input; ?>
                            </div>
                        </li>
            <?php endforeach; ?>
            </ul>
            <?php if(!isset($this->publication)): ?>
                <div class="control-label">
                <label id="jform_change_type-lbl" for="change_type" class="hasTooltip" 
                       title="" data-original-title="<?php echo JText::_('JRESEARCH_PUBLICATION_CHANGE_TYPE');  ?>">
                       <br /><?php echo JText::_('JRESEARCH_PUBLICATION_CHANGE_TYPE_DESCRIPTION');  ?>">
                                    <?php echo JText::_('JRESEARCH_PUBLICATION_CHANGE_TYPE'); ?></label>
                </div>
                <div class="controls">
                    <?php echo $this->changeType; ?>
                </div>
                    <?php endif; ?>
            </fieldset>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>             
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>            
        
        <input type="hidden" name="task" value="edit" />
        <input type="hidden" name="controller" value="publications" />    
        <?php echo JHtml::_('form.token'); ?>
    </div> 
</form>
<div class="clr"></div>