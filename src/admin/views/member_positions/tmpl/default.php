<?php
/**
 * @package JResearch
 * @subpackage Projects
 * @license GNU/GPL
 * Default list of member positions.
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); 
$saveOrder = ($this->lists['order'] == 'ordering');
$listOrder = @$this->lists['order'];
$listDirn =  @$this->lists['order_Dir'];
$actions = JResearchAccessHelper::getActions();
$canChange = $actions->get('core.manage');
?>

<?php
if ($saveOrder)
{
    $saveOrderingUrl = 'index.php?option=com_content&controller=member_positions&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'memberpositionsList', 'adminForm', strtolower(@$this->lists['order_Dir']), $saveOrderingUrl);
}
?>


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
                    <?php echo $this->lists['state'];?>
                    <?php echo $this->lists['limit']; ?>                    
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-striped" id="memberpositionsList">
        <thead>
        <tr>		
            <th style="width: 1%;" nowrap="center">
                <?php echo JHTML::_('grid.sort', 'X', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
            </th>                
            <th style="width: 5%;" class="center"><?php echo JHtml::_('grid.checkall'); ?></th>
            <th style="width: 50%;" class="title"><?php echo JHTML::_('grid.sort', JText::_('JRESEARCH_MEMBER_POSITION'), 'position', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>			
            <th style="width: 20%;" class="nowrap center"><?php echo JHTML::_('grid.sort', JText::_('JRESEARCH_PUBLISHED'), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
        </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: center;">
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
                ?>
                    <tr class="<?php echo "row$k"; ?>">
                        <td class="order nowrap center hidden-phone">
                                <?php
                                $iconClass = '';
                                if (!$canChange) {
                                    $iconClass = ' inactive';
                                } elseif (!$saveOrder) {
                                    $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
                                }
                                ?>
                                <span class="sortable-handler<?php echo $iconClass ?>">
                                        <i class="icon-menu"></i>
                                </span>
                                <?php if ($canChange && $saveOrder) : ?>
                                        <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $this->items[$i]->ordering; ?>" class="width-20 text-area-order " />
                                <?php endif; ?>
                        </td>
                        <td class="center"><?php echo $checked; ?></td>
                        <td><a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=member_positions&task=edit&cid[]='.$this->items[$i]->id); ?>"><?php echo $this->items[$i]->position.' ('.$this->items[$i]->ordering.')';  ?></a></td>
                        <td class="center"><?php echo $published; ?></td>
                    </tr>
                <?php endfor; ?>
        </tbody>
    </table>
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="" /> 

    <?php echo JHTML::_('jresearchhtml.hiddenfields', 'member_positions'); ?>
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
