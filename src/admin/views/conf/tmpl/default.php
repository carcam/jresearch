<?php
/**
 * @package JResearch
 * @subpackage Configuration
 * @license		GNU/GPL
 * Default view for the control panel
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); 

jimport('joomla.html.pane');
$user = JFactory::getUser();
?>
<div class="span8 cpanel-left">
<div id="cpanel">
	<div class="row-fluid">
		<?php if($user->authorise('core.manage', 'com_jresearch')): ?>
		<div  class="icon btn span2">
                        <a href="index.php?option=com_jresearch&amp;controller=publications">
                                <img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/publications.png" alt="<?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?>" />
                                <span><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></span>
                        </a>
		</div>
		<div  class="icon btn span2">
                        <a href="index.php?option=com_jresearch&amp;controller=researchareas">
                                <img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/areas.png" alt="<?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?>" />
                                <span><?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?></span>
                        </a>
		</div>
		<div class="icon btn span2">
                        <a href="index.php?option=com_jresearch&amp;controller=staff">
                                <img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/staff.png" alt="<?php echo JText::_('JRESEARCH_STAFF'); ?>" />
                                <span><?php echo JText::_('JRESEARCH_STAFF'); ?></span>
                        </a>
		</div>
		<div class="icon btn span2">
                        <a href="index.php?option=com_jresearch&amp;controller=member_positions">
                                <img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/memberpositions.png" alt="<?php echo JText::_('JRESEARCH_MEMBER_POSITIONS'); ?>" />
                                <span><?php echo JText::_('JRESEARCH_MEMBER_POSITIONS'); ?></span>
                        </a>
		</div>
<!--		<div class="icon btn span2">
                        <a href="index.php?option=com_jresearch&amp;controller=projects">
                                <img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/projects.png" alt="<?php echo JText::_('JRESEARCH_PROJECTS'); ?>" />
                                <span><?php echo JText::_('JRESEARCH_PROJECTS'); ?></span>
                        </a>
		</div>    -->        
	</div>
	<div class="row-fluid">

		<?php endif; ?>
	</div>
</div>
</div>
<div class="span4 cpanel-right form-horizontal">
    <?php 
        echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'about')); 
        echo JHtml::_('bootstrap.addTab', 'myTab', 'about', JText::_('JRESEARCH_ABOUT', true));
    ?>
    <table class="adminlist">
        <tbody>
            <tr>
                    <td colspan="2" style="text-align:center;"><img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/jresearch_logo.png" alt="J!Research Logo" /></td>
            </tr>
            <tr>
                <th scope="col"><?php echo JText::_('JRESEARCH_SOFTWARE_VERSION').': '; ?></th>
                <td><?php echo _JRESEARCH_VERSION_; ?></td>
            </tr>
            <tr>
                <th scope="col"><?php echo JText::_('JRESEARCH_SOFTWARE_COPYRIGHT').': '; ?></th>
                <td>Copyright 2008, Luis Galárraga</td>
            </tr>
            <tr>
                <th scope="col"><?php echo JText::_('JRESEARCH_SOFTWARE_LICENSE').': '; ?></th>
                <td>GPL version 2.0</td>
            </tr>
            <tr>
                <th scope="col"><?php echo JText::_('JRESEARCH_BIBUTILS_VERSION').': '; ?></th>
                <td><?php echo _JRESEARCH_BIBUTILS_VERSION_; ?></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                        <form name="{5C088896-C4CC-4430-A6D8-9DC9D2BE379D}" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                            <input type="hidden" name="cmd" value="_s-xclick"/>
                            <input type="hidden" name="hosted_button_id" value="1143995"/>
                            <input type="image" border="0" src="http://joomla-research.com/images/donate.png" name="submit" alt=""/>
                            <img width="1" height="1" border="0" alt="" src="https://www.paypal.com/es_XC/i/scr/pixel.gif"/>
                        </form>
                </td>
            </tr>
        </tbody>	
    </table>
<?php 
        echo JHtml::_('bootstrap.endTab');
        echo JHtml::_('bootstrap.addTab', 'myTab', 'credits', JText::_('JRESEARCH_CREDITS', true));
?>				
<table class="adminlist">
    <tbody>
        <tr>
            <th scope="col"><?php echo JText::_('JRESEARCH_SOFTWARE_AUTHOR').': '; ?></th>
            <td><?php echo JHTML::_('link', 'http://luisgalarraga.de', 'Luis Galárraga')?></td>
        </tr>
        <tr>
            <th scope="col"><?php echo JText::_('JRESEARCH_SOFTWARE_MENTOR').': '; ?></th>
            <td>Nereyda Valentin-Macias (<?php echo JHTML::_('email.cloak', 'neri@valenciasconsulting.com')?>)</td>
        </tr>
        <tr>
            <th scope="col"><?php echo JText::_('JRESEARCH_MORE_CREDITS').': '; ?></th>
            <td>
                <ul class="creditslist">
                    <li>Florian Prinz (<?php echo JHTML::_('email.cloak', 'prinz.florian@chello.at')?>)</li>
                    <li>Cárlos Cámara (<?php echo JHTML::_('email.cloak', 'cm.camara@gmail.com')?>)</li>                    
                </ul>
            </td>
        </tr>
    </tbody>
</table>
<?php 
    echo JHtml::_('bootstrap.endTab');
    echo JHtml::_('bootstrap.endTabSet');
?>
</div>
