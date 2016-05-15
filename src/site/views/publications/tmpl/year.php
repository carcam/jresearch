<?php
/**
 * @package JResearch
 * @subpackage Publications
 * @license GNU/GPL 
 * View for showing the year of a publication
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<?php
    $digitalVersion = '';
    $user = JFactory::getUser();
    $canDoPublications = JResearchAccessHelper::getActions(); 
    foreach($this->items as $year=>$publications): ?>
        <h2 class="frontendheader"><?php echo $this->escape($year); ?></h2>
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
    <?php endforeach ?> 