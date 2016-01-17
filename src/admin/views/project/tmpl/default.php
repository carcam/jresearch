<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Luis Galárraga.
* @license		GNU/GPL
* Form for the edition of a project.
*/

defined('_JEXEC') or die('Restricted access');

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
        </fieldset>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'description', JText::_('JRESEARCH_DESCRIPTION', true)); ?>
        <div class="row-fluid">
            <fieldset>
                <div>
                <?php
                    $description = $this->form->getField('description');
                    echo $description->label;
                    echo $description->input;
            	?>
                </div>
            </fieldset>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'extra', JText::_('JRESEARCH_EXTRA', true)); ?>        
        <div class="row-fluid">
            <fieldset>
            <ul class="adminformlist">            
                <?php foreach($this->form->getFieldset('extra') as $field): ?>
                <li>
                    <?php if ($field->name == "hits") : ?>
                        <?php if ($field->value != '0') : ?>
                            <?php echo $field->label.': '; ?>
                            <?php echo $field->value; ?>
                        <?php endif; ?>
                    <?php else : ?>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>        
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>        
        <input type="hidden" name="task" value="edit" />
        <input type="hidden" name="controller" value="projects" />    
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<div class="clr"></div>