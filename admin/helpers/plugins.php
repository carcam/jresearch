<?php
/**
 * @package JResearch
 * @subpackage Helpers
 * @author Luis Galárraga
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Helper function for J!Research plugins related functionalities.
 *
 */
class JResearchPluginsHelper{

	/**
	 * Triggers the onBeforeExecuteJResearchTask event. It executes every subscribed plugin 
	 * until finding one that decided to handle the request.
	 * @return boolean True if any of the subscribed plugins has decided to handle the request
	 * so the specified controller is ignored. 
	 */
	public static function onBeforeExecuteJResearchTask(){
		$dispatcher = JDispatcher::getInstance();
		$event = 'onBeforeExecuteJResearchTask';
		$args = array ();
		$observers = $dispatcher->get('_observers');
		/*
		 * We need to iterate through all of the registered observers and
		 * trigger the event for each observer that handles the event.
		 */
		foreach ($observers as $observer)
		{
			if (is_array($observer)){
				if ($observer['event'] == $event){
					if (function_exists($observer['handler'])){
						$result = call_user_func_array($observer['handler'], $args);
						// It means the previous plugin handled the request, so the other ones
						// are ignored
						if($result) return $result;
					}else{
						JError::raiseWarning('SOME_ERROR_CODE', 'JDispatcher::trigger: Event Handler Method does not exist.', 'Method called: '.$observer['handler']);
					}
				}else{
					continue;
				}
			}
			elseif (is_object($observer))
			{
				/*
				 * Since we have gotten here, we know a little something about
				 * the observer.  It is a class type observer... lets see if it
				 * is an object which has an update method.
				 */
				if (method_exists($observer, 'update'))
				{
					/*
					 * Ok, now we know that the observer is both not an array
					 * and IS an object.  Lets trigger its update method if it
					 * handles the event and return any results.
					 */
					if (method_exists($observer, $event)){
						$args['event'] = $event;
						$result = $observer->update($args);
						// It means the previous plugin handled the request, so the other ones
						// are ignored
						if($result) return $result;
					}else{
						continue;
					}
				}else{
					/*
					 * At this point, we know that the registered observer is
					 * neither a function type observer nor an object type
					 * observer.  PROBLEM, lets throw an error.
					 */
					JError::raiseWarning('SOME_ERROR_CODE', 'JDispatcher::trigger: Unknown Event Handler.', $observer );
				}
			}
		}	
		return false;
	} 
}

?>