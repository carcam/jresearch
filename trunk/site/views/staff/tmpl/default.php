<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo JText::_('Staff Members'); ?></div>
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
		<td width="25%"><a href="<?php echo JURI::base(); ?>index.php?option=com_jresearch&view=member&task=show&id=<?php echo $member->id; ?><?php echo isset($itemId)?'&Itemid='.$itemId:''; ?>"><?php echo $member; ?></a></td>
		<td width="25%"><?php echo $member->email; ?></td>
		<td width="25%"><?php echo $researchArea->name; ?></td>
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