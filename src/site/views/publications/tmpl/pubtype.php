<?php
/**
 * @package JResearch
 * @subpackage Publications
 * View for showing the type of a publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
$digitalVersion = JText::_('JRESEARCH_DIGITAL_VERSION'); 
$user = JFactory::getUser();
$canDoPublications = JResearchAccessHelper::getActions(); 
?>
<?php foreach($this->items as $type=>$publications): ?>
	<h3 class="frontendheader"><?php echo $type; ?></h3>
	<ul>

	<?php 
		foreach($publications as $pub):
			$styleObj = JResearchCitationStyleFactory::getInstance($this->style, $pub->pubtype);
			$publicationText = $styleObj->getReferenceHTMLText($pub, true);
	?>

		<?php $url = $pub->url; ?>	
		<?php $attach = $pub->getAttachment(0, 'publications'); ?>		
				
		<li><span><?php echo $publicationText;  ?></span>
			<?php if($this->showmore): ?>
			<span><?php echo JHTML::_('jresearchfrontend.link', JText::_('JRESEARCH_MORE'), 'publication', 'show', $pub->id); ?></span>
		<?php endif; ?>
		<?php if($this->showDigital){
				  if(!empty($url)){
					$link = str_replace('&', '&amp;', $url);
	                $digitalVersion = JText::_('JRESEARCH_ONLINE_VERSION');
	               	echo "<span><a href=\"$link\">[$digitalVersion]</a></span>";
	             }
			}
			if($this->showFulltext){
	             if(!empty($attach)){
	                $digitalVersion = JText::_('JRESEARCH_FULLTEXT');
	                echo "<span><a href=\"$attach\">[$digitalVersion]</a></span>";
	             }	
			} 
		?>
		<?php if($this->showBibtex): 
			echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=bibtex&amp;id='.$pub->id, '[Bibtex]').'</span>';		
		 endif;?>	
		<?php if($this->showRIS): 
			echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=ris&amp;id='.$pub->id, '[RIS]').'</span>';		
		 endif;?>
		 <?php if($this->showMODS): 
			echo '<span>'.JHTML::_('link', 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=mods&amp;id='.$pub->id, '[MODS]').'</span>';		
		 endif;?>
		 <?php 		
			$canDo = JResearchAccessHelper::getActions('publication', $pub->id);
			if($canDo->get('core.publications.edit') || ($canDoPublications->get('core.publications.edit.own') && $pub->created_by == $user->get('id'))):	 
		 ?>	 	
		 	<span>	
				<?php echo JHTML::_('jresearchfrontend.icon','edit', 'publications', $pub->id); ?> 
			</span>
		 <?php endif; ?>
		<?php if($canDoPublications->get('core.publications.delete')): ?>
				<?php echo JHTML::_('jresearchfrontend.icon','remove', 'publications', $pub->id); ?>
		<?php endif; ?>	
		
		</li>
	<?php endforeach; ?>
	</ul>
<?php endforeach; ?>