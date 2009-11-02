<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="index.php?option=com_jresearch&view=publicationssearch&task=search" method="post" name="quicksearchForm">
     <?php 
     	   $size = is_numeric($params->get('field_size'))?$params->get('field_size'):12;	
     ?>
     <div class="menu<?php echo $params->get('moduleclass_sfx')?>">
	     <input name="key" size="<?php echo $size?>" value="" />
	     <input type="hidden" name="newSearch" value="1" /> 
     </div>
</form>