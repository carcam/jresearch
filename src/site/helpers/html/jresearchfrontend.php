<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	HTML
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jresearchimport('tables.member', 'jresearch.admin');
jresearchimport('tables.publication', 'jresearch.admin');

class JHTMLjresearchfrontend{
    public static $supportedExtensions = array('doc', 'docx', 'pdf', 'ps', 'odt', 'txt');
    
    /**
     * Renders task icon for specific item, if user is authorized for it
     *
     * @param string $task
     * @param string $controller
     * @param int $id
     * @param int $userid
     * @param array options
     */
    public static function icon($task, $controller, $itemid=0, $userid=null, $options=array())
    {
        $availableController = array('publications');
        $availableTasks = array('edit', 'remove', 'delete', 'add', 'new');
        // Menu ID retention
        $menuId = JRequest::getVar('Itemid', 0);
        $menuIdText = !empty($menuId)? '&Itemid='.$menuId : '';

        if(in_array($controller, $availableController) && in_array($task, $availableTasks)){
            $text = JText::_('JRESEARCH_'.ucfirst($task));
            $pubtype = '';
            if ($controller == 'publications' && isset($options['pubtype'])) {
                $pubtype = '&pubtype='.$options['pubtype'];
            }

            return '<a href="index.php?option=com_jresearch&view=publication'.$pubtype
                    .'&task='
                    .$task.(($itemid > 0) ? '&id='.$itemid:'').$menuIdText.'" title="'.$text.'">'
            .'<img src="'.JURI::root().'components/com_jresearch/assets/'.$task.'.png" /></a>';
        }

        return '';
    }	
	
    /**
     * Creates a frontend link for com_jresearch with view, task, id and itemid parameter
     *
     * @param string $view
     * @param string $task
     * @param int $id
     * @param bool $itemId
     * @param array $additional Key-value pair for additional url parameters
     */
    public static function link($text, $view='cooperations', $task='display', $id=null, $bItemId = true, array $additional=array())
    {
        $itemid = JRequest::getVar('Itemid', null);
        $view = JFilterOutput::stringURLSafe($view);
        $task = JFilterOutput::stringURLSafe($task);
        JFilterOutput::cleanText($text);

        $url = "index.php?option=com_jresearch&view=$view&task=$task".((!empty($id))?'&id='.intval($id):'').(($bItemId && !empty($itemid))?'&Itemid='.intval($itemid):'').((count($additional) > 0)?self::_getKeyValueString($additional):'');
        return JFilterOutput::linkXHTMLSafe('<a href="'.$url.'">'.$text.'</a>');
    }

    /**
     * 
     * Constructs a list of research area links
     * @param array $researchAreas
     */
    public static function researchareaslinks($researchAreas, $display = 'list'){
        $itemid = JRequest::getVar('Itemid', null);
        $linksText = '';

        if($display != 'list' && $display != 'inline')
            $display = 'list';

        foreach($researchAreas as $area){
            if($area->id > 1){
                if($area->published){
                    $link = self::link($area->name, 'researcharea', 'show', $area->id);					
                    if($display == 'list')
                        $linksText .= '<li>'.$link.'</li>';
                    else 
                        $linksText .= ', '.$link;	
                }else{
                    if($display == 'list')
                        $linksText .= '<li>'.$area->name.'</li>';
                    else
                        $linksText .= ', '.$area->name;							
                }
            }
        }

        if(empty($linksText)){
            if($display == 'list')
                $linksText .= '<li>'.JText::_('JRESEARCH_UNCATEGORIZED').'</li>';
            else
                $linksText .= JText::_('JRESEARCH_UNCATEGORIZED');	
        }


        if($display == 'list')	
            $result = '<ul>'.$linksText.'</ul>'; 	
        else
            $result = ltrim(ltrim($linksText, ','));

        return $result;	
    }

    public static function getExternalLink($link)
    {
        $pattern = '[a-zA-Z0-9&?_.,=%\-\/]';
        if (strpos($link, "http://") === false)
        {
            $link = "http://" . trim($link);
        }

        return $link;
    }
    
    /**
     * It returns the HTML text to render the authors of a publication/project
     * @param type $authors Array of both strings (external authors) or JResearchMember instances.
     * @param type $format
     * @param type $arrangement
     * @return type
     */
    public static function authorsList($authors, $format, $arrangement = 'horizontal') {
        $output = ($arrangement == 'vertical') ? '<ul>' : '';
        $n = count($authors); 
        $i = 0; 
        foreach($authors as $auth) {
            $authorText = JResearchPublicationsHelper::formatAuthor(
                    $auth instanceof JResearchMember ? $auth->__toString() : $auth, 
                    $format);
            $suffix = ($i == $n - 1? '': '; ');
            if($auth instanceof JResearchMember && $auth->published) {
                if($auth->link_to_member) {
                    if ($auth->link_to_website) {
                        $website = JHTML::_('link', $auth->url_personal_page, $authorText);
                        if ($arrangement == 'vertical') {
                            $output.= '<li>'.$website.'</li>';                            
                        } else {
                            $output.= $website.$suffix;   
                        }                            
                    } else {
                        if ($arrangement == 'vertical') {
                            $output.= '<li>'.self::link($authorText, 'member', 'show', $auth->id).'</li>';
                        } else {
                            $output.= self::link($authorText, 'member', 'show', $auth->id).$suffix;
                        }
                    }
                } else { 
                    if ($arrangement === 'vertical') {
                       $output .= '<li>'.$authorText.'</li>';
                    } else {
                       $output.= $authorText.$suffix;                        
                    }
                }
            } else {
                if ($arrangement == 'vertical') {
                   $output .= '<li>'.$authorText.'</li>'; 
                } else {
                    $output.= $authorText.$suffix;
                }
            }
            $i++;
        }
        
        $output .= ($arrangement == 'vertical') ? '</ul>' : '';
        return $output;
    }
    

    /**
     * Formats a given list of keywords for rendering in the frontend.
     * @param type $keywords Semicolon-separated list of keywords.
     * @param type $linksEnabled If enabled keywords point to the other items
     * associated to the same keyword.
     * @param string $option The type of item to search, e.g., publications,
     * projects
     */
    public static function keywords($keywords, $linksEnabled, $option) {
        if ($linksEnabled) {
            $parts = explode(';', $keywords);
            $newParts = array();
            foreach ($parts as $part) {
                $key = urlencode($part);
                $Itemid = JRequest::getVar('Itemid');
                $url = JRoute::_("index.php?option=com_search&searchword=$key&ordering=newest&searchphrase=exact&areas[0]=$option&Itemid=$Itemid");
                $newParts[] = JHTML::_('link', $url, $part);
            }
            return implode('; ', $newParts);
        } else {
            return $keywords;
        }
    }


    /**
     * Gets value of array from given key if it exists, otherwise $default
     */
    private static function getKey($key, array &$arr, $default=null)
    {
        return (array_key_exists($key, $arr)?$arr[$key]:$default);
    }

    private static function _getKeyValueString(array $pairs) {
        $string = array();

        foreach($pairs as $key=>$value)
        {
            $string[] = ((string) JFilterOutput::stringURLSafe($key)).'='.((string) JFilterOutput::stringURLSafe($value));
        }

        return implode('&', $string);
    }


    /**
     * Renders an attachment download link
     *
     * @param string $url Resource URL
     */
    public static function attachments($attachments, $arrangement) {
        $parts = array();        
        foreach ($attachments as $attach) {
            $uri = new JUri($attach['url']);
            $path = $uri->getPath();
            $tag = $attach['tag'];
            $assetClass = self::getFileAssetClass($path);
            $text = '';
            if (empty($tag)) {
                $text = basename($path);
                if (empty($text)) {
                    $text = JText::_('JRESEARCH_URL');
                }
            } else {
                $text = JText::_($tag);
            }
            $parts[] = '<a class="attachmentlink '.$assetClass.'" href="'.$attach['url'].'">'.$text.'</a>';            
        }
        
        if ($arrangement == 'horizontal') {
            return '<span>'.implode(' ', $parts).'</span>';
        } else {
            return '<ul class="attachmentslist"><li>'.implode('</li><li>', $parts).'</li></ul>';
        }
    }
    
    private static function getFileAssetClass($path) {
        $pathInfo = pathinfo($path);

        if(empty($pathInfo['extension']) || !in_array($pathInfo['extension'], self::$supportedExtensions)){
            return 'attach-background-default';
        }else{
            return 'attach-background-'.$pathInfo['extension'];				
        }
    }
}
?>
