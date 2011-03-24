/**
 *	ImageFlow For Joomla 1.0.8 based on ImageFlow 0.9
 *
 *	This code is based on Michael L. Perrys Cover flow in Javascript.
 *	For he wrote that "You can take this code and use it as your own" [1]
 *	this is my attempt to improve some things. Feel free to use it! If
 *	you have any questions on it leave me a message in my shoutbox [2].
 *
 *	The reflection is generated server-sided by a slightly hacked
 *	version of Richard Daveys easyreflections [3] written in PHP.
 *
 *	The mouse wheel support is an implementation of Adomas Paltanavicius
 *	JavaScript mouse wheel code [4].
 *
 *	Thanks to Stephan Droste ImageFlow is now compatible with Safari 1.x.
 *
 *
 *	[1] http://www.adventuresinsoftware.com/blog/?p=104#comment-1981
 *	[2] http://shoutbox.finnrudolph.de/
 *	[3] http://reflection.corephp.co.uk/v2.php
 *	[4] http://adomas.org/javascript-mouse-wheel/
 */

var imf = {

/* Configuration variables */
conf_reflection_p : 0.5,         		// Sets the height of the reflection in % of the source image
conf_focus : 4,                  		// Sets the numbers of images on each side of the focussed one
conf_slider_width : 14,          		// Sets the px width of the slider div
conf_slider_cursor : 'e-resize', 		// Sets the slider cursor type: try "e-resize" default is 'default'
conf_thumbnail : null,					// Thumbnail classname

/* Id names used in the HTML */
conf_imageflow : 'imageflow',    		// Default is 'imageflow'
conf_loading : 'imageflow_loading',     // Default is 'imageflow_loading'
conf_images : 'imageflow_images',       // Default is 'imageflow_images'
conf_captions : 'imageflow_captions',   // Default is 'imageflow_captions'
conf_scrollbar : 'imageflow_scrollbar', // Default is 'imageflow_scrollbar'
conf_slider : 'imageflow_slider',       // Default is 'imageflow_slider'

/* Define global variables */
caption_id : 0,
new_caption_id : 0,
current : 0,
target : 0,
mem_target : 0,
timer : 0,
array_images : [],
new_slider_pos : 0,
dragging : false,
dragobject : null,
dragx : 0,
posx : 0,
new_posx : 0,
xstep : 150,
img_div : null,
imageflow_div : null,
scrollbar_div : null,
slider_div : null,
caption_div : null,
hide_slider : false,
hide_caption : false,
images_width : 0,
images_top : 0,
images_left : 0,
size : 0,
scrollbar_width : 0,
max_height : 0,
max : 0,
loaded : false,
max_conf_focus : 0,
visible_images : [],

step : function()
{
	if (imf.target < imf.current-1 || imf.target > imf.current+1)
	{
		imf.moveTo(imf.current + (imf.target-imf.current)/3);
		window.setTimeout(imf.step, 50);
		imf.timer = 1;
	}
	else
	{
		imf.timer = 0;
	}
},

glideTo : function(x, new_caption_id)
{
	/* Animate gliding to new x position */
	imf.target = x;
	imf.mem_target = x;
	if (imf.timer == 0)
	{
		window.setTimeout(imf.step, 50);
		imf.timer = 1;
	}

	/* Display new caption */
	imf.caption_id = new_caption_id;
	var caption = '&nbsp';

	if (! imf.hide_caption)
	{
		var image = imf.getImage( imf.caption_id );
		if (image != null)
		{
			caption = image.getAttribute('alt');
		}
	}
	imf.caption_div.innerHTML = caption;

	/* Set scrollbar slider to new position */
	if (imf.dragging == false)
	{
		imf.new_slider_pos = (imf.scrollbar_width * (-(x*100/((imf.max-1)*imf.xstep))) / 100) - imf.new_posx;
		imf.slider_div.style.marginLeft = (imf.new_slider_pos - imf.conf_slider_width) + 'px';
	}
},

hideImage : function( image, thumb )
{
	if( !image.ishidden )
	{
		image.ishidden = true;
		image.style.visibility = 'hidden';
		image.style.display = 'none';
		if (thumb != null)
		{
			thumb.style.visibility = 'hidden';
			thumb.style.display = 'none';
		}
	}
},

showImage : function( image, thumb )
{
	if (image.ishidden)
	{
		image.style.display = 'block';
		image.style.visibility = 'visible';
		if (thumb != null)
		{
			thumb.style.display = 'block';
			thumb.style.visibility = 'visible';
		}
		image.ishidden = false;
		imf.visible_images.push( image.i );
	}
},

moveTo : function(x)
{
	var image = null;
	var thumb = null;
	for (var index = 0; index < imf.visible_images.length; index++)
	{
		var vndx = imf.visible_images.shift();
		image = imf.getImage( vndx );
		thumb = imf.getThumb( vndx );

		/* Don't display images that are not imf.conf_focussed */
		if (image.max_focus_right < imf.mem_target || image.max_focus_left > imf.mem_target)
		{
			imf.hideImage( image, thumb );
		}
		else
		{
			imf.visible_images.push( vndx );
		}
	}

	imf.current = x;
	var zIndex = imf.max;
	var low = Math.max( 0, imf.caption_id - (imf.conf_focus + 2) );
	var high = Math.min( imf.max, imf.caption_id + (imf.conf_focus + 2));
	x += imf.xstep * low;


	/* Main loop */
	for (low; low < high; low++)
	{
		image = imf.getImage( low );
		thumb = imf.getThumb( low );

		/* Don't display images that are not imf.conf_focussed */
		if (image.max_focus_right < imf.mem_target || image.max_focus_left > imf.mem_target)
		{
			imf.hideImage( image, thumb );
		}
		else
		{
			var z = Math.sqrt(10000 + x * x) + 100;
			var xs = x / z * imf.size + imf.size;

			/* Process new image height and image width */
			var new_img_h = (image.h / image.w * image.pc) / z * imf.size;
			var new_img_w = 0;

			if ( new_img_h > imf.max_height )
			{
				new_img_h = imf.max_height;
				new_img_w = image.max_width;
			}
			else
			{
				new_img_w = image.pc / z * imf.size;
			}
/*			var thmb = imf.max_height / (imf.conf_reflection_p + 1);
			var rflct = imf.max_height - thmb;
			var new_img_top = (thmb - new_img_h) + imf.images_top + ((new_img_h / (imf.conf_reflection_p + 1)+ .5) * imf.conf_reflection_p); */
			var new_thumb_h = new_img_h / (imf.conf_reflection_p + 1);
			var new_img_top = (imf.images_width * .34 - new_img_h) + imf.images_top + (new_thumb_h * imf.conf_reflection_p + .5);

			/* Set new image properties */
			image.style.left = xs - (image.pc / 2) / z * imf.size + imf.images_left + 'px';
			/* Set image layer through zIndex */
			if ( x < 0 )
			{
				zIndex++;
			}
			else
			{
				zIndex--;
			}

			/* Change zIndex and onclick function of the focussed image */
			if ( image.i == imf.caption_id )
			{
				zIndex++;
			}
			if(new_img_w && new_img_h)
			{
				image.style.height = new_img_h + 'px';
				image.style.width = new_img_w + 'px';
				image.style.top = new_img_top + 'px';
				image.style.zIndex = zIndex;
				if (thumb != null)
				{
					thumb.style.left = image.style.left;
					thumb.style.height = new_thumb_h + 'px';
					thumb.style.width = image.style.width;
					thumb.style.top = image.style.top;
					thumb.style.zIndex = zIndex;
				}
			}
			imf.showImage( image, thumb );
		}
		x += imf.xstep;
	}
},

getThumb : function( index )
{
	var image = imf.img_div.childNodes.item(imf.array_images[index]);

	if ( image.childNodes.length == 0
	   ||imf.conf_thumbnail == null
	   ||imf.conf_thumbnail == "")
	{
		return null;
	}

	for (var i = 0; i < image.childNodes.length; i++)
	{
		if (image.childNodes[i].className == imf.conf_thumbnail)
		{
			return image.childNodes[i];
		}
	}
	return null;
},


findImage : function( image )
{
	if (image.nodeName == 'IMG' || image.childNodes.length == 0)
	{
		return image;
	}

	for (var i = 0; i < image.childNodes.length; i++)
	{
		if (image.childNodes[i].nodeName == 'IMG')
		{
			return image.childNodes[i];
		}
	}
	return image;
},

getImage : function( index )
{
	var image = null;

	try
	{
		image = imf.img_div.childNodes.item(imf.array_images[index]);
	}
	catch(e)
	{
		imf.cacheImages( false );
		image = imf.img_div.childNodes.item(imf.array_images[index]);
	}

	if (image.nodeType != 1 || (image.nodeName != 'A' && image.nodeName != 'IMG'))
	{
		imf.cacheImages( false );
		image = imf.img_div.childNodes.item(imf.array_images[index]);
	}
	if (image.nodeName == 'A' && image.nextExists && (image.nextSibling == null || image.nextSibling.nodeName != image.nextNodeName))
	{
		imf.cacheImages( false );
		image = imf.img_div.childNodes.item(imf.array_images[index]);
	}
	return imf.findImage( image );
},

cacheImages : function(onload)
{
	/* Cache EVERYTHING! */
	imf.array_images.length = 0;
	imf.max = imf.img_div.childNodes.length;
	var i = 0;

	for (var index = 0; index < imf.max; index++)
	{
		var image = imf.img_div.childNodes.item(index);
		if (image.nodeType == 1)
		{
			if (image.nodeName == 'A')
			{
				if (image.nextSibling != null)
				{
					image.nextExists = true;
					image.nextNodeName = image.nextSibling.nodeName;
				}
				else
				{
					image.nextExists = false;
				}
			}
			image = imf.findImage( image );
			if (image.nodeName == "IMG")
			{
				var x_pos = (-i * imf.xstep);
				imf.array_images[i] = index;
				image.max_focus_right = x_pos + imf.max_conf_focus;
				image.max_focus_left = x_pos - imf.max_conf_focus;
				image.i = i;

				/* Add width and height as attributes ONLY once onload */
				if(onload == true)
				{
					image.w = image.width;
					image.h = image.height;
					image.max_width = Math.round(image.w * imf.max_height / image.h);
					var thumb = imf.getThumb( i );
					imf.hideImage( image, thumb );
				}

				/* Check source image format. Get image height minus reflection height! */
				if ((image.w + 1) > (image.h / (imf.conf_reflection_p + 1)))
				{
					/* Landscape format */
					image.pc = 118;
				}
				else
				{
					/* Portrait and square format */
					image.pc = 100;
				}
				i++;
			}
		}
	}
	imf.max = imf.array_images.length;
},

/* Main function */
refresh : function(onload)
{
	/* Cache document objects in global variables */
	imf.imageflow_div = document.getElementById(imf.conf_imageflow);
	imf.img_div = document.getElementById(imf.conf_images);
	imf.scrollbar_div = document.getElementById(imf.conf_scrollbar);
	imf.slider_div = document.getElementById(imf.conf_slider);
	imf.caption_div = document.getElementById(imf.conf_captions);

	/* Cache global variables, that only change on refresh */
	imf.images_width = imf.img_div.offsetWidth;
	imf.images_top = imf.img_div.offsetTop;
	imf.images_left = imf.img_div.offsetLeft;
	imf.size = imf.images_width * 0.5;
	imf.scrollbar_width = imf.images_width * 0.6;
	imf.conf_slider_width = imf.conf_slider_width * 0.5;
	imf.max_height = imf.images_width * 0.51;
	imf.max_conf_focus = imf.conf_focus * imf.xstep;

	/* Change imageflow div properties */
	imf.imageflow_div.style.height = imf.max_height + 'px';

	/* Change images div properties */
	imf.img_div.style.height = imf.images_width * 0.338 + 'px';

	/* Change captions div properties */
	imf.caption_div.style.width = imf.images_width + 'px';
	imf.caption_div.style.marginTop = imf.images_width * 0.03 + 'px';

	/* Change scrollbar div properties */
	imf.scrollbar_div.style.marginTop = imf.images_width * 0.02 + 'px';
	imf.scrollbar_div.style.marginLeft = imf.images_width * 0.2 + 'px';
	imf.scrollbar_div.style.width = imf.scrollbar_width + 'px';

	/* Set slider attributes */
	imf.slider_div.onmousedown = function () { imf.dragstart(this); };
	imf.slider_div.style.cursor = imf.conf_slider_cursor;

	/* Cache EVERYTHING! */
	imf.cacheImages( onload );

	imf.caption_div.style.zIndex = imf.max + imf.conf_focus + 10;
	imf.scrollbar_div.style.zIndex = imf.max + imf.conf_focus + 11;
	imf.slider_div.style.zIndex = imf.max + imf.conf_focus + 12;

	/* Display images in current order */
	imf.moveTo(imf.current);
},

/* Show/hide element functions */
show : function(id)
{
	var element = document.getElementById(id);
	element.style.visibility = 'visible';
},

hide : function(id)
{
	var element = document.getElementById(id);
	element.style.visibility = 'hidden';
	element.style.display = 'none';
},

/* Hide loading bar, show content and initialize mouse event listening after loading */
initOnLoad : function()
{
	if(document.getElementById(imf.conf_imageflow))
	{
		imf.hide(imf.conf_loading);
		imf.refresh(true);
		imf.show(imf.conf_images);
		if (imf.hide_slider === false)
		{
			imf.show(imf.conf_scrollbar);
		}
		imf.initMouseWheel();
		imf.initMouseDrag();
		imf.glideTo(imf.current, imf.caption_id);
		imf.moveTo(5000);
		imf.loaded = true;
	}
},

/* Refresh ImageFlow on window resize */
resize : function()
{
	if(document.getElementById(imf.conf_imageflow) && imf.loaded === true) imf.refresh(false);
},

unload : function()
{
	if(navigator.userAgent.search(/msie/i)!= -1)
	{
		// nothing !!
	}
	else
	{
  		document = null;
	}
},

/* Handle the wheel angle change (delta) of the mouse wheel */
handle : function(delta)
{
	var change = false;
	if (delta > 0)
	{
		if (imf.caption_id >= 1)
		{
			imf.target = imf.target + imf.xstep;
			imf.new_caption_id = imf.caption_id - 1;
			change = true;
		}
	}
	else
	{
		if (imf.caption_id < (imf.max-1))
		{
			imf.target = imf.target - imf.xstep;
			imf.new_caption_id = imf.caption_id + 1;
			change = true;
		}
	}

	/* Glide to next (mouse wheel down) / previous (mouse wheel up) image */
	if (change == true)
	{
		imf.glideTo(imf.target, imf.new_caption_id);
	}
},

/* Event handler for mouse wheel event */
wheel : function(event)
{
	var delta = 0;
	if (!event) event = window.event;
	if (event.wheelDelta)
	{
		delta = event.wheelDelta / 120;
	}
	else
	if (event.detail)
	{
		delta = -event.detail / 3;
	}
	if (delta) imf.handle(delta);
	if (event.preventDefault) event.preventDefault();
	event.returnValue = false;
},

addEventListener : function (el, event, func) {
	try {
		el.addEventListener(event, func, false);
	} catch (e) {
		try {
			el.detachEvent('on'+ event, func);
			el.attachEvent('on'+ event, func);
		} catch (e) {
			el['on'+ event] = func;
		}
	}
},

/* Initialize mouse wheel event listener */
initMouseWheel : function()
{
	if(window.addEventListener) imf.imageflow_div.addEventListener('DOMMouseScroll', imf.wheel, false);
	imf.imageflow_div.onmousewheel = imf.wheel;
},

/* This function is called to drag an object (= slider div) */
dragstart : function(element)
{
	imf.dragobject = element;
	imf.dragx = imf.posx - imf.dragobject.offsetLeft + imf.new_slider_pos;
},

/* This function is called to stop dragging an object */
dragstop : function()
{
	imf.dragobject = null;
	imf.dragging = false;
},

/* This function is called on mouse movement and moves an object (= slider div) on user action */
drag : function(e)
{
	imf.posx = document.all ? window.event.clientX : e.pageX;
	if(imf.dragobject != null)
	{
		imf.dragging = true;
		imf.new_posx = (imf.posx - imf.dragx) + imf.conf_slider_width;

		/* Make sure, that the slider is moved in proper relation to previous movements by the glideTo function */
		if(imf.new_posx < ( - imf.new_slider_pos)) imf.new_posx = - imf.new_slider_pos;
		if(imf.new_posx > (imf.scrollbar_width - imf.new_slider_pos)) imf.new_posx = imf.scrollbar_width - imf.new_slider_pos;

		var slider_pos = (imf.new_posx + imf.new_slider_pos);
		var step_width = slider_pos / ((imf.scrollbar_width) / (imf.max-1));
		var image_number = Math.round(step_width);
		var new_target = (image_number) * -imf.xstep;
		var new_caption_id = image_number;

		imf.dragobject.style.left = imf.new_posx + 'px';
		imf.glideTo(new_target, new_caption_id);
	}
},

/* Initialize mouse event listener */
initMouseDrag : function()
{
	document.onmousemove = imf.drag;
	document.onmouseup = imf.dragstop;

	/* Avoid text and image selection while dragging  */
	document.onselectstart = function ()
	{
		return (!(imf.dragging == true));
	};
},

getKeyCode : function(event)
{
	event = event || window.event;
	return event.keyCode;
},

keyHandler : function(event)
{
	var charCode  = imf.getKeyCode(event);
	switch (charCode)
	{
		/* Right arrow key */
		case 39:
			imf.handle(-1);
			break;

		/* Left arrow key */
		case 37:
			imf.handle(1);
			break;

		default:
			break;
	}
}

};

imf.addEventListener(document, 'keydown', imf.keyHandler);
imf.addEventListener(window, 'load', imf.initOnLoad);
imf.addEventListener(window, 'unload', imf.unload);
imf.addEventListener(window, 'resize', imf.resize);