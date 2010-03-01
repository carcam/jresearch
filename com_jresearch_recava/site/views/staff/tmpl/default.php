<?php
/**
 * @package JResearch
 * @subpackage Staff
 * Default view for showing a list of staff members
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_MEMBERS'); ?></h1>
<table width="100%" cellpadding="2" cellspacing="2" align="center">
<thead>
	<tr align="center">
		<th width="25%"><?php echo JText::_('JRESEARCH_NAME'); ?></th>
		<th width="25%"><?php echo JText::_('JRESEARCH_EMAIL'); ?></th>	
		<th width="25%"><?php echo JText::_('JRESEARCH_RESEARCH_AREA'); ?></th>
		<th width="25%"><?php echo JText::_('JRESEARCH_POSITION'); ?></th>												
	</tr>
</thead>
<?php $itemId = JRequest::getVar('Itemid'); ?>
<tbody>
<?php foreach($this->items as $member){ 
	$researchArea = $this->areaModel->getItem($member->id_research_area);
?>
	<tr align="center">
		<td width="25%"><a href="<?php echo JURI::base(); ?>index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?php echo $member->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo $member->__toString(); ?></a></td>
		<td width="25%"><?php echo JHTML::_('email.cloak', $member->email); ?></td>
		<td width="25%">
			<?php if($researchArea->id > 1):?>
				<a href="index.php?option=com_jresearch&amp;view=researcharea&amp;task=show&amp;id=<?php echo $researchArea->id;?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo $researchArea->name; ?></a>
			<?php else: ?>
				<?php echo $researchArea->name; ?>				
			<?php endif; ?>
		</td>
		<td width="25%"><?php echo $member->position; ?></td>
	</tr>
<?php } ?>
</tbody>
<tfoot align="center">
	<tr><td colspan="4"><?php echo $this->page->getResultsCounter(); ?><br /><?php echo $this->page->getPagesLinks(); ?></td></tr>
</tfoot>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="controller" value="staff"  />
<input type="hidden" name="limitstart" value="" />
<input type="hidden" name="hidemainmenu" value="" />
<?php echo JHTML::_( 'form.token' ); ?>