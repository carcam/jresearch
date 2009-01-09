<?php 
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a new publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if(JHTML::_('Jresearch.authorize','add', 'publications'))
{
?>
	<h1><?php echo JText::_('JRESEARCH_NEW_PUBLICATION'); ?></h1>
	<form name="adminForm" id="adminForm" method="post" action="index.php">
		<table class="adminform">
			<tbody>
			<tr>
				<td width="20%"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></td>
				<td width="80%">
			<?php echo $this->types; ?>
			</td>
			</tr>
			</tbody>
		</table>
		<div style="text-align: center;">
			<input name="submit" value="<?php echo JText::_('New'); ?>" type="submit" />
		</div>
		<input type="hidden" name="option" value="com_jresearch"  /> 
		<input type="hidden" name="controller" value="publications" />
		<input type="hidden" name="task" value="add" />
		<input type="hidden" name="id" value="" />
	</form>
<?php
}
else
{
?>
	<div style="clear: both;">&nbsp;</div>
	<div style="text-align:center;"><?=JText::_('JRESEARCH_ACCESS_NOT_ALLOWED')?></div>
<?php
}
?>