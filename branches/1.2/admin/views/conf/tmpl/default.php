<?php
/**
 * @package JResearch
 * @subpackage Configuration
 * Default view for the control panel
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); 

jimport('joomla.html.pane');
$pane = JPane::getInstance('sliders', array('allowAllClose' => true));

function juimport_new($path)
{
	// attempt to load the path locally but...
	// unfortunately 1.5 doesn't check the file exists before including it so we mask it
	$res = JLoader::import( $path, JPATH_PLUGINS.DS.'jresearch'.DS.'com_upgrader'.DS.'libraries' );
	if(!$res) {
		// fall back when it doesn't work
		return jimport($path);
	}
	return $res;
}

function downloadFile_new($url,$target)
{
	juimport_new('pasamio.downloader.downloader');
	$downloader =& Downloader::getInstance();
	$error_object = new stdClass();

	$params = JComponentHelper::getParams('com_jupdateman');
	$adapter = null;
	switch($params->get('download_method', 0))
	{
		case JUPDATEMAN_DLMETHOD_FOPEN:
		default:
			$adapter = $downloader->getAdapter('fopen');
			break;
		case JUPDATEMAN_DLMETHOD_CURL:
			$adapter = $downloader->getAdapter('curl');
			break;
	}
	
	return $adapter->downloadFile_new($url, $target, $params);
}
?>
<div style="width:100%;">
<div id="cpanel" class="jresearch-control-panel">
	<div>
		<div class="icon">
			<a class="modal" rel="{handler: 'iframe', size: {x: 750, y: 500}}" href="index.php?option=com_config&amp;controller=component&amp;component=com_jresearch&amp;path=">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/config.png" alt="<?php echo JText::_('JRESEARCH_CONFIGURATION'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_CONFIGURATION'); ?></span>
			</a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=cooperations">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/cooperations.png" alt="<?php echo JText::_('JRESEARCH_COOPERATIONS'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_COOPERATIONS'); ?></span>
			</a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=facilities">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/facilities.png" alt="<?php echo JText::_('JRESEARCH_FACILITIES'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_FACILITIES'); ?></span>
			</a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=financiers">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/financier.png" alt="<?php echo JText::_('JRESEARCH_FINANCIERS'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_FINANCIERS'); ?></span>
			</a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=projects">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/projects.png" alt="<?php echo JText::_('JRESEARCH_PROJECTS'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_PROJECTS'); ?></span>
			</a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=publications">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/publications.png" alt="<?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></span>
			</a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=researchAreas">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/areas.png" alt="<?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_RESEARCH_AREAS'); ?></span></a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=staff">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/staff.png" alt="<?php echo JText::_('JRESEARCH_STAFF'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_STAFF'); ?></span>
			</a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=teams">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/teams.png" alt="<?php echo JText::_('JRESEARCH_TEAMS'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_TEAMS'); ?></span>
			</a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;controller=theses">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/theses.png" alt="<?php echo JText::_('JRESEARCH_THESES'); ?>" />					
				<span><?php echo JText::_('JRESEARCH_THESES'); ?></span>
			</a>
		</div>
	</div>
	<div>
		<div class="icon">
			<a href="index.php?option=com_jresearch&amp;task=help">
				<img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/help-browser.png" alt="<?php echo JText::_('Help'); ?>" />					
				<span><?php echo JText::_('Help'); ?></span>
			</a>
		</div>
	</div>
    	<div>
		<div class="icon">
              <?php 
                // E:\xampp\htdocs\jresearch121\plugins\jresearch\com_upgrader\jupdateman.class.php
                if(defined('_JRESEARCH_UPGRADER_SUPPORT_') || defined('JRESEARCH_UPGRADER_SUPPORT')){
                    $version = _JRESEARCH_VERSION_;
                    $versionComps = explode(' ', $version);
                    $version = $versionComps[0];
                }else
                    $version = '1.1.4';   
                    
                $url = "http://joomla-research.com/public_html/jresearchupgrader.xml";

                $config = JFactory::getConfig();
                $tmp_path = $config->getValue('config.tmp_path');
                $plugin = JPluginHelper::getPlugin('jresearch', 'jresearch_upgrader');
                $params = new JParameter($plugin->params);

                $target = $tmp_path . DS . 'jresearchupgrader.xml';

                $cached_update = $params->get('cached_update', 0);
                
                // if($cached_update) {
                    // if(!file_exists($target)) {
                        // HTML_jupgrader::showError( 'Missing update file. Please <a href="'. $url .'" target="_blank">download the update definition file</a> and put it into your temporary directory as "jupgrader.xml".<br />Target Path: '. $target);
                        // return false;
                    // }
                // } else {
                    // $result = downloadFile_new($url,$target);
                    // if(is_object( $result )) {
                        // HTML_jupgrader::showError( 'Download Failed: '. $result->message . '('. $result->number . ')' );
                        // return false;
                    // }
                // }

                if(!file_exists($target)) {
                    HTML_jupgrader::showError( 'Update file does not exist: '. $target );
                }
                
                
                // Yay! file downloaded! Processing time :(
                $xmlDoc = new JSimpleXML();

                if (!$xmlDoc->loadFile( $target )) {
                    HTML_jupgrader::showError( 'Parsing XML Document Failed!' );
                    return false;
                }

                //$root = &$xmlDoc->documentElement;
                $root = &$xmlDoc->document;

                if ($root->name() != 'update') {
                    HTML_jupgrader::showError( 'Parsing XML Document Failed: Not an update definition file!' );
                    return false;
                }

                $rootattributes = $root->attributes();
                $latest = $rootattributes['release'];
                
                if($latest == $version){
            //    echo ' The currect version is : '.$latest.' final '.$version;           
                ?>
                <a href="index.php?option=com_jresearch">
                    <img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/no_update.png" alt="<?php echo JText::_('JRESEARCH_UPGRADE_FINAL_VERSION'); ?>" />
                    <span><?php echo JText::_('JRESEARCH_UPGRADE_FINAL_VERSION'); ?></span>
                </a>
                <?php
                }
                else {
                
                ?>
                <a href="index.php?option=com_jresearch&amp;mode=upgrader">
                    <img src="<?php echo JURI::base(); ?>/components/com_jresearch/assets/update_please.png" alt="<?php echo JText::_('JRESEARCH_UPGRADE_JRESEARCH'); ?>" />
                    <span><?php echo JText::_('JRESEARCH_UPGRADE_JRESEARCH'); ?></span>
                </a>
                <?php
                }
                ?>
		</div>
	</div>

</div>	
<div class="about-panel">
<?php 
	  echo $pane->startPane('adminform');  
	  echo $pane->startPanel(JText::_('JRESEARCH_ABOUT'), 'about');
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
	  echo $pane->endPanel(); 
	  echo $pane->startPanel(JText::_('JRESEARCH_CREDITS'), 'credits');	
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
<?php echo $pane->endPanel();
	  echo $pane->endPane();
?>
</div>
</div>		