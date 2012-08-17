<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Specific type view for unpublished publication
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php $month = trim($this->publication->month);  ?>
<?php if(!empty($month)): ?>
<dt><?php echo JText::_('JRESEARCH_MONTH').': ' ?></dt>		
<dd><?php echo JResearchPublicationsHelper::formatMonth($month); ?></dd>
<?php endif; ?>
