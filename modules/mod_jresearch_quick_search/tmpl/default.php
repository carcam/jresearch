<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="index.php?option=com_jresearch&amp;view=publicationssearch&amp;task=startsearch" method="post" name="quicksearchForm">
     <?php 
     	   $size = is_numeric($params->get('field_size'))?$params->get('field_size'):12;	
     ?>
     <div class="menu<?php echo $params->get('moduleclass_sfx')?>">
	     <input name="key" size="<?php echo $size?>" value="<?php echo JRequest::getVar('key', ''); ?> " />
	     <input type="hidden" name="newSearch" value="1" /> 
     </div>
</form>