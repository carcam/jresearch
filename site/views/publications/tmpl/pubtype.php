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
        <li>
            <span><?php echo $publicationText;  ?></span>
            <?php include('showmore.php'); ?>		
        </li>
    <?php endforeach; ?>
    </ul>
<?php endforeach; ?>