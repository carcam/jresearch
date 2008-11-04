<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage 	Staff
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* Shows a coverflow of the staff members
* 
* Code parts for the staff imageflow are adjusted code parts from the mod_imageflow Module.
* (Parameters, imageflow itself and reflections are implemented from this module)
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');

//Includes
require(JPATH_COMPONENT_SITE.DS.'includes'.DS.'reflect2.php');
require(JPATH_COMPONENT_SITE.DS.'includes'.DS.'reflect3.php');

//Variables
$images =& $this->images;
$base = JURI::base();
$component = $base.'components/com_jresearch/';

//Paths
$js_path = $component.'js';
$css_path = $component.'css';
$assets = $component.'assets';

$params =& $this->params;

//Add params to the head script declaration
if ($params->get('imageslider') == '1')
{
	$scrpt = 'imf.hide_slider = false;'.PHP_EOL;
}
else
{
	$scrpt = 'imf.hide_slider = true;'.PHP_EOL;
}
if ($params->get('captions') == '1')
{
	$scrpt .= 'imf.hide_caption = false;'.PHP_EOL;
}
else
{
	$scrpt .= 'imf.hide_caption = true;'.PHP_EOL;
}
if ($params->get('glidetoimage') != '')
{
	$glidetoimage = $this->get('glidetoimage');
	//	Have they given us a percentage?
	if (JString::substr($glidetoimage, -1) == '%')
	{
		//	Yes, remove the % sign
		$glidetoimage = (int) JString::substr($glidetoimage, 0, -1);
		$glidetoimage = (int) (count($this->images)) * ($glidetoimage/100);
		$glidetoimage = (int) ($glidetoimage+.50);
	}
	else
	{
		$glidetoimage = (int) $glidetoimage;
	}
	if ($glidetoimage > count($this->images))
	{
		$glidetoimage = count($this->images);
	}
	if ($glidetoimage < 1)
	{
		$glidetoimage = 1;
	}
	$glidetoimage--;
	$scrpt .= 'imf.caption_id = '.$glidetoimage.';'.PHP_EOL;
	$scrpt .= 'imf.current = '.-$glidetoimage.' * imf.xstep;'.PHP_EOL;
}
if ($params->get('imagestacksize') != '')
{
	$imagestacksize = (int) $params->get('imagestacksize');
	if ($imagestacksize > 0 && $imagestacksize < 10)
	{
		$scrpt .= 'imf.conf_focus = '.$imagestacksize.';'.PHP_EOL;
	}
}
if ($params->get('imagethumbnailclass') != '')
{
	$scrpt .= "imf.conf_thumbnail = '".$params->get('imagethumbnailclass')."';".PHP_EOL;
}
if ($params->get('reflectheight') != '')
{
	$height = JString::str_ireplace('%', '', $params->get('reflectheight'));
	$height = ($height / 100);
	$scrpt .= 'imf.conf_reflection_p = '.$height.';'.PHP_EOL;
}

//Get document and add various scripts/stylesheets
$document =& JFactory::getDocument();

$document->addScript($js_path.'/imageflow.js');
$document->addScriptDeclaration($scrpt);

$document->addStyleSheet($css_path.'/imageflow.css');
?>
<div class="componentheading">
	<?php echo JText::_('Staff Members'); ?>
</div>

<div id="imageflow" class="imageflow">
	<div id="imageflow_loading">
		<b>Loading images</b><br/>
		<img src="<?=$assets.'/loading.gif'; ?>" width="208" height="13" alt="loading" />
	</div>
	<div id="imageflow_images">
	<?php
	//Get images from members
	for ($i=0; $i<count($this->images); $i++)
	{
		$imagename = $this->images[$i]['img'];
		
		//Reflection image
		$rimagename = JPATH_BASE . DS . $this->images[$i]['img'];
		$rimagename = JPath::clean($rimagename, DS);
		
		if (JFile::exists($rimagename))
		{	
			//Clean image path
			$imagename = JPath::clean( JURI::root(true). '/'. $imagename, '/' );

			$height = $this->get('reflectheight');
			
			//Interpret height
			if($height == '')
			{
				$height = '50%';
			}
			
			if (JString::substr($height,-1) != '%')
			{
				$height .= '%';
			}
			
			if ($height == "0%")
			{
				$rflctimage	= $imagename;
			}
			else
			{
				$rflctparams['height'] = $height;
				$rflctparams['img'] = $rimagename;
				$bgc = $this->get('fadetocolor');
				$bgc = JString::str_ireplace('#', '', $bgc);
				if ($bgc != '')
				{
					$rflctparams['bgc'] = $bgc;
				}
				$tint = $this->get('tintcolor');
				$tint = JString::str_ireplace('#', '', $tint);
				if ($tint != '')
				{
					$rflctparams['tint'] = $tint;
				}
				if ($this->get('reflectresize') != '')
				{
					$rflctparams['resize'] = $this->get('reflectresize');
				}
				if ($this->get('alphareflect') == '0')
				{
					$rflctimage = Reflect2::create( $rflctparams );
				}
				else
				{
					$rflctimage = Reflect3::create( $rflctparams );
				}
				if ($rflctimage !== false)
				{
					if (JString::substr($rflctimage, 0, JString::strlen(JPATH_BASE)) == JPATH_BASE)
					{
						$rflctimage = JString::substr( $rflctimage, JString::strlen(JPATH_BASE), JString::strlen($rflctimage)-JString::strlen(JPATH_BASE) );
					}
					$rflctimage = JPath::clean( JURI::root(true). '/'. $rflctimage, '/' );
				}
			}
			
			if ($rflctimage !== false)
			{
				//Template for imageflow
				$tmpl = '<a href="{url}" title="{hreftitle}"><img src="{rflct}" alt="{imgalt}" title="{imgtitle}" /></a>'.PHP_EOL;
				
				$def = JString::str_ireplace('{img}', $imagename, $tmpl);
				$def = JString::str_ireplace('{rflct}', $rflctimage, $def);

				foreach($images[$i] as $kwd => $val)
				{
					$def = JString::str_ireplace('{'.$kwd.'}', $val, $def);
				}
				echo $def;
			}
		}
	}
	?>
	</div>
	<div id="imageflow_captions"></div>
	<div id="imageflow_scrollbar">
		<div id="imageflow_slider"></div>
	</div>
</div>