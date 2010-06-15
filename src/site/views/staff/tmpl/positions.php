<?php
/**
 * @package JResearch
 * @subpackage Staff
 * Default view for showing a list of staff members
 */

//@todo Change styling attributes for table to CSS
// no direct access
defined('_JEXEC') or die('Restricted access'); 
$itemId = JRequest::getVar('Itemid');
?>
<h1 class="componentheading"><?php echo JText::_('JRESEARCH_MEMBERS'); ?></h1>

    <?php
    $itemId = JRequest::getVar('Itemid');
	
    ?>
	<tbody>
	    <?php
		if(count($this->positions) > 0):
			foreach($this->positions as $position):
				if($position->published == 1):
				?>
					<h2 class="contentheading"><?php echo $position->position; ?></h2>
					<p>
					
				<?php		
					if(count($this->items) > 0):
					foreach($this->items as $member):
						if($member->position == $position->id):
						?>
						<a href="<?php echo JURI::base(); ?>index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?php echo $member->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo JResearchPublicationsHelper::formatAuthor($member->__toString(), $this->format); ?></a><br />
						
						<?php
						else:
							if(empty($member->position)):
								$empty = true;
							endif;
						endif;
					
					endforeach;
					endif;
					?></p>
					<?php
				endif;
			endforeach;
		endif;
		if($empty):
		?>
		<h2 class="contentheading"><?php echo JText::_('JRESEARCH_NOT_SPECIFIED'); ?></h2>
		<p>
		<?php
			if(count($this->items) > 0):
				foreach($this->items as $member):
					if(empty($member->position)):
						?>
						<a href="<?php echo JURI::base(); ?>index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;id=<?php echo $member->id; ?><?php echo isset($itemId)?'&amp;Itemid='.$itemId:''; ?>"><?php echo JResearchPublicationsHelper::formatAuthor($member->__toString(), $this->format); ?></a><br />
						<?php
					endif;			
				endforeach;
			endif;
		?></p>
		<?php
		endif;
					
					
		?>
