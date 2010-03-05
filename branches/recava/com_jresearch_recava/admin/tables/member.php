<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * This class represents a staff member.
 *
 */
class JResearchMember extends JTable{
	/**
	 * Database integer id
	 *
	 * @var int
	 */
	public $id;
	
	/**
	 * Is it a former member?
	 *
	 * @var bool
	 */
	public $former_member;
	
	/**
	* Member's username
	*/	
	public $username;
	
	/**
	 * Member's first name
	 *
	 * @var string
	 */
	public $firstname;
	
	/**
	 * Member's lastname
	 *
	 * @var string
	 */
	public $lastname;
	
	/**
	* Member's email
	* @var string
	*/	
	public $email;
	
	/**
	 * Research area's id
	 *
	 * @var int
	 */
	public $id_research_area;
	
	/**
	 * Member's personal page
	 *
	 * @var string
	 */
	public $url_personal_page;
	
	/**
	* Member's position
	*
	* @var string
	*/
	public $position;	
	
	/**
	 * Published status
	 *
	 * @var boolean
	 */
	public $published;
	
	/**
	 * Ordering number
	 * @var int
	*/
	public $ordering;
	
	/**
	 * Member's phone number
	 *
	 * @var string
	 */
	public $phone_or_fax;
	
	
	/**
	 * Member's photo
	 *
	 * @var string
	 */
	public $url_photo;
	
	/**
	 * Member's description text
	 * 
	 * @var string
	 */
	public $description;
	
	/**
	* User id of the author who is editing the project.
	* 
	* @var int
	*/
	public $checked_out;

	/**
	* When the project was checked out.
	* 
	* @var datetime
	*/
	public $checked_out_time;

	/**
	 * Class constructor. Maps the class to a Joomla table.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct( '#__jresearch_member', 'id', $db );
	}

	/**
	 * Returns the string representation of the member
	 *
	 * @return unknown
	 */
	function __toString(){
		return "$this->firstname $this->lastname";
	}

	/**
	* Binds the information of the indicated username, so common fields like email and name
	* are imported into the object. Used for impòrting members from Joomla tables.
	*/	
	function bindFromUser($username){
		require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');
		
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM '.$db->nameQuote('#__users').' WHERE '.$db->nameQuote('username').' = '.$db->Quote($username);
		$db->setQuery($query);
		
		$result = $db->loadAssoc();
		$this->username = $result['username'];
		$this->email = $result['email'];
		$arrayName = JResearchPublicationsHelper::getAuthorComponents($result['name']);
		if($arrayName['firstname'])
			$this->firstname = $arrayName['firstname'];

		$this->lastname = $arrayName['von'].' '.$arrayName['lastname'];
			
	}

	/**
	 * Binds data from the member table if the username exists in the member-table
	 *
	 * @param string $username
	 */
	function bindFromUsername($username)
	{
		$db =& JFactory::getDBO();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('username').' = '.$db->quote($username);
		$db->setQuery($query);
		
		$result = $db->loadAssoc();
		
		$this->bind($result);
	}
	
	/**
	* Validates the content of the member's profile information.
	* @return boolean. True if all fields of the object have a valid content.
	*/	
	function check(){
		$name_pattern = '/\w[-_\w\s.]+/';
		$phone_pattern = '/\d[-\d]+/';
		$email_pattern = '/^(\w[-\w.]*)@([-a-z0-9]+(\.[-a-z0-9]+)*\.(com|edu|infocom|edu|gov|int|mil|net|org|biz|info|name|museum|coop|aero|[a-z][a-z]))$/i';
		
		// Validate first and lastname
		if(!preg_match($name_pattern, $this->lastname)){
			$this->setError(JText::_('Lastname can only contain alphabetic characters plus ._- characters with neither leading nor trailing whitespaces'));
			return false;
		}
		
		if(!preg_match($name_pattern, $this->firstname)){
			$this->setError(JText::_('First name can only contain alphabetic characters plus ._- characters with neither leading nor trailing whitespaces'));
			return false;
		}
		
		if($this->phone_or_fax){
			if(!preg_match($phone_pattern, $this->phone_or_fax)){
				$this->setError(JText::_('Phone numbers can only contain digits and scores'));
				return false;
			}
				
		}
		
		if($this->email){
			if(!preg_match($email_pattern, $this->email)){
				$this->setError(JText::_('Please provide a valid e-mail address'));
				return false;	
			}
				
		}
		
		return true;
		
	}
	
	/**
	 * Returns array of teams, where the member is a member of these teams.
	 * @return array
	 */
	public function getTeams()
	{
		$teams = array();
		
		//Get teams
		$db = JFactory::getDBO();
		$table = '#__jresearch_team_member';
		$id = $db->nameQuote('id_team');
		$id_member = $db->nameQuote('id_member');
		
		$query = 'SELECT '.$id.' FROM '.$table.' WHERE '.$id_member.'='.$this->id;
		$db->setQuery($query);
		
		$ids = $db->loadResultArray();
		
		foreach($ids as $id)
		{
			$team = new JResearchTeam($db);
			$team->load($id);
			$teams[] = $team;
		}
		
		return $teams;
	}
	
	
	static function getTeamByAuthorName($authorName){
		// First, get the staff member
		$db = JFactory::getDBO();
		$components = explode(' ', strtolower(trim($authorName)));
		$query = 'SELECT tm.id_team FROM '.$db->nameQuote('#__jresearch_member').' m, '
				.$db->nameQuote('#__jresearch_team_member').' tm WHERE '
				.'m.id = tm.id_member AND LOWER(m.firstname) = '.$db->Quote($components[0]).' AND '
				.'LOWER(m.lastname) = '.$db->Quote($components[1]);
		$db->setQuery($query);		
		$teamId = $db->loadResult();
		$team = null;
		if(!empty($teamId)){
			$team = JTable::getInstance('Team', 'JResearch');
			$team->load($teamId);
		}
		
		return $team;
	}
	
	function getTeam(){
		$teams = $this->getTeams();
		if(!empty($teams))
			return $teams[0];
		else
			return null;
	}		
	
	/**
	 * Returns the member's photo URL
	 * @return string
	 */
	function getURLPhoto(){
		global $mainframe;
		
		if($mainframe->isAdmin())
			$base = $mainframe->getSiteURL();
		else
			$base = JURI::base();

		return $base.JString::str_ireplace($base, '', $this->url_photo);	
				
	}
}

?>