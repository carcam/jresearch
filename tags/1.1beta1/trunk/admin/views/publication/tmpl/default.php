<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for adding/editing a single publication
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div style="text-align:center;"><h3><?php echo JText::_('JRESEARCH_'.strtoupper($this->pubtype).'_DEFINITION'); ?></h3></div>
<form name="adminForm" id="adminForm" method="post" class="form-validate" onSubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_BASIC')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('Title').': '?></td>
		<td colspan="3">
			<input name="title" id="title" size="80" maxlength="255" value="<?php echo $this->publication?$this->publication->title:'' ?>" class="required" />
			<br />
			<label for="title" class="labelform"><?php echo JText::_('JRESEARCH_REQUIRE_PUBLICATION_TITLE'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>		
		<td><?php echo $this->areasList; ?></td>
		<td><?php echo JText::_('JRESEARCH_YEAR').' :' ?></td>
		<td>
			<input maxlength="4" size="5" name="year" id="year" value="<?php echo $this->publication?$this->publication->year:'' ?>" class="validate-year" />
			<br />
			<label for="year" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_YEAR'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_NOTE').': ' ?></td>
		<td><textarea name="note" id="note" cols="30" rows="5" ><?php echo $this->publication?$this->publication->note:'' ?></textarea>&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_NOTE_TOOLTIP')); ?></td>
		<td><?php echo JText::_('JRESEARCH_ABSTRACT').': ' ?></td>
		<td><textarea name="abstract" id="abstract" cols="30" rows="5" ><?php echo $this->publication?$this->publication->abstract:'' ?></textarea></td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_KEYWORDS').': ' ?></td>
		<td>
			<input name="keywords" id="keywords" size="30" maxlength="255" class="validate-keywords" value="<?php echo $this->publication?$this->publication->keywords:'' ?>" />&nbsp;&nbsp;<span class="information">&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS'))?></span>
			<br />
			<label for="keywords" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_KEYWORDS'); ?></label>			
		</td>
		<td><?php echo JText::_('JRESEARCH_DIGITAL_VERSION').' (Url) : ' ?></td>
		<td>
			<input name="url" id="url" size="30" maxlength="255" class="validate-url" value="<?php echo $this->publication?$this->publication->url:'' ?>" />
			<br />
			<label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>
		</td>		
	</tr>
	<tr>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
		<td><?php echo JText::_('JRESEARCH_AUTHORS').': '; ?></td>
		<td><?php echo $this->authors; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_CITEKEY').': '?></td>
		<td>
			<input size="20" maxlength="255" name="citekey" id="citekey" class="required" value="<?php echo $this->publication?$this->publication->citekey:'' ?>" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_CITEKEY_TOOLTIP')); ?>
			<br />
			<label for="citekey" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_CITEKEY'); ?></label>
		</td>
		<td><?php echo JText::_('JRESEARCH_INTERNAL').': ' ?></td>
		<td><?php echo $this->internalRadio; ?>&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_INTERNAL_TOOLTIP')) ?></td>
	</tr>
	<tr>
		<th class="editpublication" colspan="4"><?php echo JText::_('JRESEARCH_SPECIFIC'); ?></th>
	</tr>
		<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'publication'.DS.'tmpl'.DS.$this->pubtype.'.php'); ?>
	<tr>
		<th class="editpublication" colspan="4"><?php echo JText::_('Extra'); ?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_COMMENTS').': '?></td>
		<td><textarea cols="30" rows="5" name="comments" id="comments"><?php echo $this->publication?$this->publication->comments:''  ?></textarea>&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_TOOLTIP_COMMENTS')); ?></td>
		<td><?php echo JText::_('JRESEARCH_JOURNAL_ACCEPTANCE_RATE').': '?></td>
		<td>
			<input value="<?php echo $this->publication?$this->publication->journal_acceptance_rate:'' ?>" size="10" name="journal_acceptance_rate" id="journal_acceptance_rate" maxlength="5" class="validate-numeric" />
			<br />
			<label for="journal_acceptance_rate" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_NUMBER'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_AWARDS').': '?></td>
		<td><textarea cols="30" rows="5" name="awards" id="awards"  ><?php echo $this->publication?$this->publication->awards:''; ?></textarea></td>
		<td><?php echo JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR').': ' ?></td>
		<td>	
			<input value="<?php echo $this->impact_factor?$this->publication->impact_factor:'' ?>" size="10" name="impact_factor" id="impact_factor" maxlength="8" class="validate-numeric" />
			<br />
			<label for="impact_factor" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_NUMBER'); ?></label>
		</td>
	</tr>

</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="publications" />
<input type="hidden" name="pubtype" value="<?php echo $this->pubtype; ?>" />
<input type="hidden" name="id" value="<?php echo $this->publication?$this->publication->id:'' ?>" />	
<?php echo JHTML::_('behavior.keepalive'); ?>
</form>