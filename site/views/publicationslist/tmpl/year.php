<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for showing the year of a publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

?>
<?php 
	$digitalVersion = JText::_('JRESEARCH_DIGITAL_VERSION');
	foreach($this->items as $year=>$publications): ?>
	<div class="frontendheader"><?php echo $year; ?></div>
    <ul>
    
	<?php 
	foreach($publications as $pub):
		$styleObj = JResearchCitationStyleFactory::getInstance($this->style, $pub->pubtype);
		$publicationText = $styleObj->getReferenceHTMLText($pub, true);
	?>
	<?php $url = $pub->url; ?>
	<li>
	<span><?php echo $publicationText;  ?></span>
	<?php if($this->showmore): ?>
		<span><a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a></span>
	<?php endif; ?>
	<?php if($this->showdigital): ?>
		<?php 
			  if(!empty($url))
				$link = $url;							
		 ?>
		<?php if(!empty($link)): ?>
			<?php echo "<span><a href=\"$link\">[$digitalVersion]</a></span>"; ?>			
		<?php endif; ?>
	<?php endif; ?>	
	<span><?php JHTML::_('Jresearch.icon','edit', 'publications', $pub->id); ?> <?php JHTML::_('Jresearch.icon','remove', 'publications', $pub->id); ?></span>
	</li>
	<?php endforeach; ?>
	</ul>
<?php endforeach ?> 