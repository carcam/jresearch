<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing an single article
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<tr>
	<td><?php echo JText::_('JRESEARCH_JOURNAL').': ' ?></td>
	<td><input name="journal" id="journal" type="text" size="30" maxlength="255" value="<?php echo $this->publication?$this->publication->journal:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></td>
	<td><input name="volume" id="volume" type="text" size="30" maxlength="30" value="<?php echo $this->publication?$this->publication->volume:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></td>
	<td><input name="number" id="number" type="text" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->number:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_PAGES').': ' ?></td>
	<td><input name="pages" id="pages" type="text" size="10" maxlength="20" value="<?php echo $this->publication?$this->publication->pages:'' ?>" /></td>
</tr>
<tr>
	<td><?php echo JText::_('JRESEARCH_MONTH').': ' ?></td>
	<td><input type="text" name="month" id="month" size="20" maxlength="20" value="<?php echo $this->publication?$this->publication->month:'' ?>" /></td>
	<td><?php echo JText::_('JRESEARCH_CROSS_REFERENCE').': ' ?></td>
	<td><input type="text" name="crossref" id="crossref" size="20" maxlength="255" value="<?php echo $this->publication?$this->publication->crossref:''; ?>" /></td>
</tr>
