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

?>
<h2 class="componentheading">
	<?php echo JText::_('JRESEARCH_MEMBERS'); ?>
</h2>

<div id="imageflow" class="imageflow">
	<div id="imageflow_loading">
		<b>Loading images</b><br/>
		<img src="<?php echo $this->assets_path.'/loading.gif'; ?>" width="208" height="13" alt="loading" />
	</div>
	<div id="imageflow_images">
	<?php
	//Get images from members
	for ($i=0; $i<count($this->images); $i++):
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

				foreach($this->images[$i] as $kwd => $val)
				{
					$def = JString::str_ireplace('{'.$kwd.'}', $val, $def);
				}
				echo $def;
			}
		}
	endfor;
	?>
	</div>
	<div id="imageflow_captions"></div>
	<div id="imageflow_scrollbar">
		<div id="imageflow_slider"></div>
	</div>
</div>
<p id="imageflow_caption_text">
	<?php echo $this->params->get('caption_text');?>
</p>