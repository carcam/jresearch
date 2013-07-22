<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

$fields = array('id', 'firstname', 'lastname', 'title', 'username', 'email' , 'id_research_area' , 
 'location', 'phone', 'fax', 'url_personal_page', 'files');

?>
<div style="float: left;">
<h1><?php echo JText::_('JRESEARCH_EDIT_PROFILE');?></h1>
</div>
<div style="float: right;">
	<button type="button" onclick="javascript:Joomla.submitbutton('apply')"><?php echo JText::_('JRESEARCH_SAVE') ?></button>
</div>
<div style="clear:both;"></div>
<form action="<?php echo JRoute::_('index.php?option=com_jresearch'); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data">
	<?php foreach($fields as $fieldName):  ?>
    	<?php $field = $this->form->getField($fieldName); ?>
           	<div class="formelm">
            	<?php if (!$field->hidden): ?>
                	<?php echo $field->label; ?>
                 <?php endif; ?>
                 <?php echo $field->input; ?>
             </div>
                <?php endforeach; ?>            
            <div>
			<div style="clear:left;"></div>            
            <?php
            $description = $this->form->getField('description');
            echo $description->label;
            ?></div>
            <div class="clr"></div>
            <div><?php echo $description->input; ?></div>
            <div class="clr"></div>
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="controller" value="staff" />   
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" /> 
    <?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>