<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

if(!empty($this->publication)){
	$type = JHTML::_('jresearchhtml.onlineresourcelist', array('selected'=> $this->publication->type, 'name'=> 'source_type'));
}else{
	$type = JHTML::_('jresearchhtml.onlineresourcelist', array('name'=>'source_type'));
}
?>

<div class="divTR">
	<div class="divTd"><label for="source_type"><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></label></div>
	<div class="divTdl divTdl2"><?php echo $type; ?></div>
	<div class="divTd"><label for="access_date"><?php echo JText::_('JRESEARCH_ACCESS_DATE').': ' ?></label></div>
	<div class="divTdl"><?php echo JHTML::_('calendar', !empty($this->publication)?$this->publication->access_date:'' ,'access_date', 'access_date', '%Y-%m-%d', array('class'=>'validate-date', 'size'=>'12')); ?><br />	<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'access_date', JText::_('JRESEARCH_PROVIDE_VALID_DATE')); ?></div>				
	<div class="divEspacio" ></div>			
</div>