<?php
/**
* @package		JResearch
* @subpackage	
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

class JResearchAccessHelper{
	
	
	/**
    * Returns an object with the access rules associated to the 
    * the item referenced in the arguments.
    * @return JObject
    */
	public static function getActions($category = '', $itemId = 0){
    	$user  = JFactory::getUser();
        $result = new JObject;
 
        if(empty($category) && empty($itemId)){
        	$assetName = 'com_jresearch';
        }elseif(empty($itemId)){
        	$assetName = 'com_jresearch.'.$category;
        }else{
            $assetName = 'com_jresearch.'.$category.'.'.(int)$itemId;        	
        }
 
        $actions = array('core.admin', 'core.manage', 'core.publications.create', 'core.publications.edit'
        , 'core.publications.delete', 'core.publications.edit.own', 'core.publications.edit.state'
        , 'core.staff.create', 'core.staff.edit', 'core.staff.delete', 
        'core.staff.edit.own', 'core.staff.edit.state', 'core.researchareas.create', 'core.researchareas.edit', 
        'core.researchareas.delete', 'core.researchareas.edit.own', 'core.researchareas.edit.state');
 
        foreach ($actions as $action) {
        	$result->set($action, $user->authorise($action, $assetName));
        }
 
		return $result;
    }
}