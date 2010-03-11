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

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'projectexporters'.DS.'exporter.php');

class JResearchProjectDocExporter extends JResearchProjectExporter {
	public function parse($projects)
	{
		$output = "<html>";
		$output .= "<head>";
		$output .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
		$output .= "</head>";
		$output .= "<body>";
		
		$output .= "<h1>".JText::_('JRESEARCH_PROJECTS')."</h1>";
		
		if(!is_array($projects))
			$output .= $this->parseSingle($projects);
		else{
			foreach($projects as $project){
				$output .= $this->parseSingle($project)."<br />";
			}
		}
		$output .= "</body></html>";
		
		return $output;
	}
	
	/**
	 * 
	 * @param JResearchProject $project
	 */
	private function parseSingle($project)
	{
		$output = null;
		
		$output = "<p>";
		$output .= $project->code.".".$project->title.". IP coordinador: ".$project->getPrincipalInvestigators().". Financiación ".$project->finance_value." ".$project->finance_currency.". Grupos RECAVA implicados: ";
		$output .= "</p>";
		
		return $output;
	}
	
	public function getMimeEncoding()
	{
		return 'application/msword';
	}
}

?>