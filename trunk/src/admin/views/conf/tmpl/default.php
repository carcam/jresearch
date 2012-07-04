<?php
/**
 * @package JResearch
 * @subpackage Configuration
 * Default view for the control panel
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); 

jimport('joomla.html.pane');
$user = JFactory::getUser();
?>
<div class="cpanel-left">
<div id="cpanel">
	<?php if($user->authorise('core.admin', 'com_jresearch')):  ?>
	<div class="icon-wrapper">
		<div class="icon">
			<a class="modal" rel="{handler: 'iframe', size: {x: 1024, y: 500}}" href="index.php?option=com_config&amp;tmpl=component&amp;component=com_jresearch&amp;view=component&amp;path=">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/config.png" alt="<?php echo JText::_('JRESEARCH_CONFIGURATION'); ?>" />
				<span><?php echo JText::_('JRESEARCH_CONFIGURATION'); ?></span>
			</a>
		</div>
	</div>
	<?php endif; ?>
	<?php if($user->authorise('core.manage', 'com_jresearch')): ?>
	<div  class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=publications">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/publications.png" alt="<?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?>" />
				<span><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></span>
			</a>
		</div>
	</div>
	<div  class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=researchareas">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/areas.png" alt="<?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?>" />
				<span><?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?></span></a>
		</div>
	</div>
	<div class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=staff">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/staff.png" alt="<?php echo JText::_('JRESEARCH_STAFF'); ?>" />
				<span><?php echo JText::_('JRESEARCH_STAFF'); ?></span>
			</a>
		</div>
	</div>
	<div class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=projects">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/projects.png" alt="<?php echo JText::_('JRESEARCH_PROJECTS'); ?>" />
				<span><?php echo JText::_('JRESEARCH_PROJECTS'); ?></span>
			</a>
		</div>
	</div>	
    <div  class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;mode=upgrader">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/versionupgrade.png" alt="<?php echo JText::_('JRESEARCH_UPGRADE_JRESEARCH'); ?>" />
				<span><?php echo JText::_('JRESEARCH_UPGRADE_JRESEARCH'); ?></span>
			</a>
		</div>
	</div>	
	<?php endif; ?>
	<div  class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;task=help">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/help-browser.png" alt="<?php echo JText::_('Help'); ?>" />
				<span><?php echo JText::_('Help'); ?></span>
			</a>
		</div>
	</div>
</div>
</div>
<div class="cpanel-right">
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
			<td>Luis Galárraga (<?php echo JHTML::_('email.cloak', 'shamantobi@gmail.com')?>) and Florian Prinz (<?php echo JHTML::_('email.cloak', 'prinz.florian@chello.at') ?>)</td>
		</tr>
		<tr>
			<th scope="col"><?php echo JText::_('JRESEARCH_SOFTWARE_MENTOR').': '; ?></th>
			<td>Nereyda Valentin-Macias, (<?php echo JHTML::_('email.cloak', 'neri@valenciasconsulting.com')?>)</td>
		</tr>
		<tr>
			<th scope="col"><?php echo JText::_('JRESEARCH_MORE_CREDITS').': '; ?></th>
			<td>
				<ul class="creditslist">
					<li><strong>Spanish Translation:</strong> Carlos Cámara Mora, (<?php echo JHTML::_('email.cloak', 'cmcamara@gmail.com')?>)</li>
					<li><strong>German Translation:</strong> Florian Prinz</li>
					<li><strong>Web site:</strong> Nereyda Valentin-Macias</li>
					<li><strong>sf404sef Integration:</strong> Carlos Cámara Mora</li>
					<li><strong>Several improvements 1.2 Beta: </strong>Pablo Moncada, (<?php echo JHTML::_('email.cloak', 'pmoncadaisla@gmail.com')?>)</li>
				</ul>
			</td>					
		</tr>																		
	</tbody>
</table>
<?php 
echo JHtml::_('sliders.end');
?>
</div>