<?php
/**
 * @package JResearch
 * @subpackage Publications
 */

defined('_JEXEC') or die('Restricted access'); 
foreach($this->items as $pub): 
	$publicationText = $this->style->getReferenceHTMLText($pub, true, true);
?>
	<tr><td><?php echo $publicationText;  ?>&nbsp;<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a></td></tr>
<?php endforeach; ?>