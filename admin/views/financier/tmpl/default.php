<?php
/**
 * @package JResearch
 * @subpackage Financiers
 * Default view for adding/editing a single financier
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="divForm">
<form name="adminForm" id="adminForm" action="index.php" method="post" class="form-validate" onsubmit="return validate(this);"  >
<div class="divTable">
<fieldset>
<legend><?php echo JText::_('JRESEARCH_FINANCIER');?></legend>
<div class="divTR">
		<div class="divTd"><label for="name"><?php echo JText::_('Name').': '?></label></div>
		<div class="divTdl">
			<input name="name" id="name" size="50" maxlength="255" value="<?php echo $this->financier?$this->financier->name:'' ?>" class="required" />
			<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'name', JText::_('JRESEARCH_FINANCIER_PROVIDE_VALID_NAME')); ?>
		</div>
	    <div class="divEspacio" ></div>								
</div>
<div class="divTR">
	<div class="divTd"><label for="published"><?php echo JText::_('Published').': '; ?></label></div>
	<div class="divTdl"><?php echo $this->publishedRadio; ?></div>
	    <div class="divEspacio" ></div>	
</div>	
<div class="divTR">
	<div class="divTd"><label for="url"><?php echo JText::_('URL').': '?></label></div>
	<div class="divTdl">
		<input name="url" id="url" size="50" maxlength="255" value="<?php echo $this->financier?$this->financier->url:'' ?>" class="validate-url" />
		<?php echo JHTML::_('jresearchhtml.formWarningMessage', 'url', JText::_('JRESEARCH_FINANCIER_PROVIDE_VALID_URL')); ?>
	</div>
</div>
</fieldset>
</div>
<input type="hidden" name="id" value="<?php echo $this->financier?$this->financier->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'financiers'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>
</div>