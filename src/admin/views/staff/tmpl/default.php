<?php 
/**
 * @package JResearch
 * @subpackage Staff
 * @license	GNU/GPL
 * Default view of the staff
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$saveOrder = ($this->lists['order'] == 'ordering');
$listOrder = @$this->lists['order'];
$listDirn =  @$this->lists['order_Dir'];
$actions = JResearchAccessHelper::getActions();
$canChange = $actions->get('core.staff.edit');

if ($saveOrder)
{
    $saveOrderingUrl = 'index.php?option=com_content&controller=staff&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'staffList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
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
                    <?php echo $this->lists['area']; ?>
                    <?php echo $this->lists['former']; ?>
                    <?php echo $this->lists['state']; ?>
                    <?php echo $this->lists['limit']; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-striped" id="staffList" cellspacing="1">
        <thead>
        <tr>
            <th width="1%" class="nowrap center hidden-phone">
                <?php echo JHtml::_('grid.sort', 'X', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
            </th>            
            <th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
            <th style="width: 30%;" class="title"><?php echo JHTML::_('grid.sort',  'Name', 'lastname', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
            <th style="width: 1%; text-align: center;"><?php echo JHTML::_('grid.sort', JText::_('JRESEARCH_FORMER_MEMBER'), 'former_member', $this->lists['order_Dir'], $this->lists['order'] );?></th>
            <th style="width: 1%;" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'Published', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>			
            <th style="width: 10%; text-align: center;"><?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?></th>
            <th style="width: 10%; text-align: center;"><?php echo JText::_('JRESEARCH_POSITION'); ?></th>
            <th style="text-align: center;"><?php echo JText::_('JRESEARCH_CONTACT'); ?></th>
        </tr>
        </thead>		
        <tfoot>
            <tr>
                <td colspan="8" style="text-align: center;">
                    <?php echo $this->page->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
            <?php 
            $n = count($this->items);
            for($i=0; $i<$n; $i++):
                $k = $i % 2;
                $checked = JHTML::_('grid.checkedout', $this->items[$i], $i ); 
                $published = JHtml::_('jgrid.published', $this->items[$i]->published, $i);            
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
                    <td><a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=staff&task=edit&cid[]='.$this->items[$i]->id); ?>"><?php echo JResearchPublicationsHelper::formatAuthor($this->items[$i], $this->params->get('staff_format', 'last_first'));  ?></a></td>
                    <td class="center"><input type="checkbox" name="former_member" value="1" disabled="disabled" <?php echo (($this->items[$i]->former_member == 1) ? 'checked="checked"' : "")?> /></td>
                    <td class="center"><?php echo $published; ?></td>
                    <td class="center"><?php echo implode('; ', $this->items[$i]->getResearchAreas('names')); ?></td>
                    <td class="center"><?php echo $this->items[$i]->getPositionObj(); ?></td>
                    <td class="center"><a href="mailto:<?php echo $this->items[$i]->email; ?>"><?php echo $this->items[$i]->email ?></a></td>
                </tr>
            <?php
            endfor;

            if($n <= 0):
            ?>
            <tr>
                <td colspan="9"></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="" /> 
    <input type="hidden" name="hidemainmenu" value="" />

    <?php echo JHTML::_('jresearchhtml.hiddenfields', 'staff'); ?>
    <?php echo JHTML::_( 'form.token' ); ?>
</form>