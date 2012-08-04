<?php
/**
 * @version		$Id: captcha.php 88 2008-07-10 19:28:29Z louis $
 * @package		JXtended
 * @subpackage	Captcha
 * @copyright	Copyright (C) 2007-2008 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

defined('JPATH_BASE') or die;

jximport('joomla.filesystem.folder');

/**
 * JXtended Captcha Library
 *
 * This library implements a highly flexible Captcha (Completely Automated
 * Public Turing test to tell Computers and Humans Apart) image and audio
 * verification system useful for fighting automated form submitions.
 *
 * Inspired in part by code written by: Edward Eliot <http://www.ejeliot.com/>
 *
 * <code>
 *  <?php
 *  jximport('jxtended.captcha.captcha');
 *  $captcha = &JXCaptcha::getInstance({TYPE}, {OPTIONS});
 *  ?>
 * </code>
 *
 * @package		JXtended
 * @subpackage	Captcha
 * @version		1.0
 */
class JXCaptcha extends JObject
{
	/**
	 * The captcha code string.
	 * @access	private
	 * @var		string
	 */
	var $code				= null;

	/**
	 * The captcha test id.
	 * @access	private
	 * @var		integer
	 */
	var $id					= null;

	/**
	 * The registry namespace identifier.
	 * @access	private
	 * @var		string
	 */
	var $_namespace		= 'jxcaptcha.';

	/**
	 * This method instantiates a new captcha object.  It always
	 * creates a new object to allow for multiple captcha tests
	 * per page and multiple types of captcha tests.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	$type		Captcha type (image, audio)
	 * @param	array	$options	An array of options.
	 * @return	mixed	A captcha object on success/false on failure.
	 */
	function &getInstance($type = 'image', $options = array())
	{
		$class	= 'JXCaptcha'.ucfirst($type);
		$false	= false;

		if (!call_user_func(array($class,'test'), $options)) {
			return $false;
		}

		// Instantiate a new captcha object
		$instance = new $class($options);
		return $instance;
	}

	/**
	 * This method is used to initialize the captcha object
	 * and do any necessary options validation and dependencies
	 * checking.  It will return true if all is successful and
	 * false on failure.  The method should also set an error
	 * message to be retrieved via the getError() method.
	 *
	 * <code>
	 *  <?php
	 *  jximport('jxtended.captcha.captcha');
	 *  $captcha = &JXCaptcha::getInstance({TYPE}, {OPTIONS});
	 *
	 *  // Initialize the captcha object
	 *  if (!$captcha->initialize()) {
	 * 		return false;
	 *  }
	 *  ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	True on success/false on failure
	 */
	function initialize()
	{
		return true;
	}

	/**
	 * This method creates a new captcha test instance.  If the object
	 * is created successfully, this method will return an associative
	 * array of the structure below for use by the application.
	 *
	 * Returns an associative array with key value pairs on success:
	 * 'id'	=> the numeric id of the captcha test
	 * 'file' => the path to the captcha file
	 * 'code' => the captcha code string
	 *
	 * <code>
	 *  <?php
	 *  jximport('jxtended.captcha.captcha');
	 *  $captcha = &JXCaptcha::getInstance({TYPE}, {OPTIONS});
	 *
	 *  // Initialize the captcha object
	 *  if (!$captcha->initialize()) {
	 * 		return false;
	 *  }
	 *
	 *  // Create a captcha test
	 *  if (is_array($return = $captcha->create())) {
	 *  	echo "Success";
	 *  } else {
	 *		echo "Failure";
	 *  }
	 *  ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @return	mixed	An associative array on success/false on failure.
	 */
	function create()
	{
		return;
	}

	/**
	 * This method validates a string against the captcha code stored
	 * in the users' session.  It returns true if the string matches
	 * and false if the string does not match.
	 *
	 * <code>
	 *	<?php
	 *	jximport('jxtended.captcha.captcha');
	 *
	 *	// Get the input
	 *	$post = JRequest::get('post');
	 *
	 *	// Get the captcha tests
	 *	$captchas = $mainframe->getUserState('jxcaptcha.captcha');
	 *
	 *	foreach ($captchas as $captcha)
	 *	{
	 *		if (isset($post[$captcha['id']]))
	 *		{
	 *			// Validate the input
	 *			if (JXCaptcha{TYPE}::validate($captcha['id'], $post[$captcha['id']]))
	 *				echo "Success";
	 *			else
	 *				echo "Failure";
	 *		}
	 *	}
	 *	?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	integer	$id				The id of the captcha test.
	 * @param	string	$input			A string to test against the captcha code.
	 * @param	bool	$caseSensitive	A flag to enable/disable case sensitivity.
	 * @return	bool	True on success/false on failure
	 */
	function validate($id, $input, $caseSensitive = false)
	{
		return;
	}

	/**
	 * This method can flush the captcha test associated with the session
	 * or flush any captcha files on the hard drive older than the session
	 * life-time, or both.
	 *
	 * <code>
	 * 	<?php
	 * 	jximport('jxtended.captcha.captcha');
	 *	$captcha = &JXCaptcha::getInstance({TYPE}, {OPTIONS});
	 *
	 *	// Clean up old captcha tests
	 *  if (!$captcha->clean(true, true)) {
	 *		$this->setError('Clean up failed');
	 *		return false;
	 *	}
	 * 	?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	$session	A flag to enable/disable cleaning of the session.
	 * @param	bool	$expired	A flag to enable/disable deletion of old files.
	 * @return	bool	True on success/false on failure
	 */
	function clean($session = true, $expired = true)
	{
		global $mainframe;

		$config	= &JFactory::getConfig();
		$files	= array();

		if ($session == true)
		{
			// Get the captchas
			$captchas = $mainframe->getUserState($this->_namespace.'captcha');

			if (is_array($captchas))
			{
				// Iterate the captchas and delete the files
				foreach ($captchas as $captcha)
				{
					// Delete the file
					if (!$this->_deleteFile($this->filePath.DS.$captcha['file']))
					{
						// Error: delete failed
						return false;
					}
				}
			}

			// Clean the session
			$mainframe->setUserState($this->_namespace.'captcha', null);
		}

		// Check for old files to delete?
		if ($expired == true)
		{
			// Get the files in the files directory
			if (($files = JFolder::files($this->filePath)) === false)
			{
				$this->setError(JText::_('CAPTCHA_READING_FILES_PATH_FAILED'));
				return false;
			}

			// Iterate through the files and delete only the captcha files
			foreach ($files as $file)
			{
				// Find the captcha files
				if (substr($file, 0, 8) == 'captcha_')
				{
					$mtime = filemtime($this->filePath.DS.$file);
					$ltime = $config->getValue('lifetime');
					$etime = time() - ($ltime * 60);

					// Check if the file is older than the session life-time
					if ($mtime < $etime)
					{
						// Delete the file
						if (!$this->_deleteFile($this->filePath.DS.$file))
						{
							// Error: delete failed
							return false;
						}
					}
				}
			}
		}

		return true;
	}

	/**
	 * This method generates a random captcha code string.
	 *
	 * @since	1.0
	 * @access	private
	 * @return	string	A captcha code string.
	 */
	function _getCode()
	{
		$strlen	= strlen($this->charset);

		// Generate the code
		for ($i = 0; $i < $this->length; $i++)
		{
			$this->code .= $this->charset[mt_rand(0, $strlen-1)];
		}

		return $this->code;
	}

	/**
	 * This method is used to delete a file from the filesystem.
	 *
	 * @since	1.0
	 * @access	private
	 * @param	string	$file	Absolute path to a file.
	 * @return	bool	True on success/false on failure.
	 */
	function _deleteFile($file)
	{
		jximport('joomla.filesystem.file');

		// Verify that the file exists
		if (JFile::exists($file))
		{
			// Delete the file
			if (!JFile::delete($file))
			{
				// Error: delete failed
				$this->setError(JText::_('CAPTCHA_FILE_DELETE_FAILED'));
				return false;
			}
		}

		return true;
	}
}

/**
 * JXtended Captcha Audio Library
 *
 * This library implements a highly flexible Captcha (Completely Automated
 * Public Turing test to tell Computers and Humans Apart) audio verification
 * system useful for fighting automated form submitions.  This library
 * requires the Flite Speach Synthesizer.
 *
 * <code>
 *  <?php
 *  jximport('jxtended.captcha.captcha');
 *  $captcha = &JXCaptcha::getInstance('audio', {OPTIONS});
 *  ?>
 * </code>
 *
 * @package		JXtended
 * @subpackage	Form
 * @version		1.0
 */
class JXCaptchaAudio extends JXCaptcha
{
	/**
	 * The absolute path to the synthesizer application
	 * @access	public
	 * @var		string
	 */
	var $application		= null;

	/**
	 * The character set to use for the captcha codes.
	 * @access	public
	 * @var		string
	 */
	var $charset			= null;

	/**
	 * The path to store the captcha files.
	 * @access	public
	 * @var		string
	 */
	var $filePath			= null;

	/**
	 * The image output format.
	 * @access	public
	 * @var		string
	 */
	var $format				= null;

	/**
	 * The captcha code string length.
	 * @access	public
	 * @var		integer
	 */
	var $length				= null;

	/**
	 * The image file name.
	 * @access	private
	 * @var		string
	 */
	var $filename			= null;

	/**
	 * Class constructor
	 *
	 * @access	protected
	 * @param	array	$options	An array of configuration options.
	 * @return	void
	 * @since	1.0
	 */
	function __construct($options = array())
	{
		// Define the default options
		$defaults	= array('application'		=> '/usr/bin/flite',
							'charset'			=> '0123456789',
							'filePath'			=> JPATH_SITE.DS.'media'.DS.'captcha'.DS.'audio',
							'format'			=> 'wav',
							'phrases'			=> array(
														"The %i numbers are %s",
														"Enter the next %i numbers: %s",
														"%i numbers: %s" ),
							'length'			=> 5 );

		// Iterate the options and bind them to the object
		foreach ($defaults as $k=>$v)
		{
			if (array_key_exists($k, $options))
			{
				// Overwrite the default
				$this->$k = $options[$k];
			}
			else
			{
				// Use the default
				$this->$k = $v;
			}
		}
	}

	/**
	 * This method is used to initialize the captcha object
	 * and do any necessary options validation and dependencies
	 * checking.  It will return true if all is successful and
	 * false on failure.  The method should also set an error
	 * message to be retrieved via the getError() method.
	 *
	 * <code>
	 *  <?php
	 *  jximport('jxtended.captcha.captcha');
	 *  $captcha = &JXCaptcha::getInstance('image', {OPTIONS});
	 *
	 *  // Initialize the captcha object
	 *  if (!$captcha->initialize()) {
	 * 		return false;
	 *  }
	 *  ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	True on success/false on failure
	 */
	function initialize()
	{
		// Clean up the old captcha tests
		if (!$this->clean(true, true))
		{
			return false;
		}

		// Salt the random number generator
		mt_srand(10000000 * (double) microtime());

		return true;
	}

	/**
	 * This method creates a new captcha test instance.  If the object
	 * is created successfully, this method will return an associative
	 * array of the structure below for use by the application.
	 *
	 * Returns an associative array with key value pairs on success:
	 * 'id'	=> the numeric id of the captcha test
	 * 'file' => the path to the captcha file
	 * 'code' => the captcha code string
	 *
	 * <code>
	 *  <?php
	 *  jximport('jxtended.captcha.captcha');
	 *  $captcha = &JXCaptcha::getInstance('audio', {OPTIONS});
	 *
	 *  // Initialize the captcha object
	 *  if (!$captcha->initialize()) {
	 * 		return false;
	 *  }
	 *
	 *  // Create a captcha test
	 *  if (is_array($return = $captcha->create())) {
	 *  	echo "Success";
	 *  } else {
	 *		echo "Failure";
	 *  }
	 *  ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @return	mixed	An associative array on success/false on failure.
	 */
	function create()
	{
		// Initialize some variables
		$this->id		= 'c'.mt_rand();
		$this->code		= '';
		$this->filename	= 'captcha_'.$this->id.'.'.$this->format;

		// Generate the code
		if (!$this->_getCode())
		{
			return false;
		}

		// Get a text phrase
		if (!$this->_getPhrase())
		{
			return false;
		}

		// Create the audio file
		if (!$this->_createFile())
		{
			return false;
		}

		$return	= array('id' => $this->id, 'code' => $this->code, 'file' => $this->filename);

		if (is_null($mainframe->getUserState($this->_namespace.'captcha')))
		{
			$mainframe->setUserState($this->_namespace.'captcha', array($this->id => $return));
		}
		else
		{
			$captcha = $mainframe->getUserState($this->_namespace.'captcha');
			$captcha[$this->id] = $return;

			$mainframe->setUserState($this->_namespace.'captcha', $captcha);
		}

		return $return;
	}

	/**
	 * This method validates a string against the captcha code stored
	 * in the users' session.  It returns true if the string matches
	 * and false if the string does not match.
	 *
	 * <code>
	 *	<?php
	 *	jximport('jxtended.captcha.captcha');
	 *
	 *	// Get the input
	 *	$post = JRequest::get('post');
	 *
	 *	// Get the captcha tests
	 *	$captchas = $mainframe->getUserState('jxcaptcha.captcha');
	 *
	 *	foreach ($captchas as $captcha)
	 *	{
	 *		if (isset($post[$captcha['id']]))
	 *		{
	 *			// Validate the input
	 *			if (JXCaptchaAudio::validate($captcha['id'], $post[$captcha['id']]))
	 *				echo "Success";
	 *			else
	 *				echo "Failure";
	 *		}
	 *	}
	 *	?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	integer	$id				The id of the captcha test.
	 * @param	string	$input			A string to test against the captcha code.
	 * @param	bool	$caseSensitive	A flag to enable/disable case sensitivity.
	 * @return	bool	True on success/false on failure
	 */
	function validate($id, $input, $caseSensitive = false)
	{
		global $mainframe;

		// Get the captcha arrays from the session
		$captchas	= $mainframe->getUserState($this->_namespace.'captcha');

		// Check that the specified captcha test exists
		if (!isset($captchas[$id]))
		{
			$this->setError(JText::_('CAPTCHA_ID_NOT_FOUND'));
			return false;
		}

		// Pull out the code
		$code = $captchas[$id]['code'];

		// Adjust case if necessary
		if ($caseSensitive == false)
		{
			$code	= strtoupper($code);
			$input	= strtoupper($input);
		}

		// Check if the strings match
		if ((!is_null($code)) && ($code == $input))
		{
			if (!$this->clean(true, true))
			{
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * This method compiles the randomly generated captcha
	 * code a randomly selected phrase to create a complete
	 * text string to be passed to the voice synthesizer.
	 *
	 * @since	1.0
	 * @access	private
	 * @return	string	The generated text phrase
	 */
	function _getPhrase()
	{
		$codePhrase = ' ';

		for ($i = 0; $i < $this->length; $i++)
		{
			// second from begining to second to last
			if (($i > 1) && ($i < ($this-length - 1)))
			{
				$codePhrase .= ', ';
			}

			// last character
			if ($i == ($this->length - 1))
			{
				$codePhrase .= ' and ';
			}

			$codePhrase .= $this->code[$i];
		}

		$phrase = array_rand($this->phrases, 1);

		$this->phrase = sprintf($phrase, $this->length, $codePhrase);

		return $this->phrase;
	}

	/**
	 * Method to run the voice synthesizer and write the file to disk.
	 *
	 * @since	1.0
	 * @access	private
	 * @return	bool	True on success/false on failure.
	 */
	function _createFile()
	{
		$fullPath	= $this->filePath.DS.$this->filename;
		$sequence	= array($this->application, '-t', $this->phrase, '-o', $fullPath);
		$output		= array();
		$return		= null;

		// Run the command
		exec(implode(' ', $sequence), $output, $return);

		// Check the return
		if ($return == 0)
		{
			return true;
		}

		return false;
	}
}

/**
 * JXtended Captcha Image Library
 *
 * This library implements a highly flexible Captcha (Completely Automated
 * Public Turing test to tell Computers and Humans Apart) image verification
 * system useful for fighting automated form submitions.  This library requires
 * the GD PHP extension to be installed.
 *
 * <code>
 *  <?php
 *  jximport('jxtended.captcha.captcha');
 *  $captcha = &JXCaptcha::getInstance('image', {OPTIONS});
 *  ?>
 * </code>
 *
 * @package		JXtended
 * @subpackage	Form
 * @version		1.0
 */
class JXCaptchaImage extends JXCaptcha
{
	/**
	 * The character set to use for the captcha codes.
	 * @access	public
	 * @var		string
	 */
	var $charset			= null;

	/**
	 * Flag to enable/disable font colors.
	 * @access	public
	 * @var		bool
	 */
	var $color				= null;

	/**
	 * Flag to enable/disable character shadows.
	 * @access	public
	 * @var		bool
	 */
	var $shadow				= null;

	/**
	 * The path to the background images.
	 * @access	public
	 * @var		string
	 */
	var $backgroundPath		= null;

	/**
	 * The path to the fonts.
	 * @access	public
	 * @var		string
	 */
	var $fontPath			= null;

	/**
	 * The path to store the captcha files.
	 * @access	public
	 * @var		string
	 */
	var $filePath			= null;

	/**
	 * An array of background images.
	 * @access	public
	 * @var		array
	 */
	var $backgrounds		= null;

	/**
	 * An array of available fonts.
	 * @access	public
	 * @var		array
	 */
	var $fonts				= null;

	/**
	 * The minimum font size.
	 * @access	public
	 * @var		integer
	 */
	var $minFont			= null;

	/**
	 * The maximum font size.
	 * @access	public
	 * @var		integer
	 */
	var $maxFont			= null;

	/**
	 * The font angle range minimum.
	 * @access	public
	 * @var		integer
	 */
	var $minAngle			= null;

	/**
	 * The font angle range maximum.
	 * @access	public
	 * @var		integer
	 */
	var $maxAngle			= null;

	/**
	 * The image output format.
	 * @access	public
	 * @var		string
	 */
	var $format				= null;

	/**
	 * The captcha code string length.
	 * @access	public
	 * @var		integer
	 */
	var $length				= null;

	/**
	 * The image height in pixels.
	 * @access	public
	 * @var		integer
	 */
	var $height				= null;

	/**
	 * The image width in pixels.
	 * @access	public
	 * @var		integer
	 */
	var $width				= null;

	/**
	 * Should the image be output directly?
	 * @access	public
	 * @var		boolean
	 */
	var $direct				= null;

	/**
	 * The image resource container.
	 * @access	private
	 * @var		resource
	 */
	var $image				= null;

	/**
	 * The image file name.
	 * @access	private
	 * @var		string
	 */
	var $filename			= null;

	/**
	 * Class constructor
	 *
	 * @access	protected
	 * @param	array	$options	An array of configuration options.
	 * @return	void
	 * @since	1.0
	 */
	function __construct($options = array())
	{
		// Define the default options
		$defaults	= array('charset'			=> 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
							'color'				=> true,
							'shadow'			=> false,
							'backgroundPath'	=> dirname( __FILE__ ).DS.'backgrounds',
							'fontPath'			=> dirname( __FILE__ ).DS.'fonts',
							'filePath'			=> JPATH_SITE.DS.'media'.DS.'captcha'.DS.'image',
							'backgrounds'		=> array(),
							'fonts'				=> array(),
							'minFont'			=> 11,
							'maxFont'			=> 14,
							'minAngle'			=> -25,
							'maxAngle'			=> 25,
							'format'			=> 'png',
							'length'			=> 5,
							'height'			=> 20,
							'width'				=> 100,
							'direct'			=> false );

		// Iterate the options and bind them to the object
		foreach ($defaults as $k=>$v)
		{
			if (array_key_exists($k, $options))
			{
				// Overwrite the default
				$this->$k = $options[$k];
			}
			else
			{
				// Use the default
				$this->$k = $v;
			}
		}

		// Get the fonts available
		if (count($this->fonts = JFolder::files($this->fontPath, '.', false, true)) < 1)
		{
			$this->setError(JText::_('CAPTCHA_NO_FONTS_AVAILABLE'));
			return false;
		}

		// Get the backgrounds available
		if (($this->backgrounds = JFolder::files($this->backgroundPath, '.', false, true)) === false)
		{
			$this->setError(JText::_('CAPTCHA_ERROR_READING_BACKGROUNDS_DIRECTORY'));
			return false;
		}

		// Salt the random number generator
		mt_srand(10000000 * (double) microtime());
	}

	/**
	 * This method is used to initialize the captcha object
	 * and do any necessary options validation and dependencies
	 * checking.  It will return true if all is successful and
	 * false on failure.  The method should also set an error
	 * message to be retrieved via the getError() method.
	 *
	 * <code>
	 *  <?php
	 *  jximport('jxtended.captcha.captcha');
	 *  $captcha = &JXCaptcha::getInstance('image', {OPTIONS});
	 *
	 *  // Initialize the captcha object
	 *  if (!$captcha->initialize()) {
	 * 		return false;
	 *  }
	 *  ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	True on success/false on failure
	 */
	function test($options = array())
	{
		// Verify GD's presence
		if (!function_exists('gd_info')) {
			$this->setError(JText::_('CAPTCHA_GD_LIBRARY_NOT_AVAILABLE'));
			return false;
		}

		// Verify the image format is valid
		switch ((array_key_exists('format', $options)) ? $options['format'] : null)
		{
			case 'png':
				if (!function_exists('imagepng')) {
					$this->setError(JText::_('CAPTCHA_INVALID_IMAGE_FORMAT_PNG'));
					return false;
				}
				break;

			case 'jpeg':
				if (!function_exists('imagejpeg')) {
					$this->setError(JText::_('CAPTCHA_INVALID_IMAGE_FORMAT_JPEG'));
					return false;
				}
				break;

			case 'gif':
				if (!function_exists('imagegif')) {
					$this->setError(JText::_('CAPTCHA_INVALID_IMAGE_FORMAT_GIF'));
					return false;
				}
				break;

			default:
				if (!function_exists('imagepng')) {
					$this->setError(JText::_('CAPTCHA_INVALID_IMAGE_FORMAT_PNG'));
					return false;
				}
				if (!function_exists('imagejpeg')) {
					$this->setError(JText::_('CAPTCHA_INVALID_IMAGE_FORMAT_JPEG'));
					return false;
				}
				if (!function_exists('imagegif')) {
					$this->setError(JText::_('CAPTCHA_INVALID_IMAGE_FORMAT_GIF'));
					return false;
				}
				break;
		}

		return true;
	}

	function generateId()
	{
		global $mainframe;

		// Initialize the code string and image filename
		$this->id		= 'c'.mt_rand();
		$this->code		= '';
		$this->filename	= 'captcha_'.$this->id.'.'.$this->format;

		$return	= array('id' => $this->id, 'code' => $this->code, 'file' => $this->filename);

		if (is_null($mainframe->getUserState($this->_namespace.'captcha')))
		{
			$mainframe->setUserState($this->_namespace.'captcha', array($this->id => $return));
		}
		else
		{
			$captcha = $mainframe->getUserState($this->_namespace.'captcha');
			$captcha[$this->id] = $return;

			$mainframe->setUserState($this->_namespace.'captcha', $captcha);
		}

		return $return;
	}

	/**
	 * This method creates a new captcha test instance.  If the object
	 * is created successfully, this method will return an associative
	 * array of the structure below for use by the application.
	 *
	 * Returns an associative array with key value pairs on success:
	 * 'id'	=> the numeric id of the captcha test
	 * 'file' => the path to the captcha file
	 * 'code' => the captcha code string
	 *
	 * <code>
	 *  <?php
	 *  jximport('jxtended.captcha.captcha');
	 *  $captcha = &JXCaptcha::getInstance('image', {OPTIONS});
	 *
	 *  // Initialize the captcha object
	 *  if (!$captcha->initialize()) {
	 * 		return false;
	 *  }
	 *
	 *  // Create a captcha test
	 *  if (is_array($return = $captcha->create())) {
	 *  	echo "Success";
	 *  } else {
	 *		echo "Failure";
	 *  }
	 *  ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @return	mixed	An associative array on success/false on failure.
	 */
	function create($id = null)
	{
		global $mainframe;

		if (empty($id)) {
			$return = $this->generateId();
		} else {
			$captcha = $mainframe->getUserState($this->_namespace.'captcha');
			if (is_null($captcha) || empty($captcha[$id])) {
				$return	= array('id' => $id, 'code' => '', 'file' => 'captcha_'.$id.'.'.$this->format);
			} else {
				$return = $captcha[$id];
			}
		}

		// Create a base image
		$this->image = imagecreatetruecolor($this->width, $this->height);

		// Get the image background
		if (!$this->_getBackground())
		{
			return false;
		}

		// Generate the code
		if (!$this->_getCode())
		{
			return false;
		}

		// Draw the code
		if (!$this->_drawCode())
		{
			return false;
		}

		if (!$this->direct) {
			// Write the image file
			if (!$this->_createImage())
			{
				return false;
			}
		}

		$return['code'] = $this->code;
		if (is_null($mainframe->getUserState($this->_namespace.'captcha')))
		{
			$mainframe->setUserState($this->_namespace.'captcha', array($return['id'] => $return));
		}
		else
		{
			$captcha = $mainframe->getUserState($this->_namespace.'captcha');
			$captcha[$return['id']] = $return;

			$mainframe->setUserState($this->_namespace.'captcha', $captcha);
		}

		if ($this->direct) {
			// Write the image file
			if (!$this->_outputImage())
			{
				return false;
			}
		}

		// Destroy the image
		imagedestroy($this->image);

		return $return;
	}

	/**
	 * This method validates a string against the captcha code stored
	 * in the users' session.  It returns true if the string matches
	 * and false if the string does not match.
	 *
	 * <code>
	 *	<?php
	 *	jximport('jxtended.captcha.captcha');
	 *
	 *	// Get the input
	 *	$post = JRequest::get('post');
	 *
	 *	// Get the captcha tests
	 *	$captchas = $mainframe->getUserState('jxcaptcha.captcha');
	 *
	 *	foreach ($captchas as $captcha)
	 *	{
	 *		if (isset($post[$captcha['id']]))
	 *		{
	 *			// Validate the input
	 *			if (JXCaptchaImage::validate($captcha['id'], $post[$captcha['id']], false))
	 *				echo "Success";
	 *			else
	 *				echo "Failure";
	 *		}
	 *	}
	 *	?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	integer	$id				The id of the captcha test.
	 * @param	string	$input			A string to test against the captcha code.
	 * @param	bool	$caseSensitive	A flag to enable/disable case sensitivity.
	 * @return	bool	True on success/false on failure
	 */
	function validate($id, $input, $caseSensitive = false)
	{
		global $mainframe;

		// Get the captcha arrays from the session
		$captchas	= $mainframe->getUserState($this->_namespace.'captcha');

		// Check that the specified captcha test exists
		if (!isset($captchas[$id]))
		{
			$this->setError(JText::_('CAPTCHA_ID_NOT_FOUND'));
			return false;
		}

		// Pull out the code
		$code = $captchas[$id]['code'];

		// Adjust case if necessary
		if ($caseSensitive == false)
		{
			$code	= strtoupper($code);
			$input	= strtoupper($input);
		}
		// Check if the strings match
		if ((!is_null($code)) && ($code == $input))
		{

			if (!$this->clean(true, true))
			{
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * This method draws the captcha code string in the image
	 * using a random color, angle, size, font, etc for each
	 * character.
	 *
	 * @since	1.0
	 * @access	private
	 * @return	bool	True on success/false on failure.
	 */
	function _drawCode()
	{
		// Calculate the spacing
		$spacing = (int) ($this->width / $this->length);

		// Iterate through our code and write each character
		for ($i = 0; $i < strlen($this->code); $i++)
		{
			// Get a font face, size, and angle
			$fontSize	= mt_rand($this->minFont, $this->maxFont);
			$fontAngle	= mt_rand($this->minAngle, $this->maxAngle);
			$fontKey	= array_rand($this->fonts, 1);
			$font		= $this->fonts[$fontKey];

			// Get a font color
			if ($this->color) {
				// Use random colors
				$fontColor = imagecolorallocate($this->image, rand(0, 100), rand(0, 100), rand(0, 100));

				if ($this->shadow) {
					// Shadow color
					$shadowColor = imagecolorallocate($this->image, rand(0, 100), rand(0, 100), rand(0, 100));
				}
			}
			else
			{
				// Use greyscale colors
				$fontRandColor = rand(0, 100);
				$fontColor = imagecolorallocate($this->image, $fontRandColor, $fontRandColor, $fontRandColor);

				if ($this->shadow) {
					// Shadow color
					$shadowRandColor = rand(0, 100);
					$shadowColor = imagecolorallocate($this->image, $shadowRandColor, $shadowRandColor, $shadowRandColor);
				}
			}

			// Get the character bounds
			$char = imageftbbox($fontSize, $fontAngle, $font, $this->code[$i], array());

			// Calculate offsets
			$charX		= $spacing / 4 + $i * $spacing;
			$charHeight	= $char[2] - $char[5];
            $charY		= $this->height / 2 + $charHeight / 4;

			// Write the character
            imagefttext($this->image, $fontSize, $fontAngle, $charX, $charY, $fontColor, $font, $this->code[$i], array());

            // Check for a character shadow
			if ($this->shadow) {
				// calculate the character shadow
				$shadowAngle	= mt_rand($this->minAngle, $this->maxAngle);
				$shadowCharX	= rand(-5, 5);
				$shadowCharY	= rand(-5, 5);

				// Write the character shadow
				imagefttext($this->image, $fontSize, $shadowAngle, $charX + $shadowCharX, $charY + $shadowCharY, $shadowColor, $font, $this->code[$i], array());
			}
		}

		return true;
	}

	/**
	 * This method selects a background at random for use in
	 * our captcha image.
	 *
	 * @since	1.0
	 * @access	private
	 * @return	bool	True on success/false on failure.
	 */
	function _getBackground()
	{
		// Do we have any backgrounds available?
		if (count($this->backgrounds) >= 1)
		{
			// Get a background
			$background_key	= array_rand($this->backgrounds, 1);
			$background		= $this->backgrounds[$background_key];

			// Get setup to use the background
			$parts = explode('.', $background);
			$filetype = $parts[count($parts)-1];
			$function = 'imagecreatefrom'.$filetype;

			// Check that gd handles that image type
			if (!function_exists($function))
			{
				$this->setError(JText::_('JXCAPTCHA_INVALID_BACKGROUND_IMAGE_TYPE'));
				return false;
			}

			// Create the background image
			$background_img = $function($background);

			// Copy the background to the main image
			imagecopy($this->image, $background_img, 0, 0, 0, 0, $this->width, $this->height);

			// Destroy the background image
			imagedestroy($background_img);
		}
		else
		{
			// Create the image with a blank background
			$this->image = imagecreatetruecolor($this->width, $this->height);
		}

		// Allocate a white background color
		imagecolorallocate($this->image, 255, 255, 255);

		return $this->image;
	}

	/**
	 * This method writes the image resource to the filesystem
	 * in one of many standard formats.
	 *
	 * @since	1.0
	 * @access	private
	 * @return	bool	True on success/false on failure.
	 */
	function _createImage()
	{
		$format		= $this->format;
		$filename	= $this->filePath.DS.$this->filename;

		switch($format)
		{
			case 'png':
				// Create a png
				$result = imagepng($this->image, $filename);
				break;

			case 'jpeg':
				// Create a jpeg
				$result	= imagejpeg($this->image, $filename);
				break;

			case 'gif':
				// Create a gif
				$result	= imagegif($this->image, $filename);
				break;
		}

		// Check the result
		if ($result === false)
		{
			$this->setError(JText::_('CAPTCHA_IMAGE_CREATION_FAILED'));
			return false;
		}

		return true;
	}

	/**
	 * This method writes the image resource to the filesystem
	 * in one of many standard formats.
	 *
	 * @since	1.0
	 * @access	private
	 * @return	bool	True on success/false on failure.
	 */
	function _outputImage()
	{
		$format		= $this->format;

		switch($format)
		{
			case 'png':
				// Create a png
				header("Content-type: image/png");
				$result = imagepng($this->image);
				break;

			case 'jpeg':
				// Create a jpeg
				header("Content-type: image/jpeg");
				$result	= imagejpeg($this->image);
				break;

			case 'gif':
				// Create a gif
				header("Content-type: image/gif");
				$result	= imagegif($this->image);
				break;
		}

		// Check the result
		if ($result === false)
		{
			$this->setError(JText::_('CAPTCHA_IMAGE_CREATION_FAILED_DIRECT'));
			return false;
		}

		$app = &JFactory::getApplication();
		$app->close();
	}
}