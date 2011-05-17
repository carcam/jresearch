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
	<div class="divTdl divTdl2"><input name="howpublished" id="howpublished" type="text" size="15" maxlength="255" value="<?php echo isset($this->publication)?$this->publication->howpublished:'' ?>" /></div>
</div>