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
	<?php $digitalVersion = JText::_('JRESEARCH_DIGITAL_VERSION'); ?>
	<?php $url = $pub->url; ?>
	<?php $attach = $pub->getAttachment(0, 'publications'); ?>	
	<tr><td align="right"><?php JHTML::_('Jresearch.icon','edit', 'publications', $pub->id); ?> <?php JHTML::_('Jresearch.icon','remove', 'publications', $pub->id); ?></td></tr>
	<tr><td><?php echo $publicationText;  ?>&nbsp;
	<?php if($this->showmore): ?>
		<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a>&nbsp;
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php
			 if(!empty($url))
				$link = $url;
			  elseif(!empty($attach))
			  	$link = $attach;							
		 ?>
		<?php if(!empty($link)): ?>
			<?php "<a href=\"$link\">[$digitalVersion]</a>"; ?>			
		<?php endif; ?>
	<?php endif; ?>	
	</td></tr>
<?php endforeach; ?>