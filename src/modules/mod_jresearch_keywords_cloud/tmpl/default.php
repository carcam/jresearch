<?php 

/**
* @package		JResearch
* @subpackage 	Modules
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access'); ?>
<div class="word-cloud" id="<?php echo $params->get('divid', 'word-cloud') ?>">
<?php echo $cloud->render(); ?>
</div>
</body>
