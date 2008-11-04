<?php
/**
 * @package JResearch
 * @subpackage Staff
 * 
 * View for adding user to the staff of J!Research
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch">
	<div style="align:center;width:100%;margin-left:auto;margin-right:auto"><?php echo $this->control; ?></div>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="task" value="import" />
	<input type="hidden" name="controller" value="staff"  />
	<input type="hidden" name="hidemainmenu" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>

</form>