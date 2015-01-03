<?php
/**
 * @package JResearch
 * @subpackage Publications
 * @license GNU/GPL 
 * View for showing the type of a publication
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

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
        <li><span><?php echo $publicationText;  ?></span>
        <?php if($this->showmore): ?>
            <span><?php echo JHTML::_('jresearchfrontend.link', JText::_('JRESEARCH_MORE'), 'publication', 'show', $pub->id); ?></span>
        <?php endif; ?>
    <?php 
            $attachments = array();
            if($this->showDigital) {
                $digitalVersion = $pub->getAttachment($this->digitalVersionTag);
                if ($digitalVersion != null) {
                    $attachments[] = $digitalVersion;
                }
            }

            if($this->showFulltext){
                $fullText = $pub->getAttachment($this->fullTextTag);
                if ($fullText != null) {
                    $attachments[] = $fullText;
                }
            } 

            if($this->showBibtex) {
                $entry = array();
                $entry['url'] = 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=bibtex&amp;id='.$pub->id;
                $entry['tag'] = '[Bibtex]';
                $attachments[] = $entry;
            }

            if ($this->showRIS) {
                $entry = array();
                $entry['url'] = 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=ris&amp;id='.$pub->id;
                $entry['tag'] = '[RIS]';
                $attachments[] = $entry;
            }

            if ($this->showMODS) {
                $entry = array();
                $entry['url'] = 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=mods&amp;id='.$pub->id;
                $entry['tag'] = '[MODS]';
                $attachments[] = $entry;                    
            }

            echo JHTML::_('jresearchfrontend.attachments', $attachments, 'horizontal');                
	?>                        
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