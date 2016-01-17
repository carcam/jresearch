<?php
/**
 * @package JResearch
 * @subpackage Projects
 * @license	GNU/GPL
 * Default view for listing projects.
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php
$saveOrder = $this->lists['order'] == 'ordering';
$listOrder = @$this->lists['order'];
$listDirn =  @$this->lists['order_Dir'];
$actions = JResearchAccessHelper::getActions();
$canChange = $actions->get('core.projects.edit');
if ($saveOrder)
{
    $saveOrderingUrl = 'index.php?option=com_content&controller=projects&task=saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'projectsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
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
                    <?php echo $this->lists['year']; ?>
                    <?php echo $this->lists['state']?>
                    <?php echo $this->lists['authors']?>
                    <?php echo $this->lists['area']?>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-striped" id="projectsList">
        <thead>
            <tr>		
                <th style="width: 1%;" class="nowrap center hidden-phone">                
                    <?php echo JHtml::_('grid.sort', 'X', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                </th>
                <th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
                <th style="width: 30%;" class="title"><?php echo JHTML::_('grid.sort', JText::_('JRESEARCH_TITLE'), 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                <th style="width: 1%;" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'Published', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>                						
                <th style="text-align: center; width: 15%;"><?php echo JText::_('JRESEARCH_PROJECT_LEADERS'); ?></th>
                <th style="text-align: center; width: 15%;"><?php echo JText::_('JRESEARCH_MEMBERS'); ?></th>
                <th style="text-align: center; width: 10%;"><?php echo JText::_('JRESEARCH_START_DATE'); ?></th>               
                <th style="text-align: center; width: 10%;"><?php echo JText::_('JRESEARCH_END_DATE'); ?></th>                
                <th style="text-align: center; width: 5%;"><?php echo JText::_('JRESEARCH_HITS'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="10">
                    <?php echo $this->page->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
        <?php 
            $n = count($this->items);
            for($i=0; $i<$n; $i++) :
                $text = '';
                $k = $i % 2;
                $checked = JHTML::_('grid.checkedout', $this->items[$i], $i ); 
                $published = JHTML::_('grid.published', $this->items[$i], $i );
                $members = $this->items[$i]->getMembers();
                $authorsText = JResearchPublicationsHelper::formatAuthorsArray($members, $this->params->get('staff_format', 'last_first'));
                $leaders = $this->items[$i]->getLeaders();
                $leadersText = JResearchPublicationsHelper::formatAuthorsArray($leaders, $this->params->get('staff_format', 'last_first'));
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
                <td><?php echo $checked; ?></td>
                <td><a href="<?php echo JFilterOutput::ampReplace('index.php?option=com_jresearch&controller=projects&task=edit&cid[]='.$this->items[$i]->id); ?>"><?php echo $this->items[$i]->title;  ?></a></td>
                <td class="center"><?php echo $published; ?></td>
                <td class="center"><?php echo $authorsText; ?></td>
                <td class="center"><?php echo $leadersText; ?></td>
                <td class="center"><?php echo $this->items[$i]->getStartDate(); ?></td>
                <td class="center"><?php echo $this->items[$i]->getEndDate(); ?></td>                
                <td class="center"><?php echo $this->items[$i]->hits ;?></td>
            </tr>
            <?php endfor; ?>

            <?php if($n <= 0): ?>
            <tr>
                <td colspan="9"></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="" /> 

    <?php echo JHTML::_('jresearchhtml.hiddenfields', 'projects'); ?>
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
