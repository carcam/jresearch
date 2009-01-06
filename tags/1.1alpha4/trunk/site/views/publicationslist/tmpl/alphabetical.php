<?php
/**
 * @package JResearch
 * @subpackage Publications
 */

defined('_JEXEC') or die('Restricted access'); 
global $mainframe;
$style = $mainframe->getParams('com_jresearch')->get('citationStyle', 'APA');
foreach($this->items as $pub): 
	$styleObj = JResearchCitationStyleFactory::getInstance($style, $this->items[$i]->pubtype);
	$publicationText = $styleObj->getReferenceHTMLText($this->items[$i], true, true);
?>
	<?php $digitalVersion = JText::_('JRESEARCH_DIGITAL_VERSION'); ?>
	<?php $url = $pub->url; ?>
	<tr><td align="right"><?php JHTML::_('Jresearch.icon','edit', 'publications', $pub->id); ?></td></tr>
	<tr><td><?php echo $publicationText;  ?>&nbsp;
	<?php if($this->showmore): ?>
		<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a>&nbsp;
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php echo !empty($url)?"<a href=\"$url\">[$digitalVersion]</a>":''; ?>
	<?php endif; ?>
	</td></tr>
<?php endforeach; ?>