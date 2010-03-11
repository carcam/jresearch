<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Helpers
* @copyright	Copyright (C) 2010 Florian Prinz.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'exporters'.DS.'exporter.php');

class JResearchPublicationDocExporter extends JResearchPublicationExporter {
	public function parse($publications)
	{
		$output = "<html>";
		$output .= "<head>";
		$output .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
		$output .= "</head>";
		$output .= "<body>";
		
		$output .= "<h1>".JText::_('JRESEARCH_PUBLICATIONS')."</h1>";
		
		if(!is_array($publications))
			$output .= $this->parseSingle($publications);
		else{
			foreach($publications as $pub){
				$output .= $this->parseSingle($pub)."<br />";
			}
		}
		$output .= "</body></html>";
		
		return $output;
	}
	
	private function parseSingle($publication)
	{
		$output = null;
		
		$style = JResearchCitationStyleFactory::getInstance('vancouver', $publication->pubtype);
		$output = "<p>".$style->getReferenceHTMLText($publication, true)."</p>";
		
		return $output;
	}
	
	public function getMimeEncoding()
	{
		return 'application/msword';
	}
}

?>