<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single masterthesis
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
$thesesTypes = JHTML::_('jresearchhtml.thesestypeslist', array('name' => 'type', 'selected' => isset($this->publication)?$this->publication->type:'bsc'));

?>

<div class="divTR">
	<div class="divTd"><label for="school"><?php echo JText::_('JRESEARCH_SCHOOL').': ' ?></label></div>
	<div class="divTdl divTdl2"><input type="text" name="school" id="school" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->school:'' ?>" /></div>	
	<div class="divTd"><label for="type"><?php echo JText::_('JRESEARCH_FIELD_TYPE').': ' ?></label></div>
	<div class="divTdl"><?php echo $thesesTypes; ?></div>
	<div class="divEspacio" ></div>	
</div>
<div class="divTR">
	<div class="divTd"><label for="address"><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></label></div>
	<div class="divTdl divTdl2"><input type="text" name="address" id="address" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->address:'' ?>" /></div>
</div>