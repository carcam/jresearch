<?php
/**
 * @package JResearch
 * @subpackage Publications
 */

defined('_JEXEC') or die('Restricted access'); ?>
<ul>
<?php $digitalVersion = JText::_('JRESEARCH_DIGITAL_VERSION'); ?>
<?php
foreach($this->items as $pub): 
	$styleObj = JResearchCitationStyleFactory::getInstance($this->style, $pub->pubtype);
	$publicationText = $styleObj->getReferenceHTMLText($pub, true, true);
?>
	<?php $url = $pub->url; ?>
	<li><span><?php echo $publicationText;  ?></span>
	<?php if($this->showmore): ?>
		<span><a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a></span>
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php
			 if(!empty($url))
				$link = $url;
		 ?>
		<?php if(!empty($link)): ?>
			<?php "<span><a href=\"$link\">[$digitalVersion]</a></span>"; ?>			
		<?php endif; ?>
	<?php endif; ?>	
	<span><?php JHTML::_('Jresearch.icon','edit', 'publications', $pub->id); ?> <?php JHTML::_('Jresearch.icon','remove', 'publications', $pub->id); ?></span>
	</li>
<?php endforeach; ?>
</ul>