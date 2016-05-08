<?php
/**
 * @package JResearch
 * @subpackage Publications
 * @license	GNU/GPL
 * Specific type view for conference publication
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $editor = trim($this->publication->editor);  ?>
<?php if(!empty($editor)): ?>
	<dt><?php echo JText::_('JRESEARCH_EDITOR').': ' ?></dt>		
	<dd property="bibo:property"><?php echo $editor; ?></dd>
<?php endif; ?>
<?php $volume = trim($this->publication->volume); ?>
<?php if(!empty($volume)): ?>
	<dt><?php echo JText::_('JRESEARCH_VOLUME').': ' ?></dt>
	<dd property="bibo:volume"><?php echo $volume ?></dd>
<?php endif; ?>
<?php $booktitle = trim($this->publication->booktitle);  ?>
<?php if(!empty($booktitle)): ?>
	<dt><?php echo JText::_('JRESEARCH_BOOKTITLE').': ' ?></dt>		
	<dd><?php echo $booktitle; ?></dd>
<?php endif; ?>
<?php $number = trim($this->publication->number); ?>
<?php if(!empty($number)): ?>
	<dt><?php echo JText::_('JRESEARCH_NUMBER').': ' ?></dt>
	<dd property="bibo:number"><?php echo $number ?></dd>
<?php endif; ?>
<?php $series = trim($this->publication->series);  ?>
<?php if(!empty($series)): ?>
	<dt><?php echo JText::_('JRESEARCH_SERIES').': ' ?></dt>		
	<dd><?php echo $series; ?></dd>
<?php endif; ?>
<?php $pages = str_replace('--', '-', trim($this->publication->pages)); ?>
<?php if(!empty($pages)): ?>
	<dt><?php echo JText::_('JRESEARCH_PAGES').': ' ?></dt>
	<dd property="bibo:pages"><?php echo $pages ?></dd>
<?php endif; ?>
<?php $address = trim($this->publication->address);  ?>
<?php if(!empty($address)): ?>
	<dt><?php echo JText::_('JRESEARCH_ADDRESS').': ' ?></dt>		
	<dd><?php echo $address; ?></dd>
<?php endif; ?>
<?php $publisher = trim($this->publication->publisher); ?>
<?php if(!empty($month)): ?>
	<dt><?php echo JText::_('JRESEARCH_PUBLISHER').': ' ?></dt>
	<dd property="bibo:publisher"><?php echo $publisher ?></dd>
<?php endif; ?>
<?php $organization = trim($this->publication->organization);  ?>
<?php if(!empty($organization)): ?>
	<dt><?php echo JText::_('JRESEARCH_ORGANIZATION').': ' ?></dt>		
	<dd><?php echo $organization; ?></dd>
<?php endif; ?>
<?php $month = trim($this->publication->month); ?>
<?php if(!empty($month)): ?>
	<dt><?php echo JText::_('JRESEARCH_MONTH').': ' ?></dt>
	<dd><?php echo JResearchPublicationsHelper::formatMonth($month); ?></dd>
<?php endif; ?>
<?php $isbn = trim($this->publication->isbn);  ?>
<?php if(!empty($isbn)): ?>
	<dt><?php echo JText::_('JRESEARCH_ISBN').': ' ?></dt>		
	<dd property="bibo:isbn"><?php echo $isbn; ?></dd>
<?php endif; ?>
<?php $issn = trim($this->publication->issn); ?>
<?php if(!empty($issn)): ?>
	<dt><?php echo JText::_('JRESEARCH_ISSN').': ' ?></dt>
	<dd property="bibo:issn"><?php echo JResearchPublicationsHelper::formatISSN($issn); ?></dd>
<?php endif; ?>
<?php echo isset($this->reference)?$this->reference:''; ?>