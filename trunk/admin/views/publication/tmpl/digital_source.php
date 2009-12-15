<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

if(!empty($this->publication)){
	$type = JHTML::_('jresearchhtml.digitalresourcelist', array('selected'=> $this->publication->source_type, 'name'=> 'source_type'));
}else{
	$type = JHTML::_('jresearchhtml.digitalresourcelist', array('name'=>'source_type'));
}
?>
<div class="divTR">
	<div class="divTd"><label for="source_type" ><?php echo JText::_('JRESEARCH_SOURCE_TYPE').': ' ?></label></div>
	<div class="divTdl divTdl2"><?php echo $type; ?></div>
	<div class="divTd"><?php echo JText::_('Publisher').': ' ?></div>
	<div class="divTdl"><input name="publisher" id="publisher" type="text" size="15" maxlength="60" value="<?php echo isset($this->publication)?$this->publication->publisher:'' ?>" /></div>
	<div class="divEspacio" ></div>			
</div>
<div class="divTR">
	<div class="divTd"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></div>
	<div class="divTdl"><input type="text" name="address" id="address" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->address:'' ?>" /></div>
</div>