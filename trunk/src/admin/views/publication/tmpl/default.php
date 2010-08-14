<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>

<script type="text/javascript">
	function submitbutton(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			submitform(task);
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<div style="text-align:center;"><h3><?php echo JText::_('JRESEARCH_'.strtoupper($this->pubtype).'_DEFINITION'); ?></h3></div>

<form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
   
    <div class="width-60 fltlft">
        <fieldset class="panelform">
                <legend><?php echo JText::_( 'JRESEARCH_BASIC' ); ?></legend>
                <ul>
                <?php foreach($this->form->getFieldset('basic') as $field): ?>
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

        <?php echo JHtml::_('sliders.end'); ?>
        <input type="hidden" name="task" value="publication.edit" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<div class="clr"></div>