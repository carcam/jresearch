<?php
/**
* @package JResearch
* @subpackage Teams
* Default view for showing a single team
*/

$leader = $this->item->getLeader();
$contentArr = explode('<hr id="system-readmore" />', $this->item->description);
?>
<h1 class="componentheading">
	<?php echo JText::_('JRESEARCH_TEAM');?>
	-
	<?php echo JFilterOutput::ampReplace($this->item->name);?>
</h1>
<table summary="<?php echo JText::_('JRESEARCH_TEAM_SUMMARY'); ?>">
	<tfoot>
		<tr>
			<td colspan="2">
				<a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<th><?php echo JText::_('JRESEARCH_TEAM_LEADER');?>:</th>
			<td>
				<a href="index.php?option=com_jresearch&view=member&task=show&id=<?php echo $leader->id; ?>&Itemid=<?php echo (isset($this->itemId)?$this->itemId:'');?>" title="">
				<?php
				echo $leader->__toString();
				?>
				</a>
			</td>
		</tr>
		<?php 
		if($leader->position):
		?>
		<tr>
			<th><?php echo JText::_('Position').': ' ?></th>
			<td><?php echo $leader->position; ?></td>
		</tr>
		<?php 
		endif;
		
		if($leader->location):
		?>
		<tr>
			<th><?php echo JText::_('JRESEARCH_LOCATION'); ?></th>
			<td><?php echo $leader->location; ?></td>
		</tr>
		<?php 
		endif;
		
		if($leader->phone_or_fax):
		?>
		<tr>
			<th><?php echo JText::_('JRESEARCH_PHONE_OR_FAX').': ' ?></th>
			<td><?php echo $leader->phone_or_fax; ?></td>
		</tr>
		<?php 
		endif;
		
		if($leader->email):
		?>
		<tr>
			<th><?php echo JText::_('Email').' :' ?></th>
			<td><?php echo $leader->email; ?></td>
		</tr>
		<?php 
		endif;
		?>
		<tr>
			<th><?php echo JText::_('JRESEARCH_TEAM_MEMBERS');?>:</th>
			<td>
				<ul><li><?php echo implode("</li><li> ", $this->memberLinks)?></li></ul>
			</td>
		</tr>
		<tr>
			<th colspan="2" scope="colgroup">
				<?php echo JText::_('Description')?>:
			</th>
		</tr>
		<?php
		//Show description only if description exists
		if($contentArr[0] != ""):
		?>
		<tr>
			<td colspan="2">
				<div>
					<?php echo $contentArr[0];?>
				</div>
				<div style="text-align:left">
					<?php echo $contentArr[1];?>
				</div>
			</td>
		</tr>
		<?php
		endif;
		?>
	</tbody>
</table>