<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a single unpublished publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="divTR">
	<div class="divTd"><label for="month"><?php echo JText::_('JRESEARCH_MONTH').': ' ?></label></div>
	<div><input type="text" name="month" id="number" size="15" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></div>
</div>