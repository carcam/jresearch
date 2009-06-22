<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for adding/editing a single publication
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div style="text-align:center;"><h3><?php echo JText::_('JRESEARCH_'.strtoupper($this->pubtype).'_DEFINITION'); ?></h3></div>
<form name="adminForm" id="adminForm" enctype="multipart/form-data" action="./" method="post" class="form-validate" onsubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th class="title" colspan="4"><?php echo JText::_('JRESEARCH_BASIC')?></th>
	</tr>
	<tr>
		<th><?php echo JText::_('Title').': '?></th>
		<td colspan="3">
			<input name="title" id="title" size="60" maxlength="255" value="<?php echo $this->publication?$this->publication->title:'' ?>" class="required" />
			<br />
			<label for="title" class="labelform"><?php echo JText::_('JRESEARCH_REQUIRE_PUBLICATION_TITLE'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></th>		
		<td><?php echo $this->areasList; ?></td>
		<th><?php echo JText::_('JRESEARCH_YEAR').' :' ?></th>
		<td>
			<input maxlength="4" size="5" name="year" id="year" value="<?php echo $this->publication?$this->publication->year:'' ?>" class="validate-year" />
			<br />
			<label for="year" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_YEAR'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_NOTE').': ' ?></th>
		<td><textarea name="note" id="note" cols="20" rows="5" ><?php echo $this->publication?$this->publication->note:'' ?></textarea>&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_NOTE_TOOLTIP')); ?></td>
		<th><?php echo JText::_('JRESEARCH_ABSTRACT').': ' ?></th>
		<td><textarea name="abstract" id="abstract" cols="20" rows="5" ><?php echo $this->publication?$this->publication->abstract:'' ?></textarea></td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_KEYWORDS').': ' ?></th>
		<td>
			<input name="keywords" id="keywords" size="30" maxlength="255" class="validate-keywords" value="<?php echo $this->publication?$this->publication->keywords:'' ?>" />&nbsp;&nbsp;<span class="information">&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS'))?></span>
			<br />
			<label for="keywords" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_KEYWORDS'); ?></label>			
		</td>
		<th><?php echo JText::_('JRESEARCH_DIGITAL_VERSION').' (Url) : ' ?></th>
		<td>
			<input name="url" id="url" size="30" maxlength="255" class="validate-url" value="<?php echo $this->publication?$this->publication->url:'' ?>" />
			<br />
			<label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('Published').': '; ?></th>
		<td><?php echo $this->publishedRadio; ?></td>
		<th><?php echo JText::_('JRESEARCH_FILE').': '; ?></th>
		<td><?php echo $this->files; ?>&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_PUBLICATION_FILES_TOOLTIP')); ?></td>

	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_CITEKEY').': '?></th>
		<td>
			<input size="20" maxlength="255" name="citekey" id="citekey" class="required" value="<?php echo $this->publication?$this->publication->citekey:'' ?>" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_CITEKEY_TOOLTIP')); ?>
			<br />
			<label for="citekey" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_CITEKEY'); ?></label>
		</td>
		<th><?php echo JText::_('JRESEARCH_INTERNAL').': ' ?></th>
		<td><?php echo $this->internalRadio; ?>&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_INTERNAL_TOOLTIP')) ?></td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_DOI').': '?></th>
		<td>
			<input size="20" maxlength="255" name="doi" id="doi" class="validate-doi" value="<?php echo $this->publication?$this->publication->doi:'' ?>" />
			<br />
			<label for="doi" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_DOI'); ?></label>
		</td>
		<th><?php echo JText::_('JRESEARCH_AUTHORS').': '; ?></th>
		<td><?php echo $this->authors; ?></td>
	</tr>
	<?php if(!empty($this->publication)): ?>
	<tr>
		<th><?php echo JText::_('Hits').': '?></th>
		<td><?php echo $this->publication->hits;  ?><div><label for="resethits"><?php echo JText::_('Reset').': '; ?></label><input type="checkbox" name="resethits" id="resethits" /></div></td>
		<td></td>
		<td></td>
	</tr>
	<?php endif; ?>
	<tr>
		<th class="title" colspan="4"><?php echo JText::_('JRESEARCH_SPECIFIC'); ?></th>
	</tr>
		<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'publication'.DS.'tmpl'.DS.$this->pubtype.'.php'); ?>
	<tr>
		<th class="title" colspan="4"><?php echo JText::_('Extra'); ?></th>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_COMMENTS').': '?></th>
		<td><textarea cols="20" rows="5" name="comments" id="comments"><?php echo $this->publication?$this->publication->comments:''  ?></textarea>&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_TOOLTIP_COMMENTS')); ?></td>
		<th><?php echo JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE').': '?></th>
		<td>
			<input value="<?php echo $this->publication?$this->publication->journal_acceptance_rate:'' ?>" size="10" name="journal_acceptance_rate" id="journal_acceptance_rate" maxlength="5" class="validate-numeric" />
			<br />
			<label for="journal_acceptance_rate" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_NUMBER'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('JRESEARCH_AWARDS').': '?></th>
		<td><textarea cols="20" rows="5" name="awards" id="awards"  ><?php echo $this->publication?$this->publication->awards:''; ?></textarea></td>
		<th><?php echo JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR').': ' ?></th>
		<td>	
			<input value="<?php echo $this->impact_factor?$this->publication->impact_factor:'' ?>" size="10" name="impact_factor" id="impact_factor" maxlength="8" class="validate-numeric" />
			<br />
			<label for="impact_factor" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_NUMBER'); ?></label>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo JText::_('JRESEARCH_COVER').': '?>
		</th>
		<td colspan="3">
			<input name="cover" id="cover" size="30" maxlength="255" class="validate-url" value="<?php echo $this->publication?$this->publication->cover:'' ?>" />
			<br />
			<label for="cover" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>
		</td>
	</tr>
</tbody>
</table>

<input type="hidden" name="pubtype" value="<?php echo $this->pubtype; ?>" />
<input type="hidden" name="id" value="<?php echo $this->publication?$this->publication->id:'' ?>" />
<?php if(JRequest::getVar('modelkey')): ?>
	<input type="hidden" name="modelkey" value="<?php echo JRequest::getVar('modelkey'); ?>" />
<?php endif; ?>
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'publications'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
