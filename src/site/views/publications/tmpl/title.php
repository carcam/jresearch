<?php
/**
 * @package JResearch
 * @subpackage Publications
 * @license GNU/GPL 
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<ul>
<?php $digitalVersion = ''; 
	$user = JFactory::getUser();
	$canDoPublications = JResearchAccessHelper::getActions(); 	
?>
<?php
    foreach($this->items as $pub): 
	$styleObj = JResearchCitationStyleFactory::getInstance($this->style, $pub->pubtype);
	$publicationText = $styleObj->getReferenceHTMLText($pub, true, true);
?>
    <li>
        <span><?php echo $publicationText;  ?></span>
        <?php include('showmore.php'); ?>
    </li>
<?php endforeach; ?>
</ul>