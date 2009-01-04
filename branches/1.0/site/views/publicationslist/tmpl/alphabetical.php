<?php
/**
 * @package JResearch
 * @subpackage Publications
 */

defined('_JEXEC') or die('Restricted access'); 
global $mainframe;
$style = $mainframe->getParams('com_jresearch')->get('citationStyle', 'APA');
foreach($this->items as $pub): 
	$styleObj = JResearchCitationStyleFactory::getInstance($style, $pub->pubtype);
	$publicationText = $styleObj->getReferenceHTMLText($pub, true, true);
?>
	<tr><td><?php echo $publicationText;  ?>&nbsp;<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a></td></tr>
<?php endforeach; ?>
