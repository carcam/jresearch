<?php
/**
 * @package JResearch
 * @subpackage Researchareas
 * @license	GNU/GPL
 * Default view for listing research areas
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

$saveOrder = ($this->lists['order'] == 'ordering');
$listOrder = @$this->lists['order'];
$listDirn =  @$this->lists['order_Dir'];
$actions = JResearchAccessHelper::getActions();
$canChange = $actions->get('core.researchareas.edit');

if ($saveOrder)
{
    $saveOrderingUrl = 'index.php?option=com_content&controller=researchareas&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'researchareasList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
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
                    <?php echo $this->lists['state']; ?>
                    <?php echo $this->lists['limit']; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-striped" id="researchareasList">
        <thead>
        <tr>
            <th width="1%" class="nowrap center hidden-phone">
                <?php echo JHtml::_('grid.sort', 'X', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
            </th>
            <th style="width: 1%;" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
            <th style="width: 58%;" class="title"><?php echo JHTML::_('grid.sort', JText::_('JRESEARCH_RESEARCH_AREA_NAME'), 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>            
            <th class="nowrap center" style="min-width: 55px;"><?php echo JHTML::_('grid.sort',  JText::_('JRESEARCH_PUBLISHED'), 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
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
                for($i = 0; $i < $n; $i++):
                    $k = $i % 2;
                    $checked = JHTML::_('grid.checkedout', $this->items[$i], $i ); 
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
                        <td>
                        <a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=researchareas&task=edit&cid[]='.$this->items[$i]->id); ?>">
                            <?php echo $this->items[$i]->name;  ?>
                        </a>						
                        <?php if(!empty($this->items[$i]->alias)): ?>
                        <p class="smallsub">
                                (<span><?php echo JText::_('JRESEARCH_ALIAS') ?></span>: <?php echo $this->items[$i]->alias; ?>)
                        </p>
                        <?php endif; ?>						
                        </td>
                        <td class="center"><?php echo $published; ?></td>
                    </tr>
                <?php
                endfor;

                if($n <= 0):
                ?>
                <tr>
                        <td colspan="4"></td>
                </tr>
                <?php 
                endif;
                ?>
        </tbody>
    </table>

    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" /> 

    <?php echo JHtml::_('jresearchhtml.hiddenfields', 'researchareas'); ?>
    <?php echo JHtml::_( 'form.token' ); ?>
</form>