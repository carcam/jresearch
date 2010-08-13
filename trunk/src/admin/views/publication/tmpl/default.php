<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="adminForm">
        <fieldset class="adminform">
                <legend><?php echo JText::_( 'com_helloworld_HelloWorld_Details' ); ?></legend>
                <?php foreach($this->form->getFieldset() as $field): ?>
                        <?php if (!$field->hidden): ?>
                                <?php echo $field->label; ?>
                        <?php endif; ?>
                        <?php echo $field->input; ?>
                <?php endforeach; ?>
        </fieldset>
        <input type="hidden" name="task" value="helloworld.edit" />
</form>

?>