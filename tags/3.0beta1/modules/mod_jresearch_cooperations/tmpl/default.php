<?php // no direct access
/**
* @package		JResearch
* @subpackage 	Modules
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

$path_relative = JString::str_ireplace(JPATH_BASE, '', $dirname );
$path_relative = JPath::clean( $path_relative, '/');
$modpath = JURI::root(true) . $path_relative . '/';
$document = &JFactory::getDocument();

//Additional cooperations
if ($params->get('cssfile') != '')
{
	$cssfile = $params->get('cssfile');
	$cssfile = JURI::root(true).'/'.$cssfile;
	$document->addStyleSheet( $cssfile );
}

$countCoops = count($coops);

$itemId = JRequest::getVar('Itemid');
?>
<div id="cooperations" class="cooperations<?php echo $params->get('moduleclass_sfx'); ?>">
	<?php
	if($countCoops > 0)
	{
	?>
		<ul id="list-cooperations">
			<?php
			foreach($coops as $coop):
			?>
				<li class="coops-line">
					<?php echo $coop->name; ?>
				</li>
			<?php
			endforeach;
			?>
		</ul>
	<?php
	}
	else
	{
	?>
		<div style="text-align: center;">
			<?php echo JText::_('QUICKCOOPS_NORECORDSFOUND')?>
		</div>
	<?php
	}
	?>
</div>