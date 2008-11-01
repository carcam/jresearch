<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>
<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch">
	<table>
		<tbody>
			<tr>
				<td style="text-align:left; width:100%;">
				</td>
				<td nowrap="nowrap">
					<?=$this->lists['state'];?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="adminlist" cellspacing="1">
		<thead>
		<tr>		
			<th width="1%">#</th>
			<th width="1%" align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?=count($this->items );?>);" /></th>
			<th class="month" width="30%"><?=JHTML::_('grid.sort', JText::_('JRESEARCH_MDM_MONTH'), 'month', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?=JHTML::_('grid.sort', 'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			<th><?=JText::_('JRESEARCH_MEMBER'); ?></th>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="6">
					<?=$this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
			<?php 
			$n = count($this->items);
			for($i=0; $i<$n; $i++)
			{
				$k = $i % 2;
				$checked 	= JHTML::_('grid.checkedout', $this->items[$i], $i ); 
				$published  = JHTML::_('grid.published', $this->items[$i], $i );
				$mdm =& $this->items[$i];	//Bind element
				
				//Bind member
				$user =& $this->user->getItem($mdm->id_member);
			?>
				<tr class="<?="row$k";?>">
					<td><?=$this->page->getRowOffset($i);?></td>
					<td width="1%"><?=$checked;?></td>
					<td width="30%"><a href="index.php?option=com_jresearch&controller=mdm&task=edit&cid[]=<?=$mdm->id; ?>"><?=date("F Y",strtotime($mdm->month));?></a></td>
					<td align="center"><?=$published;?></td>
					<td align="center"><?=$user->firstname." ".$user->lastname?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jresearch" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?=$this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" /> 
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="mdm"  />
	<?=JHTML::_('form.token'); ?>
</form>
