<?php
	/*
		----------------------------------------------------------------
		Easy Reflections by Richard Davey, Core PHP (rich@corephp.co.uk)
		v2 - 2nd March 2007
        Updates include changes by Monte Ohrt (monte@ohrt.com)
		----------------------------------------------------------------
		You are free to use this in any product, or on any web site.
		Latest builds at: http://reflection.corephp.co.uk
		----------------------------------------------------------------

		This script accepts the following $_GET parameters:

		img		        required	The source image (to reflect)
		height	        optional	Height of the reflection (% or pixel value)
		bgc		        optional	Background colour to fade into, default = #000000
        fade_start      optional    Start the alpha fade from whch value? (% value)
        fade_end        optional    End the alpha fade from whch value? (% value)
		jpeg	        optional	Output will be JPEG at 'param' quality (default 80)
        cache           optional    Save reflection image to the cache? (boolean) (removed, always cache)
        resize			optional	resize pct of original (% value)

	*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );


class Reflect2
{

	function create( $params )
	{

		//	PHP Version sanity check
		if (version_compare('4.3.2', phpversion()) == 1)
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('This version of PHP is not fully supported. You need 4.3.2 or above.'));
			return false;
		}

		//	GD check
		if (extension_loaded('gd') == false && !dl('gd.so'))
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('You are missing the GD extension for PHP, sorry but I cannot continue.'));
			return false;
		}

		//	Our allowed query string parameters

		//	img (the image to reflect)
		if (isset($params['img']))
		{
			$source_image = $params['img'];

			//$source_image = utf8_decode($source_image);

			$source_image = str_replace('://','',$source_image);
			//$source_image = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $source_image;

	        if (file_exists($source_image))
	        {
                $cache_dir = dirname($source_image);
                $cache_base = basename($source_image);
                $paramlist = '2'.implode( $params );
                $cache_file = 'refl_' . md5($paramlist) . '_' . $cache_base;
                $cache_path = $cache_dir . DIRECTORY_SEPARATOR . $cache_file;

                if (file_exists($cache_path) && filemtime($cache_path) >= filemtime($source_image))
                {
                    // Use cached image
                    return $cache_path;
                }
	        }
	        else
	        {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('Cannot find or read source image'));
				return false;
	        }
		}
		else
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('No source image to reflect supplied'));
			return false;
		}

		//	bgc (the background colour used, defaults to black if not given)
		if (!isset($params['bgc']))
		{
			$red = 0;
			$green = 0;
			$blue = 0;
		}
		else
		{
			//	Extract the hex colour
			$hex_bgc = $params['bgc'];

			//	Does it start with a hash? If so then strip it
			$hex_bgc = str_replace('#', '', $hex_bgc);

			switch (strlen($hex_bgc))
			{
				case 6:
					$red = hexdec(substr($hex_bgc, 0, 2));
					$green = hexdec(substr($hex_bgc, 2, 2));
					$blue = hexdec(substr($hex_bgc, 4, 2));
					break;

				case 3:
					$red = substr($hex_bgc, 0, 1);
					$green = substr($hex_bgc, 1, 1);
					$blue = substr($hex_bgc, 2, 1);
					$red = hexdec($red . $red);
					$green = hexdec($green . $green);
					$blue = hexdec($blue . $blue);
					break;

				default:
					//	Wrong values passed, default to black
					$red = 0;
					$green = 0;
					$blue = 0;
			}
		}

		//	height (how tall should the reflection be?)
		if (isset($params['height']))
		{
			$output_height = $params['height'];

			//	Have they given us a percentage?
			if (substr($output_height, -1) == '%')
			{
				//	Yes, remove the % sign
				$output_height = (int) substr($output_height, 0, -1);

				//	Gotta love auto type casting ;)
				if ($output_height < 10)
				{
					$output_height = "0.0$output_height";
				}
				else
				{
					$output_height = "0.$output_height";
				}
			}
			else
			{
				$output_height = (int) $output_height;
			}
		}
		else
		{
			//	No height was given, so default to 50% of the source images height
			$output_height = 0.50;
		}

		$resize = 100.0;
		if (isset($params['resize']))
		{
			$resize = str_replace('%', '', $params['resize']);
			$resize = ($resize / 100);
		}

		if (isset($params['fade_start']))
		{
			if (strpos($params['fade_start'], '%') !== false)
			{
				$alpha_start = str_replace('%', '', $params['fade_start']);
				$alpha_start = (int) (127 * $alpha_start / 100);
			}
			else
			{
				$alpha_start = (int) $params['fade_start'];

				if ($alpha_start < 1 || $alpha_start > 127)
				{
					$alpha_start = 80;
				}
			}
		}
		else
		{
			$alpha_start = 80;
		}

		if (isset($params['fade_end']))
		{
			if (strpos($params['fade_end'], '%') !== false)
			{
				$alpha_end = str_replace('%', '', $params['fade_end']);
				$alpha_end = (int) (127 * $alpha_end / 100);
			}
			else
			{
				$alpha_end = (int) $params['fade_end'];

				if ($alpha_end < 1 || $alpha_end > 0)
				{
					$alpha_end = 0;
				}
			}
		}
		else
		{
			$alpha_end = 0;
		}

		/*
			----------------------------------------------------------------
			Ok, let's do it ...
			----------------------------------------------------------------
		*/

		//	How big is the image?
		$image_details = getimagesize($source_image);

		if ($image_details === false)
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('Not a valid image supplied, or this script does not have permissions to access it.'));
			return false;
		}
		else
		{
			$width = $image_details[0];
			$height = $image_details[1];
			$type = $image_details[2];
			$mime = $image_details['mime'];
		}

		//	Calculate the height of the output image
		if ($output_height < 1)
		{
			//	The output height is a percentage
			$new_height = $height * $output_height;
		}
		else
		{
			//	The output height is a fixed pixel value
			$new_height = $output_height;
		}

		//	Detect the source image format - only GIF, JPEG and PNG are supported. If you need more, extend this yourself.
		switch ($type)
		{
			case 1:
				//	GIF
				$source = imagecreatefromgif($source_image);
				break;

			case 2:
				//	JPG
				$source = imagecreatefromjpeg($source_image);
				break;

			case 3:
				//	PNG
				$source = imagecreatefrompng($source_image);
				break;

			default:
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('Unsupported image file format.'));
				return false;
		}


		/*
			----------------------------------------------------------------
			Build the reflection image
			----------------------------------------------------------------
		*/

		//	We'll store the final reflection in $output. $buffer is for internal use.
		$output = imagecreatetruecolor($width, $new_height);
		$buffer = imagecreatetruecolor($width, $new_height);

		//	Copy the bottom-most part of the source image into the output
		imagecopy($output, $source, 0, 0, 0, $height - $new_height, $width, $new_height);

		//	Rotate and flip it (strip flip method)
	    for ($y = 0; $y < $new_height; $y++)
	    {
	       imagecopy($buffer, $output, 0, $y, 0, $new_height - $y - 1, $width, 1);
	    }

		$output = $buffer;

		/*
			----------------------------------------------------------------
			Apply the fade effect
			----------------------------------------------------------------
		*/

		//	This is quite simple really. There are 127 available levels of alpha, so we just
		//	step-through the reflected image, drawing a box over the top, with a set alpha level.
		//	The end result? A cool fade into the background colour given.

		//	There are a maximum of 127 alpha fade steps we can use, so work out the alpha step rate

		$alpha_length = abs($alpha_start - $alpha_end);

		for ($y = 0; $y <= $new_height; $y++)
		{
			//  Get % of reflection height
			$pct = $y / $new_height;

			//  Get % of alpha
			if ($alpha_start > $alpha_end)
	        {
	            $alpha = (int) ($alpha_start - ($pct * $alpha_length));
	        }
	        else
	        {
	            $alpha = (int) ($alpha_start + ($pct * $alpha_length));
	        }

			imagefilledrectangle($output, 0, $y, $width, $y, imagecolorallocatealpha($output, $red, $green, $blue, $alpha));

		}


		/*
			----------------------------------------------------------------
			HACK - Build the reflection image by combining the source
			image AND the reflection in one new image!
			----------------------------------------------------------------
		*/
		$finaloutput = imagecreatetruecolor($width, $height+$new_height);
		imagecopy($finaloutput, $source, 0, 0, 0, 0, $width, $height);
		imagecopy($finaloutput, $output, 0, $height, 0, 0, $width, $new_height);
		if ($resize !== 100.0)
		{
			$resize_width = $width * $resize;
			$resize_height = ($height + $new_height) * $resize;
			imagedestroy($output);
			$output = imagecreatetruecolor( $resize_width, $resize_height);
			imagecopyresized($output, $finaloutput, 0, 0, 0, 0, $resize_width, $resize_height, $width, $height+$new_height );
		}
		else
		{
			$output = $finaloutput;
		}

		/*
			----------------------------------------------------------------
			Output our final PNG
			----------------------------------------------------------------
		*/

		//	If you'd rather output a JPEG instead of a PNG then pass the parameter 'jpeg' (no value needed) on the querystring

		if (isset($params['png']))
		{
         	imagepng($output, $cache_path);
			imagedestroy($output);
            return $cache_path;
		}
		else
		{
			if(!isset($params['jpeg']))
			{
				$quality = 90;
			}
			else
			{
				$quality = (int) $params['jpeg'];
			}

			if ($quality < 1 || $quality > 100)
			{
				$quality = 90;
			}

			//	JPEG (the final parameter = the quality, 0 = terrible, 100 = pixel perfect)
            imagejpeg($output, $cache_path, $quality);
			imagedestroy($output);
            return $cache_path;
		}
	}
}
?>