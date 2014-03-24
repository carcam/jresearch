<?php
/**
 * @package JResearch
 * @subpackage Publications
 * @license	GNU/GPL
 * View for adding/editing a new publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1><?php echo JText::_('JRESEARCH_NEW_PUBLICATION'); ?></h1>
<form name="adminForm" id="adminForm" method="post" action="index.php">
<table class="adminform">
	<tbody>
		<tr>
			<th style="width: 20%;"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></th>
			<td>
				<?php echo $this->types; ?>
			</td>
		</tr>
	</tbody>
</table>
<div style="text-align: center;">
	<input name="submit" value="<?php echo JText::_('New'); ?>" type="submit" />
</div>

<?php echo JHTML::_('jresearchhtml.hiddenfields', 'publications', 'edit'); ?>
</form>