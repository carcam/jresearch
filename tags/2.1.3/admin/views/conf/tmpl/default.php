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
		<?php if($user->authorise('core.admin', 'com_jresearch')):  ?>
			<div class="icon btn span2">
				<a class="modal" rel="{handler: 'iframe', size: {x: 1024, y: 500}}" href="index.php?option=com_config&amp;tmpl=component&amp;component=com_jresearch&amp;view=component">
					<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/config.png" alt="<?php echo JText::_('JRESEARCH_CONFIGURATION'); ?>" />
					<span><?php echo JText::_('JRESEARCH_CONFIGURATION'); ?></span>
				</a>
			</div>
		<?php endif; ?>
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
					<span><?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?></span></a>
		</div>
		<div class="icon btn span2">
				<a href="index.php?option=com_jresearch&amp;controller=staff">
					<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/staff.png" alt="<?php echo JText::_('JRESEARCH_STAFF'); ?>" />
					<span><?php echo JText::_('JRESEARCH_STAFF'); ?></span>
				</a>
		</div>
	</div>
	<div class="row-fluid">
		<div class="icon btn span2">
				<a href="index.php?option=com_jresearch&amp;controller=member_positions">
					<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/memberpositions.png" alt="<?php echo JText::_('JRESEARCH_MEMBER_POSITIONS'); ?>" />
					<span><?php echo JText::_('JRESEARCH_MEMBER_POSITIONS'); ?></span>
				</a>
		</div>
		<div class="icon btn span2">
				<a href="index.php?option=com_jresearch&amp;controller=projects">
					<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/projects.png" alt="<?php echo JText::_('JRESEARCH_PROJECTS'); ?>" />
					<span><?php echo JText::_('JRESEARCH_PROJECTS'); ?></span>
				</a>
		</div>
		<?php if($user->authorise('core.admin', 'com_jresearch')): ?>
		<div  class="icon btn span2">
				<a href="index.php?option=com_jresearch&amp;mode=upgrader">
					<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/versionupgrade.png" alt="<?php echo JText::_('JRESEARCH_UPGRADE_JRESEARCH'); ?>" />
					<span><?php echo JText::_('JRESEARCH_UPGRADE_JRESEARCH'); ?></span>
				</a>
		</div>
		<?php endif; ?>
		<?php endif; ?>
		<div  class="icon btn span2">
				<a href="index.php?option=com_jresearch&amp;task=help">
					<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/help-browser.png" alt="<?php echo JText::_('Help'); ?>" />
					<span><?php echo JText::_('Help'); ?></span>
				</a>
		</div>
	</div>
</div>
</div>
<div class="span4 cpanel-right">
<?php 
          echo JHtml::_('sliders.start','panel-sliders',array('useCookie'=>'1'));
          echo JHtml::_('sliders.panel', JText::_('JRESEARCH_ABOUT'), 'about');
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
          echo JHtml::_('sliders.panel', JText::_('JRESEARCH_CREDITS'), 'credits');
?>				
<table class="adminlist">
	<tbody>
		<tr>
			<th scope="col"><?php echo JText::_('JRESEARCH_SOFTWARE_AUTHOR').': '; ?></th>
			<td>Luis Galárraga <?php echo JText::_('JRESEARCH_AND'); ?> Florian Prinz </td>
		</tr>
		<tr>
			<th scope="col"><?php echo JText::_('JRESEARCH_SOFTWARE_MENTOR').': '; ?></th>
			<td>Nereyda Valentin-Macias, (<?php echo JHTML::_('email.cloak', 'neri@valenciasconsulting.com')?>)</td>
		</tr>
		<tr>
			<th scope="col"><?php echo JText::_('JRESEARCH_MORE_CREDITS').': '; ?></th>
			<td>
				<ul class="creditslist">
					<li><strong><?php echo JText::_('JRESEARCH_TRANSLATION_CREDITS'); ?>:</strong> <?php echo JText::_('JRESEARCH_TRANSLATOR_NAME'); ?></li>
					<li><strong><?php echo JText::_('JRESEARCH_WEBSITE_CREDITS'); ?>:</strong> Nereyda Valentin-Macias</li>
					<li><strong><?php echo JText::_('JRESEARCH_12IMPROVEMENTS_CREDITS'); ?>: </strong>Pablo Moncada</li>
				</ul>
			</td>					
		</tr>																		
	</tbody>
</table>
<?php 
echo JHtml::_('sliders.end');
?>
</div>
