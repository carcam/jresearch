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
	<?php $attach = $pub->getAttachment(0, 'publications'); ?>	
	<li><span><?php echo $publicationText;  ?></span>
	<?php if($this->showmore): ?>
		<span><?php echo JHTML::_('jresearch.link', JText::_('JRESEARCH_MORE'), 'publication', 'show', $pub->id); ?></span>
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php
			 if(!empty($url))
				$link = str_replace('&', '&amp;', $url);
			  elseif(!empty($attach))
			  	$link = $attach;							
		 ?>
		<?php if(!empty($link)): ?>
			<?php "<span><a href=\"$link\">[$digitalVersion]</a></span>"; ?>			
		<?php endif; ?>
	<?php endif; ?>	
	<span><?php echo JHTML::_('Jresearch.icon','edit', 'publications', $pub->id); ?> <?php echo JHTML::_('Jresearch.icon','remove', 'publications', $pub->id); ?></span>
	</li>
<?php endforeach; ?>
</ul>