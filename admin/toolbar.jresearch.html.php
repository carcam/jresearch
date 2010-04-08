<?php
/**
* @version		$Id$
* @package		JResearch
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// No direct access
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php');

/**
* 
* This is a helper class for rendering backend toolbars
* 
*/
class JResearchToolbar
{
	
	/**
	 * Prints the toolbar menu for publications administration list.
	 *
	 */
	public static function publicationsAdminListToolbar()
	{
		global $mainframe;
    	$doc =& JFactory::getDocument();
    	$url = $mainframe->getSiteUrl();
    	
    	$css = ".icon-32-export{background: url(".$url."administrator/components/com_jresearch/assets/export.png) 100% 0 no-repeat;}";
		$doc->addStyleDeclaration($css);		
		$css = ".icon-32-import{background: url(".$url."administrator/components/com_jresearch/assets/import.png) 100% 0 no-repeat;}";
		$doc->addStyleDeclaration($css);
	
	
		JToolBarHelper::title(JText::_('JRESEARCH_PUBLICATIONS'));
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		
		JToolBarHelper::divider();	
		JToolBarHelper::addNewX('add', JText::_('Add'));
		JToolBarHelper::deleteList(JText::_('JRESEARCH_DELETE_PUB_CONFIRMATION'),'remove', JText::_('Delete'));

		JToolBarHelper::divider();
		
		JToolBarHelper::publishList('publish', JText::_('Publish'));
		JToolBarHelper::unpublishList('unpublish', JText::_('Unpublish'));
		
		JToolBarHelper::divider();
		
		JToolBarHelper::custom('makeinternal', 'publish', '', JText::_('JRESEARCH_TURN_ON_INTERNAL'));
		JToolBarHelper::custom('makenoninternal', 'unpublish', '', JText::_('JRESEARCH_TURN_OFF_INTERNAL'));		
		
		JToolBarHelper::divider();
		
		self::importButton();
		self::exportButton();
		JToolBarHelper::custom('exportAll', 'export', '', JText::_('JRESEARCH_EXPORT_ALL_PUBLICATIONS'), false);
	}

	/**
	* Prints the toolbar menu for staff administration list.
	*/	
	public static function staffAdminListToolbar(){
		JToolBarHelper::title(JText::_('JRESEARCH_STAFF'));
		
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::divider();
		
		JToolBarHelper::addNewX('add', JText::_('Add'));
		JToolBarHelper::editListX('edit', JText::_('Edit'));
		JToolBarHelper::deleteList(JText::_('JRESEARCH_DELETE_MEMBER_CONFIRMATION'),'remove', JText::_('Delete'));		

		JToolBarHelper::divider();
		
		
		JToolBarHelper::publishList('publish', JText::_('Publish'));
		JToolBarHelper::unpublishList('unpublish', JText::_('Unpublish'));

	}

	/**
	* Prints the toolbar menu for research areas administration list.
	*/	
	public static function researchAreasListToolbar(){
		JToolBarHelper::title(JText::_('JRESEARCH_RESEARCH_AREA'));
		
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::divider();
		JToolBarHelper::addNewX('add', JText::_('Add'));
		JToolBarHelper::editListX('edit', JText::_('Edit'));
		JToolBarHelper::deleteList(JText::_('JRESEARCH_DELETE_RESEARCH_AREA_CONFIRMATION'),'remove', JText::_('Delete'));		

		JToolBarHelper::divider();		
		
		JToolBarHelper::publishList('publish', JText::_('Publish'));
		JToolBarHelper::unpublishList('unpublish', JText::_('Unpublish'));

	}
	
	/**
	* Toolbar shown in the page for importing staff members from Joomla
	* users table.
	*/
	public static function addMemberToolBar(){
		JToolBarHelper::title(JText::_('JRESEARCH_IMPORT_STAFF'));
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::back(JText::_('Back'));
		JToolBarHelper::save('import', JText::_('Save'));
	}

	/**
	* Toolbar printed when editing or adding an item like a publication, project,
	* thesis or research area.
	*/	
	public static function editPublicationAdminToolbar(){
		$cid = JRequest::getVar('cid');
		$pubtype = JRequest::getVar('pubtype');
		if(isset($cid))
			$title = JText::_('JRESEARCH_EDIT_PUBLICATION');
		else
			$title = JText::_('JRESEARCH_NEW_PUBLICATION');	

		$title .= " ($pubtype)";
		JToolBarHelper::title($title);
		self::editItemAdminToolbar();
	}
	
	/**
	* Toolbar printed when editing a staff member profile.
	*/
	public static function editMemberAdminToolbar(){		
		JToolBarHelper::title(JText::_('JRESEARCH_EDIT_MEMBER_PROFILE'));
		self::editItemAdminToolbar();
	}
	
	/**
	* Toolbar printed when listing JResearch projects admin list.
	*/
	public static function projectsListToolbar(){
		JToolBarHelper::title(JText::_('JRESEARCH_PROJECTS'));
		
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::divider();
		JToolBarHelper::addNewX('add', JText::_('Add'));
		JToolBarHelper::editListX('edit', JText::_('Edit'));
		JToolBarHelper::deleteList(JText::_('Are you sure you want to delete the selected items?'),'remove', JText::_('Delete'));		

		JToolBarHelper::divider();		
		
		JToolBarHelper::publishList('publish', JText::_('Publish'));
		JToolBarHelper::unpublishList('unpublish', JText::_('Unpublish'));
	}
	
	public static function mdmListToolbar(){
		JToolBarHelper::title(JText::_('JRESEARCH_MDM'));
		
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::divider();
		JToolBarHelper::addNewX('add', JText::_('Add'));
		JToolBarHelper::editListX('edit', JText::_('Edit'));
		JToolBarHelper::deleteList(JText::_('Are you sure you want to delete the selected items?'),'remove', JText::_('Delete'));		

		JToolBarHelper::divider();		
		
		JToolBarHelper::publishList('publish', JText::_('Publish'));
		JToolBarHelper::unpublishList('unpublish', JText::_('Unpublish'));
	}
	
	/**
	 * Prints an 'Export' button
	 */
	public static function exportButton(){
		JToolBarHelper::custom('export', 'export', '', JText::_('Export'), false);		
	}
	
	/**
	 * Prints an 'Import' Button
	 *
	 */
	public static function importButton(){
		JToolBarHelper::custom('import', 'import', '', JText::_('Import'), false);		
	}
	
	/**
	 * Prints the toolbar displayed when importing set of publications from
	 * files.
	 *
	 */
	public static function importPublicationsToolbar(){
		JToolBarHelper::back(JText::_('Back'));
	}

	/**
	* Renders a button that points to JResearch Control Panel
	* @param $text Text to appear on the button.
	*/ 	
	public static function toControlPanel($text){
		global $mainframe;
	  	$doc =& JFactory::getDocument();
    	$url = $mainframe->getSiteUrl();
		$css = ".icon-32-config{background: url(".$url."administrator/components/com_jresearch/assets/config32.png) 100% 0 no-repeat;}";		
		$doc->addStyleDeclaration($css);
		JToolBarHelper::custom('tocontrolPanel', 'config', '', $text, false);		
	}	
	
	/**
	* Renders the toolbar displayed when creating/editing a research area.
	*/
	public static function editResearchAreaAdminToolbar(){
		$cid = JRequest::getVar('cid');
		
		if($cid)
			$title = JText::_('JRESEARCH_EDIT_RESEARCH_AREA');
		else
			$title = JText::_('JRESEARCH_NEW_RESEARCH_AREA');	
			
		JToolBarHelper::title($title);
		self::editItemAdminToolbar();
	}
	
	/**
	* Renders the toolbar displayed when creating/editing a project.
	*/
	public static function editProjectAdminToolbar(){
		$cid = JRequest::getVar('cid');
		if($cid)
			$title = JText::_('JRESEARCH_EDIT_PROJECT');
		else
			$title = JText::_('JRESEARCH_NEW_PROJECT');	

		JToolBarHelper::title($title);
		self::editItemAdminToolbar();
	}
	
	/**
	* Renders the toolbar displayed when creating/editing a mdm.
	*/
	public static function editMdmAdminToolbar()
	{
		$cid = JRequest::getVar('cid');
		
		if($cid)
			$title = JText::_('JRESEARCH_EDIT_MDM');
		else
			$title = JText::_('JRESEARCH_NEW_MDM');	

		JToolBarHelper::title($title);
		
		self::editItemAdminToolbar();
	}

	/**
	 * Renders the toolbar shown with the administrative list of theses.
	 *
	 */
	public static function thesesAdminListToolbar(){
		JToolBarHelper::title(JText::_('JRESEARCH_THESES'));
		
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::divider();
		JToolBarHelper::addNewX('add', JText::_('Add'));
		JToolBarHelper::editListX('edit', JText::_('Edit'));
		JToolBarHelper::deleteList(JText::_('JRESEARCH_DELETE_ITEMS_QUESTION'),'remove', JText::_('Delete'));				

		JToolBarHelper::divider();		
		
		JToolBarHelper::publishList('publish', JText::_('Publish'));
		JToolBarHelper::unpublishList('unpublish', JText::_('Unpublish'));
	}
	
	public static function editThesisAdminToolbar(){
		$cid = JRequest::getVar('cid');
		if($cid)
			$title = JText::_('JRESEARCH_EDIT_THESIS');
		else
			$title = JText::_('JRESEARCH_NEW_THESIS');	
		
		JToolBarHelper::title($title);	
		self::editItemAdminToolbar();
		
	}
	
	/**
	 * Renders the common toolbar used when editing items in the
	 * backend.
	 *
	 */
	public static function editItemAdminToolbar(){
		$bar = & JToolBar::getInstance('toolbar');
		$doc = & JFactory::getDocument();

		// Add a back button
		$bar->appendButton('Link', 'save', JText::_('Save'), 'javascript:msubmitform(\'save\')' );
		$bar->appendButton('Link', 'apply', JText::_('Apply'), 'javascript:msubmitform(\'apply\')' );
		$bar->appendButton('Link', 'cancel', JText::_('Cancel'), 'javascript:msubmitform(\'cancel\')' );

		$doc->addScriptDeclaration('function msubmitform(pressbutton){
			if (pressbutton) {
				document.adminForm.task.value=pressbutton;
			}
			if (typeof document.adminForm.onsubmit == "function") {
				if(!document.adminForm.onsubmit())
					return;
				else
					document.adminForm.submit();
			}else
				document.adminForm.submit();
		}');
		
	}

	/**
	 * Header for the control panel view.
	 *
	 */
	public static function controlPanelToolbar(){
		JToolBarHelper::title(JText::_('JRESEARCH_CONTROL_PANEL'));
	}
	
	/**
	* Prints the toolbar menu for cooperations
	*/	
	public static function cooperationsAdminListToolbar()
	{
		JToolBarHelper::title(JText::_('JRESEARCH_COOPERATIONS'));
		
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::divider();
		self::adminListToolbar();
	}
	
	public static function editCooperationAdminToolbar()
	{
		$cid = JRequest::getVar('cid');
		if($cid)
			$title = JText::_('JRESEARCH_EDIT_COOPERATION');
		else
			$title = JText::_('JRESEARCH_NEW_COOPERATION');	
		
		JToolBarHelper::title($title);
		
		self::editItemAdminToolbar();
	}
	
	public static function facilitiesAdminListToolbar()
	{
		JToolBarHelper::title(JText::_('JRESEARCH_FACILITIES'));

		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::divider();		
		self::adminListToolbar();
	}
	
	public static function editFacilityAdminToolbar()
	{
		$cid = JRequest::getVar('cid');
		if($cid)
			$title = JText::_('JRESEARCH_EDIT_FACILITY');
		else
			$title = JText::_('JRESEARCH_NEW_FACILITY');	
		
		JToolBarHelper::title($title);
		
		self::editItemAdminToolbar();
	}

	public static function financiersAdminListToolbar()
	{
		JToolBarHelper::title(JText::_('JRESEARCH_FINANCIERS'));

		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::divider();		
		self::adminListToolbar();
	}
	
	public static function editFinancierAdminToolbar()
	{
		$cid = JRequest::getVar('cid');
		if($cid)
			$title = JText::_('JRESEARCH_EDIT_FINANCIER');
		else
			$title = JText::_('JRESEARCH_NEW_FINANCIER');	
		
		JToolBarHelper::title($title);
		
		self::editItemAdminToolbar();
	}
	
	/**
	* Prints the toolbar menu for teams
	*/	
	public static function teamsAdminListToolbar()
	{
		JToolBarHelper::title(JText::_('JRESEARCH_TEAMS'));
		
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
		JToolBarHelper::divider();
		self::adminListToolbar();
	}
	
	public static function editTeamAdminToolbar()
	{
		$cid = JRequest::getVar('cid');
		if($cid)
			$title = JText::_('JRESEARCH_EDIT_TEAM');
		else
			$title = JText::_('JRESEARCH_NEW_TEAM');	
		
		JToolBarHelper::title($title);
		
		self::editItemAdminToolbar();
	}
	
	
	public static function adminListToolbar()
	{
		JToolBarHelper::addNewX('add', JText::_('Add'));
		JToolBarHelper::editListX('edit', JText::_('Edit'));
		JToolBarHelper::deleteList(JText::_('JRESEARCH_DELETE_ITEM_CONFIRMATION'),'remove', JText::_('Delete'));		

		JToolBarHelper::divider();
		
		JToolBarHelper::publishList('publish', JText::_('Publish'));
		JToolBarHelper::unpublishList('unpublish', JText::_('Unpublish'));
	}
	
	/**
	 * Toolbar shown when visiting help page in backend interface.
	 *
	 */
	public static function helpToolbar(){
		self::toControlPanel(JText::_('JRESEARCH_CONTROL_PANEL'));
	}
}
?>
