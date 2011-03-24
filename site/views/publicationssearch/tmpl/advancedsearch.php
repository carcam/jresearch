<form method="post" action="index.php?option=com_jresearch&amp;view=publicationssearch&amp;task=startsearch" name="adminForm">
<?php global $mainframe; ?>
<fieldset><legend><?php echo JText::_('JRESEARCH_SEARCH_FOR');?></legend>
<div class="divTR">
	<div class="divTdl divTdl1"><label for="key" ><?php echo JText::_('JRESEARCH_SEARCH_KEY').': ' ?></label></div>
	<div class="divTdl"><input name="key" size="20" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchkey', 'key')); ?>" /></div>	
	<div class="divTdl divTdl3"><label for="in_fields" ><?php echo JText::_('JRESEARCH_IN_FIELDS').': ' ?></label></div>
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.searchfieldslist', array('name'=>'keyfield0', 'selected' => $mainframe->getUserStateFromRequest('publicationssearchkeyfield0', 'keyfield0'), 'attributes'=>'size="1"')); ?></div>
	<div class="divTdl">
	<input name="submit" type="submit" value="<?php echo JText::_('Go');?>">
	<input name="reset" type="reset" onclick="javascript:document.location.href='index.php?option=com_jresearch&controller=publications&task=advancedsearch&newSearch=1&Itemid=<?php echo JRequest::getInt('Itemid'); ?>';" value="<?php echo JText::_('Clear');?>">
	</div>
	<div class="divEspacio"></div>
</div>
<div class="divTR">
	<div class="divTdl divTdl1"><?php echo JHTML::_('jresearchhtml.operatorslist', array('name' => 'op1', 'selected' => $mainframe->getUserStateFromRequest('publicationssearchop1', 'op1'),  'attributes' => 'size="1"')); ?></div>
	<div class="divTdl divTdl2"><input name="key1" size="20" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchkey1', 'key1')); ?>" /></div>	
	<div class="divTdl divTdl3"></div>
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.searchfieldslist', array('name'=>'keyfield1', 'selected' => $mainframe->getUserStateFromRequest('publicationssearchkeyfield1', 'keyfield1') , 'attributes'=>'size="1"')); ?></div>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<div class="divTdl divTdl1"><?php echo JHTML::_('jresearchhtml.operatorslist', array('name' => 'op2', 'selected' => $mainframe->getUserStateFromRequest('publicationssearchop2', 'op2') ,'attributes' => 'size="1"')); ?></div>
	<div class="divTdl divTdl2"><input name="key2" size="20" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchkey2', 'key2')); ?>" /></div>	
	<div class="divTdl divTdl3"></div>
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.searchfieldslist', array('name'=>'keyfield2' , 'selected' => $mainframe->getUserStateFromRequest('publicationssearchkeyfield2', 'keyfield2'), 'attributes'=>'size="1"')); ?></div>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<div class="divTdl divTdl1"><?php echo JHTML::_('jresearchhtml.operatorslist', array('name' => 'op3' , 'selected' => $mainframe->getUserStateFromRequest('publicationssearchop3', 'op3'), 'attributes' => 'size="1"')); ?></div>
	<div class="divTdl divTdl2"><input name="key3" size="20" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchkey3', 'key3')); ?>" /></div>	
	<div class="divTdl divTdl3"></div>
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.searchfieldslist', array('name'=>'keyfield3', 'selected' => $mainframe->getUserStateFromRequest('publicationssearchkeyfield3', 'keyfield3'), 'attributes'=>'size="1"')); ?></div>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<div class="divTdl"><label for="with_abstract"><?php echo JText::_('JRESEARCH_ITEMS_WITH_ABSTRACT');?></label></div>
	<?php $with_abstract = $mainframe->getUserStateFromRequest('publicationssearchwith_abstract', 'with_abstract'); ?>
	<div class="divTdl"><input name="with_abstract" type="checkbox" id="with_abstract" <?php echo $with_abstract == 'on'? 'checked="checked"':''; ?> /></div>	
	<div class="divTdl"><?php echo JText::_('JRESEARCH_ADVANCED_SEARCH_INSTRUCTIONS'); ?></div>
	<div class="divEspacio"></div>	
</div>
</fieldset>
<fieldset>
<legend><?php echo JText::_('JRESEARCH_SEARCH_LIMITED_TO'); ?></legend>
<div class="divTR">
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.publicationsosteopathictypeslist', 'osteotype', 'size="1"', $mainframe->getUserStateFromRequest('publicationssearchosteotype', 'osteotype')); ?></div>	
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.languagelist', 'language', 'size="1"', 'id', 'name', $mainframe->getUserStateFromRequest('publicationssearchlanguage', 'language')); ?></div>
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.publicationstatuslist', array('name'=>'status', 'backend' => false ,'selected' => $mainframe->getUserStateFromRequest('publicationssearchkeystatus', 'status'),  'size="1"')); ?></div>
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
	<div class="divTdl"><?php echo JHTML::_('jresearchhtml.publicationdatesearchlist', array('name'=>'date_field', 'selected' => $mainframe->getUserStateFromRequest('publicationssearchdatefield', 'date_field') ,'size="1"')); ?></div>
	<div class="divTdl"><?php echo JText::_('JRESEARCH_FROM').': '?>
	<input maxlength="4" name="from_year" size="4" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchfrom_year', 'from_year')); ?>" />/<input maxlength="2" name="from_month" size="2" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchfrom_month', 'from_month')); ?>" />/<input maxlength="2" name="from_day" size="2" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchfrom_day', 'from_day')); ?>" />
	 <?php echo JText::_('JRESEARCH_TO').': '?> 
	<input maxlength="4" name="to_year" size="4" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchto_year', 'to_year')); ?>" />/<input maxlength="2" name="to_month" size="2" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchto_month', 'to_month')); ?>" />/<input maxlength="2" name="to_day" size="2" value="<?php echo htmlspecialchars($mainframe->getUserStateFromRequest('publicationssearchto_day', 'to_day')); ?>" />
	</div>	
	<div class="divEspacio"></div>		
</div>
<div class="divTR">
	<div class="divTdl"><label for="recommended"><?php echo JText::_('JRESEARCH_SEARCH_ONLY_RECOMMENDED').': '?></label></div>	
	<?php $recommended = $mainframe->getUserStateFromRequest('publicationssearchrecommended', 'recommended'); ?>
	<div class="divTdl"> <input name="recommended" type="checkbox" id="recommended" <?php echo $recommended == 'on'?'checked="checked"':'' ?> /></div>	
	<div class="divEspacio"></div>	
</div>
<div class="divTR">
<?php echo JText::_('JRESEARCH_SEARCH_USE_FORMAT_YYYY_MM_DD');?>
</div>
</fieldset>
<fieldset>
	<legend><?php echo JText::_('JRESEARCH_ORDER_BY')?></legend>
	<div class="divTR">
		<div class="divTdl"><?php echo JHTML::_('jresearchhtml.orderbysearchlist', array('name'=>'order_by1', 'selected' => $mainframe->getUserStateFromRequest('publicationssearchorder_by1', 'order_by1', 'author_name_ascending') ,'size="1"')); ?></div>
		<div class="divTdl"><?php echo JHTML::_('jresearchhtml.orderbysearchlist', array('name'=>'order_by2', 'selected' => $mainframe->getUserStateFromRequest('publicationssearchorder_by2', 'order_by2', 'date_descending')  ,'size="1"')); ?></div>		
		<div class="divEspacio"></div>		
	</div>
</fieldset>
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'publications', 'startsearch'); ?>
<div style="text-align:right;"><input name="submit" type="submit" value="<?php echo JText::_('Go');?>"></div>
<input type="hidden" name="limit" value="20" />
<input type="hidden" name="limitstart" value="0" />
</form>
