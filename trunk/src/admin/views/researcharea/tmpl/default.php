<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

$fields = array('id', 'name', 'alias', 'published');

?>
<form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="width-70">
        <fieldset class="panelform">
                <ul class="adminformlist">
                <?php foreach($fields as $fieldName):  ?>
                    <?php $field = $this->form->getField($fieldName); ?>
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
    <input type="hidden" name="task" value="edit" />
    <input type="hidden" name="controller" value="researchAreas" />
    <?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>