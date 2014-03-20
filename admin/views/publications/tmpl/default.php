<?php
/**
 * @package JResearch
 * @subpackage Publications
 * Default view for listing publications
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" method="post" id="adminForm" action="index.php?option=com_jresearch">
	<table>
		<tbody>
			<tr>
				<td style="text-align:left; width:100%;"><?php echo JText::_('Filter'); ?>
					<input type="text" name="filter_search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" onchange="document.adminForm.submit();" />
					<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
					<button onclick="document.adminForm.filter_search.value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
				</td>
				<td nowrap="nowrap">
					<?php echo $this->lists['authors'] ?>
					<?php echo $this->lists['year']; ?>
					<?php echo $this->lists['state'];?>
					<?php echo $this->lists['pubtype']; ?>
					<?php echo $this->lists['area']; ?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="adminlist" cellspacing="1">
		<thead>
		<tr>		
			<th style="width: 1%;">#</th>
			<th style="width: 1%; text-align: center;"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th style="width: 30%;" class="title"><?php echo JHTML::_('grid.sort',  JText::_('JRESEARCH_TITLE'), 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th style="width: 1%;" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   JText::_('Published'), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th style="width: 1%;" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   JText::_('Internal'), 'internal', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>			
			<th style="width: 22%;"><?php echo JText::_('JRESEARCH_AUTHORS'); ?></th>
			<th style="width: 5%;"><?php echo JHTML::_('grid.sort',   JText::_('JRESEARCH_YEAR'), 'year', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th style="width: 10%;"><?php echo JHTML::_('grid.sort',   JText::_('JRESEARCH_CITEKEY'), 'citekey', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th style="width: 5%;"><?php echo JHTML::_('grid.sort',   JText::_('JRESEARCH_TYPE'), 'pubtype', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th style="width: 5%;"><?php echo JText::_('Hits'); ?></th>
			<th style="width: 10%;"><?php echo JText::_('JRESEARCH_EXPORT'); ?></th>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="12">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
			<?php 
			$n = count($this->items);
			for($i=0; $i<$n; $i++):
					$k = $i % 2;
					$checked 	= JHTML::_('grid.checkedout', $this->items[$i], $i ); 
					$published  = JHTML::_('grid.published', $this->items[$i], $i );
					$authors = $this->items[$i]->getAuthors();
          			$text = JResearchPublicationsHelper::formatAuthorsArray($authors, $this->params->get('staff_format', 'last_first'));
					// Links for exporting
					$exportLinks = array();
					$exportLinks[] = JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportSingle&format=bibtex&cid[]='.$this->items[$i]->id), 'Bibtex');	
					$exportLinks[] = JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportSingle&format=mods&cid[]='.$this->items[$i]->id), 'MODS');	
					$exportLinks[] = JHTML::_('link', JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=exportSingle&format=ris&cid[]='.$this->items[$i]->id), 'RIS');
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->page->getRowOffset( $i ); ?></td>
					<td><?php echo $checked; ?></td>
					<td>
					<a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$this->items[$i]->id.'&pubtype='.$this->items[$i]->pubtype); ?>"><?php echo $this->items[$i]->title;  ?></a>
					<?php if(!empty($this->items[$i]->alias)): ?>
					<p class="smallsub">
						(<span><?php echo JText::_('JRESEARCH_ALIAS') ?></span>: <?php echo $this->items[$i]->alias; ?>)
					</p>
					<?php endif; ?>
					</td>
					<td class="center"><?php echo $published; ?></td>
					<td class="center">
						<?php
							$app =& JFactory::getApplication();
							$templateDir = JURI::base().'templates/'.$app->getTemplate().'/images/admin';							
						?>
						<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','toggle_internal')" title="<?php echo ( $this->items[$i]->internal ) ? JText::_( 'Yes' ) : JText::_( 'No' );?>">
						<img src="<?php echo $templateDir; ?>/<?php echo ( $this->items[$i]->internal ) ? 'tick.png' : 'publish_x.png'; ?>" width="16" height="16" border="0" alt="<?php echo ( $this->items[$i]->internal ) ? JText::_( 'Yes' ) : JText::_( 'No' );?>" /></a>
					</td>					
					<td class="center"><?php echo $text; ?></td>
					<td class="center"><?php echo $this->items[$i]->year; ?></td>
					<td class="center"><?php echo $this->items[$i]->citekey; ?></td>
					<td class="center"><?php echo JText::_('JRESEARCH_'.strtoupper($this->items[$i]->pubtype)); ?></td>
					<td class="center"><?php echo $this->items[$i]->hits; ?></td>
					<td class="center"><?php echo implode(' , ', $exportLinks); ?></td>
				</tr>
			<?php
			endfor;
			
			if($n <= 0):
			?>
			<tr>
				<td colspan="11"></td>
			</tr>
			<?php 
			endif;
			?>
		</tbody>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir'] ?>" /> 
	
	<?php echo JHTML::_('jresearchhtml.hiddenfields', 'publications'); ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
