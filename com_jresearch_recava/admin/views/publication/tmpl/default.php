<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for adding/editing a single publication
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div style="text-align:center;"><h3><?php echo JText::_('JRESEARCH_'.strtoupper($this->pubtype).'_DEFINITION'); ?></h3></div>
<div class="acotacion"><?php echo $this->pubtype == 'recava_article'? JText::_('JRESEARCH_ARTICLE_RECAVA_ACOTACION') : ''; ?></div>
<form name="adminForm" id="adminForm" method="post" class="form-validate" onSubmit="return validate(this);">
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th class="formheader" colspan="4"><?php echo JText::_('JRESEARCH_BASIC')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_TITLE').': '?></td>
		<td colspan="3">
			<input name="title" id="title" size="60" maxlength="255" value="<?php echo $this->publication?$this->publication->title:'' ?>" class="required" />
			<br />
			<label for="title" class="labelform"><?php echo JText::_('JRESEARCH_REQUIRE_PUBLICATION_TITLE'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>		
		<td><?php echo $this->areasList; ?></td>
		<td><?php echo JText::_('JRESEARCH_YEAR').' :' ?></td>
		<td>
			<input maxlength="4" size="5" name="year" id="year" value="<?php echo $this->publication?$this->publication->year:'' ?>" class="validate-year required" />
			<br />
			<label for="year" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_YEAR'); ?></label>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_ABSTRACT').': ' ?></td>
		<td colspan="4"><textarea name="abstract" id="abstract" cols="70" rows="5" ><?php echo $this->publication?$this->publication->abstract:'' ?></textarea></td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_KEYWORDS').': ' ?></td>
		<td>
			<input name="keywords" id="keywords" size="20" maxlength="255" class="validate-keywords" value="<?php echo $this->publication?$this->publication->keywords:'' ?>" />&nbsp;&nbsp;<span class="information">&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_SEPARATED_BY_COMMAS'))?></span>
			<br />
			<label for="keywords" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_KEYWORDS'); ?></label>			
		</td>
		<td><?php echo JText::_('JRESEARCH_DIGITAL_VERSION').' (Url) : ' ?></td>
		<td>
			<input name="url" id="url" size="20" maxlength="255" class="validate-url" value="<?php echo $this->publication?$this->publication->url:'' ?>" />
			<br />
			<label for="url" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_VALID_URL'); ?></label>
		</td>		
	</tr>
	<tr>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
		<td><?php echo JText::_('JRESEARCH_INTERNAL').': ' ?></td>
		<td><?php echo $this->internalRadio; ?>&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_INTERNAL_TOOLTIP')) ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_CITEKEY').' (PMID): '?></td>
		<td>
			<input size="20" maxlength="255" name="citekey" id="citekey" class="required" value="<?php echo $this->publication?$this->publication->citekey:'' ?>" />&nbsp;&nbsp;<?php echo JHTML::_('tooltip', JText::_('JRESEARCH_CITEKEY_TOOLTIP')); ?>
			<br />
			<label for="citekey" class="labelform"><?php echo JText::_('JRESEARCH_PROVIDE_CITEKEY'); ?></label>
		</td>
		<td colspan="2"></td>
	</tr>
	<tr>
		<th class="editpublication" colspan="4"><?php echo JText::_('JRESEARCH_AUTHORS'); ?></th>	
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_AUTHORS').': '; ?></td>
		<td colspan="3"><?php echo $this->authors; ?></td>
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
		<td><?php echo JText::_('JRESEARCH_JOURNAL_IMPACT_FACTOR').': ' ?></td>
		<td>
			<?php if(isset($this->publication)): ?>
				<input type="text" value="<?php echo $this->publication->getImpactFactor(); ?>" size="10" name="impact_factor" <?php echo !empty($this->publication->id_journal)? 'readonly="readonly"': ''; ?> id="impact_factor" maxlength="10" class="validate-quantity" />
			<?php else: ?>
				<input type="text" size="10" name="impact_factor" id="impact_factor" maxlength="10" class="validate-quantity" />
			<?php endif; ?>	
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
<?php $Itemid = JRequest::getVar('Itemid'); ?>
<input type="hidden" name="Itemid" value="<?php echo !empty($Itemid)?$Itemid:''; ?>" />
<?php if(JRequest::getVar('modelkey')): ?>
	<input type="hidden" name="modelkey" value="<?php echo JRequest::getVar('modelkey'); ?>" />
<?php endif; ?>	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>