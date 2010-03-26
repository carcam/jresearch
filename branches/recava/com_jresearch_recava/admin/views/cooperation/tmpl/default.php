<?php
/**
* @package JResearch
* @subpackage Cooperations
* Default view for adding/editing a cooperation
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
?>
<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data" class="form-validate" onSubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_COOPERATION')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_COOPERATION_NAME').': '?></td>
		<td>
			<input name="name" id="name" size="50" maxlength="100" class="required" value="<?php echo $this->coop?$this->coop->name:'' ?>" />
			<br /><label for="name" class="labelform"><?php echo JText::_('Please provide a name. Alphabetic characters plus _- and spaces are allowed.'); ?></label>
		</td>
		<td><?php echo JText::_('JRESEARCH_COOPERATION_URL').': '; ?></td>
		<td>
			<input name="url" id="url" size="50" maxlength="255" class="required" value="<?php echo $this->coop?$this->coop->url:''; ?>" />
			<br /><label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>			
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
		<td><?php echo JText::_('Order').': '; ?></td>
		<td><?php echo $this->orderList; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('Photo').': ' ?></td>
		<td>
			<input class="inputbox" name="inputfile" id="inputfile" type="file" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::sprintf('JRESEARCH_IMAGE_SUPPORTED_FORMATS', _COOPERATION_IMAGE_MAX_WIDTH_, _COOPERATION_IMAGE_MAX_HEIGHT_)); ?><br />
		</td>
		<td colspan="2">
			<?php
			if($this->coop && $this->coop->image_url):
			?>
				<a href="<?php echo $this->coop->image_url;?>" class="modal">
					<img src="<?php echo $this->coop->image_url;?>" alt="<?php echo JText::_('Photo'); ?>" class="modal" />
				</a>
				<label for="delete" /><?php echo JText::_('JRESEARCH_DELETE_CURRENT_PHOTO'); ?></label><input type="checkbox" name="delete" id="delete" />
				<input type="hidden" name="image_url" id="image_url" value="<?php echo $this->coop->image_url;?>" />
			<?php
			endif;
			?>
		</td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $this->editor->display( 'description',  $this->coop?$this->coop->description:'' , '100%', '350', '75', '20' ) ; ?></td>
	</tr>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_COOPERATION_INTERCHANGE')?></th>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_COOPERATION_PERSON_NAME')?></th>
		<td>
			<input name="person_name" id="person_name" size="50" maxlength="100" class="required" value="<?php echo $this->coop?$this->coop->person_name:'' ?>" />
			<br /><label for="person_name" class="labelform"><?php echo JText::_('Please provide a name. Alphabetic characters plus _- and spaces are allowed.'); ?></label>
		</td>
		<th><?php echo JText::_('JRESEARCH_COOPERATION_TOI')?></th>
		<td>
			<?php echo $this->toiElement; ?>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_START_DATE').': ' ?></th>
		<?php $startDate = $this->coop?$this->coop->start_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $startDate ,'start_date', 'start_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="start_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label> 
		</td>
		<th><?php echo JText::_('JRESEARCH_DEADLINE').': ' ?></th>
		<?php $endDate = $this->coop?$this->coop->end_date:''; ?>
		<td>
			<?php echo JHTML::_('calendar', $endDate ,'end_date', 'end_date', '%Y-%m-%d', array('class'=>'validate-date')); ?><br />
			<label for="end_date" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DATE'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_COOPERATION_RECEPTOR')?></th>
		<td><?php echo $this->receptorElement; ?></td>
		<th><?php echo JText::_('JRESEARCH_COOPERATION_EMISOR')?></th>
		<td><?php echo $this->emisorElement; ?></td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="cooperations" />
<input type="hidden" name="id" value="<?php echo $this->coop?$this->coop->id:'' ?>" />	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>		
</form>