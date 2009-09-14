<form method="post" action="index.php" name="adminForm">

<fieldset><legend><?php echo JText::_('JRESEARCH_SEARCH_FOR');?></legend>
<div class="divTR">
	<div class="divTdl"><label for="key" ><?php echo JText::_('JRESEARCH_SEARCH_KEY').': ' ?></label></div>
	<div class="divTdl"><input name="key" size="15" value="" /></div>	
	<div class="divTdl"><label for="in_fields" ><?php echo JText::_('JRESEARCH_IN_FIELDS').': ' ?></label></div>
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.searchfieldslist', array('name'=>'keyfield0', 'attributes'=>'size="1"')); ?></div>
	<div class="divTdl">
	<input name="submit" type="submit" value="<?php echo JText::_('Go');?>">
	<input name="reset" type="reset" value="<?php echo JText::_('Clear');?>">
	</div>
	<div class="divEspacio"></div>
</div>
<div class="divTR">
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.operatorslist', array('name' => 'op1', 'attributes' => 'size="1"')); ?></div>
	<div class="divTdl divTdl2"><input name="key1" size="20" value="" /></div>	
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.searchfieldslist', array('name'=>'keyfield1', 'attributes'=>'size="1"')); ?></div>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.operatorslist', array('name' => 'op2', 'attributes' => 'size="1"')); ?></div>
	<div class="divTdl divTdl2"><input name="key2" size="20" value="" /></div>	
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.searchfieldslist', array('name'=>'keyfield2', 'attributes'=>'size="1"')); ?></div>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.operatorslist', array('name' => 'op3', 'attributes' => 'size="1"')); ?></div>
	<div class="divTdl divTdl2"><input name="key2" size="20" value="" /></div>	
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.searchfieldslist', array('name'=>'keyfield3', 'attributes'=>'size="1"')); ?></div>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<div class="divTdl"><label for="with_abstract"><?php echo JText::_('JRESEARCH_ITEMS_WITH_ABSTRACT');?></label></div>
	<div class="divTdl"> <input name="with_abstract" type="checkbox" value="" /></div>	
	<div class="divTdl"><?php echo JText::_('JRESEARCH_ADVANCED_SEARCH_INSTRUCTIONS'); ?></div>
	<div class="divEspacio"></div>	
</div>
</fieldset>
<fieldset>
<legend><?php echo JText::_('JRESEARCH_SEARCH_LIMITED_TO'); ?></legend>
<div class="divTR">
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.publicationstypeslist', 'pubtype', 'size="1"'); ?></div>	
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.languagelist', 'language', 'size="1"', 'id', 'name'); ?></div>
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.publicationstatuslist', array('name'=>'status', 'size="1"')); ?></div>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.publicationdatesearchlist', array('name'=>'date_field', 'size="1"')); ?></div>
	<div class="divTdl"><?php echo JText::_('JRESEARCH_FROM').': '?>
	<input maxlength="4" name="from_year" size="4" />/<input maxlength="2" name="from_month" size="2" />/<input maxlength="2" name="from_day" size="2" />
	 <?php echo JText::_('JRESEARCH_TO').': '?> 
	<input maxlength="4" name="to_year" size="4" />/<input maxlength="2" name="to_month" size="2" />/<input maxlength="2" name="to_day" size="2" />
	</div>	
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<div class="divTdl"><label for="recommended"><?php echo JText::_('JRESEARCH_SEARCH_ONLY_RECOMMENDED').': '?></label></div>	
	<div class="divTdl"> <input name="recommended" type="checkbox" value="" /></div>	
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
<?php echo JText::_('JRESEARCH_SEARCH_USE_FORMAT_YYYY_MM_DD');?>
</div>
</fieldset>
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_ORDER_BY')?></legend>
	<div class="divTR">
		<div class="divTdl"><?php echo JHTML::_('jresearchhtml.orderbysearchlist', array('name'=>'order_by1', 'size="1"')); ?></div>
		<div class="divTdl"><?php echo JHTML::_('jresearchhtml.orderbysearchlist', array('name'=>'order_by2', 'size="1"')); ?></div>		
		<div class="divEspacio"></div>		
	</div>
</fieldset>
</form>