<?php
/**
 * @package JResearch
 * @subpackage Publications
 */

defined('_JEXEC') or die('Restricted access'); 
foreach($this->items as $pub): 
	$publicationText = $this->style->getReferenceHTMLText($pub, true, true);
?>
	<?php $digitalVersion = JText::_('JRESEARCH_DIGITAL_VERSION'); ?>
	<?php $url = $pub->url; ?>

	<tr><td><?php echo $publicationText;  ?>&nbsp;
	<?php if($this->showmore): ?>
		<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a>&nbsp;
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php echo !empty($url)?"<a href=\"$url\">[$digitalVersion]</a>":''; ?>
	<?php endif; ?>
	</td></tr>
<?php endforeach; ?>