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
	<h3 class="frontendheader"><?php echo $year; ?></h3>
    <ul>
    
	<?php 
	foreach($publications as $pub):
		$styleObj = JResearchCitationStyleFactory::getInstance($this->style, $pub->pubtype);
		$publicationText = $styleObj->getReferenceHTMLText($pub, true);
	?>
	<?php $url = $pub->url; ?>
	<?php $attach = $pub->getAttachment(0, 'publications'); ?>
	<li>
	<span><?php echo $publicationText;  ?></span>
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
			<?php echo "<span><a href=\"$link\">[$digitalVersion]</a></span>"; ?>			
		<?php endif; ?>
	<?php endif; ?>	
	<?php if($this->showBibtex): 
		echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=bibtex&amp;id='.$pub->id, '[Bibtex]').'</span>';		
	 endif;?>	
	<?php if($this->showRIS): 
		echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=ris&amp;id='.$pub->id, '[RIS]').'</span>';		
	 endif;?>
	 <?php if($this->showMODS): 
		echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=mods&amp;id='.$pub->id, '[MODS]').'</span>';		
	 endif;?>	 	
	<span><?php echo JHTML::_('Jresearch.icon','edit', 'publications', $pub->id); ?> <?php echo JHTML::_('Jresearch.icon','remove', 'publications', $pub->id); ?></span>
	</li>
	<?php endforeach; ?>
	</ul>
<?php endforeach ?> 