<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single misc. publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divTR">
	<div class="divTd"><label for="howpublished"><?php echo JText::_('JRESEARCH_HOW_PUBLISHED').': ' ?></label></div>		
	<div class="divTdl divTdl2"><input name="howpublished" id="howpublished" type="text" size="15" maxlength="255" value="<?php echo $this->publication?$this->publication->howpublished:'' ?>" /></div>
	<div class="divTd"><label for="month"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></label></div>
	<div class="divTdl"><input type="text" name="month" id="number" size="15" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></div>
</div>