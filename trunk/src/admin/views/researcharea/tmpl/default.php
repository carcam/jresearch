<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Research Areas
* @copyright	Luis Galárraga.
* @license		GNU/GPL
* Form for the edition of a research area.
*/

defined('_JEXEC') or die('Restricted access');

$fields = array('id', 'name', 'alias');
$user = JFactory::getUser();
if($user->authorise('core.researchareas.edit.state', 'com_jresearch')){
	$fields[] = 'published';
}

?>

<div class="row-fluid">
    <div class="spans10">
            <form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
                <fieldset>
                    <?php foreach($fields as $fieldName):  ?>
                            <?php $field = $this->form->getField($fieldName); ?>
                            <div class="control-group">
                                    <?php if (!$field->hidden): ?>
                                            <div class="control-label">
                                                    <?php echo $field->label; ?>
                                            </div>
                                    <?php endif; ?>
                                    <div class="controls">
                                            <?php echo $field->input; ?>
                                    </div>
                            </div>
                    <?php endforeach; ?>
                    <div class="control-group">
                            <div class="control-label">
                                    <?php $description = $this->form->getField('description'); ?>
                                    <?php echo $description->label;?>
                            </div>
                            <div class="controls">
                                    <?php echo $description->input; ?>
                            </div>
                    </div>
                </fieldset>
                <input type="hidden" name="task" value="edit" />
                <input type="hidden" name="controller" value="researchareas" />
                <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>
