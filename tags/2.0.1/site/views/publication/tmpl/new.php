<?php 
/**
 * @package JResearch
 * @subpackage Publications
 * View for adding/editing a new publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$mainframe = JFactory::getApplication();
$params = $mainframe->getPageParameters('com_jresearch');
$bibtex = $params->get('enable_bibtex_frontend_import', 1);
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
	<?php if($bibtex) {?>
	<?php 
		jresearchimport('helpers.researchareas', 'jresearch.admin');
	    $researchAreas = JResearchResearchareasHelper::getResearchAreas();
    	$researchAreasOptions = array();
    	$formatsOptions = array();
    	
    	// Retrieve the list of research areas
    	foreach($researchAreas as $r){
            $researchAreasOptions[] = JHTML::_('select.option', $r->id, $r->name);
    	}    	
    	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'researchAreas[]', 'class="inputbox" id="researchAreas" multiple="multiple" size="5"');	
	?>
	<h1><?php echo JText::_('JRESEARCH_IMPORT_PUBLICATIONS'); ?></h1>
	<form name="upload" enctype="multipart/form-data" method="post" >
	<table class="adminform">
			<tbody>
			<tr>
				<th style="width: 20%;"><?php echo JText::_('JRESEARCH_BIBTEX_FILE'); ?></th>
				<td width="80%">
                	<input type="file" name="inputfile" />
                    <label for="maptostaff"><?php echo JText::_('JRESEARCH_MAP_TO_STAFF').': '; ?></label>
                    <input type="checkbox" name="maptostaff" id="maptostaff"  />
			</td>
			</tr>
			<tr>
				<th style="width: 20%;"><?php echo JText::_('JRESEARCH_BIBTEX_LANGUAGE'); ?></th>
				<td width="80%">
			<textarea name="bibtex"></textarea> <br />
			</td>
			</tr>
			<tr>
				<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': '; ?></th>
				<td><?php echo $researchAreasHTML; ?></td>
			</tr>			
			</tbody>
		</table>
		 <div style="text-align: center;">
			<input  name="submit" value="<?php echo JText::_('JRESEARCH_IMPORT');?>" type="submit">
		</div>		
		<?php if(isset($Itemid)): ?>
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
		<?php endif; ?>
		<input type="hidden" name="task" id="task" value="executeImport" />
		<input type="hidden" name="controller" id="controller" value="publications" />
		<input type="hidden" name="option" id="option" value="com_jresearch" />		
	</form>
	<?php } ?>