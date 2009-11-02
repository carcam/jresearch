<?php 
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a new publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if(JHTML::_('Jresearch.authorize','add', 'publications'))
{
$params = $mainframe->getPageParameters('com_jresearch');
$bibtex = $params->get('enable_bibtex');
?>
	<h1><?php echo JText::_('JRESEARCH_NEW_PUBLICATION'); ?></h1>
	<form name="adminForm" id="adminForm" method="post" action="index.php">
		<table class="adminform">
			<tbody>
			<tr>
				<th style="width: 20%;"><?php echo JText::_('JRESEARCH_TYPE').': ' ?></th>
				<td>
					<?php echo $this->types; ?>
				</td>
			</tr>
			</tbody>
		</table>
		<div style="text-align: center;">
			<input name="submit" value="<?php echo JText::_('New'); ?>" type="submit" />
		</div>
		
		<?php echo JHTML::_('jresearchhtml.hiddenfields', 'publications', 'add'); ?>
		<?php $Itemid = JRequest::getVar('Itemid'); ?>
		<?php if(isset($Itemid)): ?>
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
		<?php endif; ?>		
		<input type="hidden" name="id" value="0" />
	</form>
	<?php //BEGIN PABLO MONCADA ?>
	<?php if($bibtex == "yes") {?>
	<h1><?php echo JText::_('JRESEARCH_NEW_PUBLICATION_BY_BIBTEX'); ?></h1>
	<form name="upload" enctype="multipart/form-data" method="post" >
	<table class="adminform">
			<tbody>
			<tr>
				<th style="width: 20%;"><?php echo JText::_('JRESEARCH_BIBTEX_FILE'); ?></th>
				<td width="80%">
			<input type="file" name="inputfile" />
			</td>
			</tr>
			<tr>
				<th style="width: 20%;"><?php echo JText::_('JRESEARCH_BIBTEX_LANGUAGE'); ?></th>
				<td width="80%">
			<textarea name="bibtex"></textarea> <br />
			</td>
			</tr>
			</tbody>
		</table>
		 <div style="text-align: center;">
			<input  name="submit" value="Importar" type="submit">
		</div>
	</form>
<?php
}else {}
//END PABLO MONCADA
}
else
{
?>
	<div style="clear: both;">&nbsp;</div>
	<div style="text-align:center;"><?php echo JText::_('JRESEARCH_ACCESS_NOT_ALLOWED')?></div>
<?php
}
?>