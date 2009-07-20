<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

?>

<div class="divTR">
	<div class="divTd"><label for="journal"><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="journal" id="journal" type="text" size="15" maxlength="255" value="<?php echo $this->publication?$this->publication->journal:'' ?>" /></div>
	<div class="divTd"><label for="volume"><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></label></div>
	<div class="divTdl"><input name="volume" id="volume" type="text" size="15" maxlength="30" value="<?php echo $this->publication?$this->publication->volume:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="number"><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></label></div>
	<div class="divTdl divTdl2"><input name="number" id="number" type="text" size="15" maxlength="20" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></div>
	<div class="divTd"><label for="month"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></label></div>
	<div class="divTdl"><input type="text" name="month" id="month" size="15" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="access_date"><?php echo JText::_('JRESEARCH_ACCESS_DATE').': ' ?></label></div>
	<div class="divTdl divTdl2"><?php echo JHTML::_('calendar', !empty($this->publication)?$this->publication->access_date:'' ,'access_date', 'access_date', '%Y-%m-%d', array('class'=>'validate-date', 'size'=>'12')); ?>
	<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'access_date', JText::_('JRESEARCH_PROVIDE_VALID_DATE')); ?></div>				
	<div class="divTd"><label for="day"><?php echo JText::_('JRESEARCH_DAY').': ' ?></label></div>
	<div class="divTdl"><input name="day" id="day" type="text" size="2" maxlength="2" value="<?php echo $this->publication?$this->publication->day:'' ?>" /></div>
	<div class="divEspacio" ></div>	
</div>