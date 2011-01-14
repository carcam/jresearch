<?php
/**
 * @version		$Id$
 * @package		JResearch
 * @subpackage	Includes
 * @license		GNU/GPL, see LICENSE.php
 */

/**
 * Base interface for services retrieving publications information from external
 * databases.
 *
 */
interface JResearchDatabaseServiceInterface{

	/**
	 * Executes the call to the remote service.
	 *
	 * @param mixed $request Parameter need by the remote service.
	 */
	public function call($request);
	
	/**
	 * Returns the name of the class that holds the input parameters for
	 * remote call.
	 *
	 * @return string
	 */
	public function getRequestClass();
	
	/**
	 * Returns the name of the class that holds the output parameters for
	 * remote call.
	 *
	 * @return string
	 */
	public function getResponseClass();
	
	/**
	 * Returns the prepared request according to the requirements of the
	 * remote call.
	 *
	 * @return object Instance of the request class associated to this service.
	 */
	public function getPreparedRequest();
	
	/**
	 * Implements the serialization process of the result of a call.
	 *
	 * @param mixed $result Instance of the class that handles service responses.
	 * @param string $format The format used for serialization. A service may support
	 * more than one serialization format.
	 */
	public function serialize($result, $format='xml');
}

?>