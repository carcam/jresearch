<?php
/**
 * @package JResearch
 * @subpackage Configuration
 * Default view for the control panel
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<table class="adminform">
	<tbody>
	<tr>
	<td valign="top" width="55%">
		<div id="cpanel">
			<div style="float: left;">
				<div class="icon">
					<a class="modal" rel="{handler: 'iframe', size: {x: 750, y: 500}}" href="index.php?option=com_config&controller=component&component=com_jresearch&path=">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/config.png" alt="<?php echo JText::_('JRESEARCH_CONFIGURATION'); ?>">					
						<span><?php echo JText::_('JRESEARCH_CONFIGURATION'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=cooperations">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/cooperations.png" alt="<?php echo JText::_('JRESEARCH_COOPERATIONS'); ?>">					
						<span><?php echo JText::_('JRESEARCH_COOPERATIONS'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=facilities">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/facilities.png" alt="<?php echo JText::_('JRESEARCH_FACILITIES'); ?>">					
						<span><?php echo JText::_('JRESEARCH_FACILITIES'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=financiers">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/financier.png" alt="<?php echo JText::_('JRESEARCH_FINANCIERS'); ?>">					
						<span><?php echo JText::_('JRESEARCH_FINANCIERS'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=projects">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/projects.png" alt="<?php echo JText::_('JRESEARCH_PROJECTS'); ?>">					
						<span><?php echo JText::_('JRESEARCH_PROJECTS'); ?></span>
					</a>
				</div>
			</div>
				<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=publications">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/publications.png" alt="<?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?>">					
						<span><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=journals">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/areas.png" alt="<?php echo JText::_('JRESEARCH_JOURNALS'); ?>">					
						<span><?php echo JText::_('JRESEARCH_JOURNALS'); ?></span></a>
				</div>
			</div>			
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=researchAreas">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/areas.png" alt="<?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?>">					
						<span><?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?></span></a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=staff">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/staff.png" alt="<?php echo JText::_('JRESEARCH_STAFF'); ?>">					
						<span><?php echo JText::_('JRESEARCH_STAFF'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=teams">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/teams.png" alt="<?php echo JText::_('JRESEARCH_TEAMS'); ?>">					
						<span><?php echo JText::_('JRESEARCH_TEAMS'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;controller=theses">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/theses.png" alt="<?php echo JText::_('JRESEARCH_THESES'); ?>">					
						<span><?php echo JText::_('JRESEARCH_THESES'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_jresearch&amp;task=help">
						<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/help-browser.png" alt="<?php echo JText::_('Help'); ?>">					
						<span><?php echo JText::_('Help'); ?></span>
					</a>
				</div>
			</div>
		</div>	
	</td>
	<td valign="top" width="45%">
		<div id="content-pane" class="pane-sliders">
			<div class="panel">
				<h3 class="title jpane-toggler-down" id="cpanel-panel-logged"><span><?php echo JText::_('JRESEARCH_CREDITS'); ?></span></h3>
				<div style="border-top: medium none; border-bottom: medium none; overflow: hidden; padding-top: 0px; padding-bottom: 0px; height: 290px;" class="jpane-slider content">
				<table class="adminlist">
					<tbody>
						<tr>
							<td colspan="2" style="text-align:center;"><img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/jresearch_logo.png" /></td>
						</tr>
						<tr>
							<td width="20%"><?php echo JText::_('JRESEARCH_SOFTWARE_AUTHOR').': '; ?></td>
							<td>Luis Gal&aacute;rraga (<a href="mailto:shamantobi@gmail.com">shamantobi@gmail.com</a>) and Florian Prinz (<a href="mailto:prinz.florian@chello.at">prinz.florian@chello.at</a>)</td>
						</tr>
						<tr>
							<td width="20%"><?php echo JText::_('JRESEARCH_SOFTWARE_MENTOR').': '; ?></td>
							<td>Nereyda Valentin-Macias, (<a href="mailto:neri@valenciasconsulting.com">neri@valenciasconsulting.com</a>)</td>
						</tr>
						<tr>
							<td width="20%"><?php echo JText::_('JRESEARCH_SOFTWARE_VERSION').': '; ?></td>
							<td>1.1.4</td>
						</tr>
						<tr>
							<td width="20%"><?php echo JText::_('JRESEARCH_SOFTWARE_COPYRIGHT').': '; ?></td>
							<td>Copyright 2008, Luis Gal&aacute;rraga</td>
						</tr>
						<tr>
							<td width="20%"><?php echo JText::_('JRESEARCH_SOFTWARE_LICENSE').': '; ?></td>
							<td>GPL version 2.0</td>
						</tr>
						<tr>
							<td width="20%"><?php echo JText::_('JRESEARCH_BIBUTILS_VERSION').': '; ?></td>
							<td>3.41</td>
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
			</div>
		</div>		
	</div>	
	</td>
	</tr>
	</tbody>
</table>