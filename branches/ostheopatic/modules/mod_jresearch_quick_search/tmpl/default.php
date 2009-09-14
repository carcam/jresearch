<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="index.php?option=com_jresearch&view=publicationssearch&task=search" method="post" name="quicksearchForm">
     <?php $params = JModuleHelper::getParams('mod_jresearch_quick_search'); 
     	   $size = is_numeric($params->get('field_size'))?$params->get('field_size'):12;	
     ?>
     <div class="menu<?php echo $params->get('moduleclass_sfx')?>">
	     <input name="key" size="<?php echo $size?>" value="" />
     </div>
</form>