<?php
/**
 * @package JResearch
 * @subpackage Staff
 * @license	GNU/GPL
 * Form for the edition of member profiles.
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

$fields = array('id', 'firstname', 'lastname', 'title', 'username', 'email', 
    'link_to_member', 'published', 'id_research_area' ,'former_member', 
    'position', 'location', 'phone', 'fax', 'url_personal_page', 'google_scholar',
    'link_to_website', 'url_photo', 'files', 'created_by');
?>
<form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basic')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'basic', JText::_('JRESEARCH_BASIC', true)); ?>

        <fieldset class="panelform">
                <ul class="adminformlist">
                <?php foreach($fields as $fieldName):  ?>
                    <?php $field = $this->form->getField($fieldName); ?>
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
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'description', JText::_('JRESEARCH_DESCRIPTION', true)); ?>
        <fieldset>
            <?php $description = $this->form->getField('description'); ?>
            <div><?php echo $description->input; ?></div>
        </fieldset>
        <?php echo JHtml::_('bootstrap.endTab'); ?>             
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>
    <input type="hidden" name="task" value="edit" />
    <input type="hidden" name="controller" value="staff" />    
    <?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>