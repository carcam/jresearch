/**
 *  author:		Timothy Groves - http://www.brandspankingnew.net
 *	version:	1.2 - 2006-11-17
 *              1.3 - 2006-12-04
 *              2.0 - 2007-02-07
 *
 */

var useBSNns;

if (useBSNns)
{
	if (typeof(bsn) == "undefined")
		bsn = {}
	_bsn = bsn;
}
else
{
	_bsn = this;
}



if (typeof(_bsn.Autosuggest) == "undefined")
	_bsn.Autosuggest = {}













_bsn.AutoSuggest = function (fldID, param)
{
	// no DOM - give up!
	//
	if (!document.getElementById)
		return false;
	
	
	
	
	// get field via DOM
	//
	this.fld = _bsn.DOM.getElement(fldID);
	this.fldresults = _bsn.DOM.getElement(fldID+'result');
	this.fldnresults = _bsn.DOM.getElement('jform[n'+fldID+']');

	if (!this.fld)
		return false;
	
	
	
	
	// init variables
	//
	this.sInput 		= "";
	this.nInputChars 	= 0;
	this.aSuggestions 	= [];
	this.iHighlighted 	= 0;
	
	
	
	
	// parameters object
	//
	this.oP = (param) ? param : {};
	
	// defaults	
	//
	if (!this.oP.minchars)									this.oP.minchars = 1;
	if (!this.oP.method)									this.oP.meth = "get";
	if (!this.oP.varname)									this.oP.varname = "input";
	if (!this.oP.className)									this.oP.className = "autosuggest";
	if (!this.oP.timeout)									this.oP.timeout = 3500;
	if (!this.oP.delay)										this.oP.delay = 250;
	if (!this.oP.offsety)									this.oP.offsety = -5;
	if (!this.oP.shownoresults)								this.oP.shownoresults = true;
	if (!this.oP.noresults)									this.oP.noresults = this.lbl_noresults;
	if (!this.oP.maxheight && this.oP.maxheight !== 0)		this.oP.maxheight = 250;
	if (!this.oP.cache && this.oP.cache != false)			this.oP.cache = true;
	
	
	
	
	
	// set keyup handler for field
	// and prevent autocomplete from client
	//
	var pointer = this;
	
	// NOTE: not using addEventListener because UpArrow fired twice in Safari
	//_bsn.DOM.addEvent( this.fld, 'keyup', function(ev){ return pointer.onKeyPress(ev); } );
	
	this.fld.onkeypress 	= function(ev){ return pointer.onKeyPress(ev); }
	this.fld.onkeyup 		= function(ev){ return pointer.onKeyUp(ev); }
	
	this.fld.setAttribute("autocomplete","off");
}
















_bsn.AutoSuggest.prototype.onKeyPress = function(ev)
{
	
	var key = (window.event) ? window.event.keyCode : ev.keyCode;
	// set responses to keydown events in the field
	// this allows the user to use the arrow keys to scroll through the results
	// ESCAPE clears the list
	// TAB sets the current highlighted value
	//
	var RETURN = 13;
	var TAB = 9;
	var ESC = 27;
	
	var bubble = true;

	switch(key)
	{

		case RETURN:
			this.setHighlightedValue();
			bubble = false;
			break;


		case ESC:
			this.clearSuggestions();
			break;
	}

	return bubble;
}



_bsn.AutoSuggest.prototype.onKeyUp = function(ev)
{
	var key = (window.event) ? window.event.keyCode : ev.keyCode;
	
	// set responses to keydown events in the field
	// this allows the user to use the arrow keys to scroll through the results
	// ESCAPE clears the list
	// TAB sets the current highlighted value
	//

	var ARRUP = 38;
	var ARRDN = 40;
	
	var bubble = true;

	switch(key)
	{


		case ARRUP:
			this.changeHighlight(key);
			bubble = false;
			break;


		case ARRDN:
			this.changeHighlight(key);
			bubble = false;
			break;
		
		
		default:
			this.getSuggestions(this.fld.value);
	}

	return bubble;
	

}








_bsn.AutoSuggest.prototype.getSuggestions = function (val)
{
	
	// if input stays the same, do nothing
	//
	if (val == this.sInput)
		return false;

	
	// input length is less than the min required to trigger a request
	// reset input string
	// do nothing
	//
	if (val.length < this.oP.minchars)
	{
		this.sInput = "";
		return false;
	}
	
//Si hago algun cambio en el texto limpio el input hidden que contiene el id del evaluador
	if (typeof(this.oP.callback) == "function")
			this.oP.callback( null );

	// if caching enabled, and user is typing (ie. length of input is increasing)
	// filter results out of aSuggestions from last request
	//
	if (val.length>this.nInputChars && this.aSuggestions.length && this.oP.cache)
	{
		var arr = [];
		for (var i=0;i<this.aSuggestions.length;i++)
		{
			if (this.aSuggestions[i].value.substr(0,val.length).toLowerCase() == val.toLowerCase())
				arr.push( this.aSuggestions[i] );
		}
		
		this.sInput = val;
		this.nInputChars = val.length;
		this.aSuggestions = arr;
		
		this.createList(this.aSuggestions);
		
		
		
		return false;
	}
	else
	// do new request
	//
	{
		this.sInput = val;
		this.nInputChars = val.length;


		var pointer = this;
		clearTimeout(this.ajID);
		this.ajID = setTimeout( function() { pointer.doAjaxRequest() }, this.oP.delay );
	}

	return false;
}



_bsn.AutoSuggest.prototype.doAjaxRequest = function ()
{
	
	var pointer = this;
	
	// create ajax request
	var areat=$(this.oP.area);
	if(areat){
		areat = '&areat_id='+areat.value;
	}else{
		areat='';
	}
	var url = this.oP.script+this.oP.varname+"="+escape(this.fld.value)+areat;
	var meth = this.oP.meth;
	
	var onSuccessFunc = function (req) { pointer.setSuggestions(req) };
	var onErrorFunc = function (status) { /*alert("AJAX error: "+status); */ };

	var myAjax = new _bsn.Ajax();  
  myAjax.makeRequest( url, meth, onSuccessFunc, onErrorFunc );
}





_bsn.AutoSuggest.prototype.setSuggestions = function (req)
{
	this.aSuggestions = [];
	
	if (this.oP.json)
	{
		var jsondata = eval('(' + req.responseText + ')');
		
		if(jsondata.results.length >0 && jsondata.results[0].codigo != ''){
			for (var i=0;i<jsondata.results.length;i++)
			{
				this.aSuggestions.push(  { 'id':jsondata.results[i].id, 'value':jsondata.results[i].value, 'info':jsondata.results[i].info
				, 'codigo':jsondata.results[i].codigo, 'insti':jsondata.results[i].insti, 'dir':jsondata.results[i].dir
				, 'propi_id':jsondata.results[i].propi_id, 'tel':jsondata.results[i].tel, 'director':jsondata.results[i].director}  );
			}
		}else{
			for (var i=0;i<jsondata.results.length;i++)
			{
				this.aSuggestions.push(  { 'id':jsondata.results[i].id, 'value':jsondata.results[i].value, 'info':jsondata.results[i].info }  );
			}
		}
	}
	else
	{

		var xml = req.responseXML;
	
		// traverse xml
		//
		var results = xml.getElementsByTagName('results')[0].childNodes;

		for (var i=0;i<results.length;i++)
		{
			if (results[i].hasChildNodes())
				this.aSuggestions.push(  { 'id':results[i].getAttribute('id'), 'value':results[i].childNodes[0].nodeValue, 'info':results[i].getAttribute('info') }  );
		}
	
	}
	
	this.idAs = "as_"+this.fld.id;
	

	this.createList(this.aSuggestions);

}














_bsn.AutoSuggest.prototype.createList = function(arr)
{
	var pointer = this;
	
	
	// get rid of old list
	// and clear the list removal timeout
	//
	_bsn.DOM.removeElement(this.idAs);
	this.killTimeout();
	
	
	// create holding div
	//
	var div = _bsn.DOM.createElement("div", {id:this.idAs, className:this.oP.className});	
	
	var hcorner = _bsn.DOM.createElement("div", {className:"as_corner"});
	var hbar = _bsn.DOM.createElement("div", {className:"as_bar"});
	var header = _bsn.DOM.createElement("div", {className:"as_header"});
	header.appendChild(hcorner);
	header.appendChild(hbar);
	div.appendChild(header);
	
	
	
	
	// create and populate ul
	//
	var ul = _bsn.DOM.createElement("ul", {id:"as_ul"});
	
	
	
	
	// loop throught arr of suggestions
	// creating an LI element for each suggestion
	//
	for (var i=0;i<arr.length;i++)
	{
		// format output with the input enclosed in a EM element
		// (as HTML, not DOM)
		//
		var val = arr[i].value;
		var st = val.toLowerCase().indexOf( this.sInput.toLowerCase() );
		var output = val.substring(0,st) + "<em>" + val.substring(st, st+this.sInput.length) + "</em>" + val.substring(st+this.sInput.length);
		
		
		var span 		= _bsn.DOM.createElement("span", {}, output, true);
		if (arr[i].info != "")
		{
			var br			= _bsn.DOM.createElement("br", {});
			span.appendChild(br);
			var small		= _bsn.DOM.createElement("small", {}, arr[i].info);
			span.appendChild(small);
		}
		
		var a 			= _bsn.DOM.createElement("a", { href:"#" });
		
		var tl 		= _bsn.DOM.createElement("span", {className:"tl"}, " ");
		var tr 		= _bsn.DOM.createElement("span", {className:"tr"}, " ");
		a.appendChild(tl);
		a.appendChild(tr);
		
		a.appendChild(span);
		
		a.name = i+1;
		a.onclick = function () { pointer.setHighlightedValue(); return false; }
		a.onmouseover = function () { pointer.setHighlight(this.name); }
		
		var li 			= _bsn.DOM.createElement(  "li", {}, a  );
		
		ul.appendChild( li );
	}
	
	
	// no results
	//
	if (arr.length == 0)
	{
		var li 			= _bsn.DOM.createElement(  "li", {className:"as_warning"}, this.oP.noresults  );
		
		ul.appendChild( li );
	}
	
	
	div.appendChild( ul );
	
	
	var fcorner = _bsn.DOM.createElement("div", {className:"as_corner"});
	var fbar = _bsn.DOM.createElement("div", {className:"as_bar"});
	var footer = _bsn.DOM.createElement("div", {className:"as_footer"});
	footer.appendChild(fcorner);
	footer.appendChild(fbar);
	div.appendChild(footer);
	
	
	
	// get position of target textfield
	// position holding div below it
	// set width of holding div to width of field
	//
	var pos = _bsn.DOM.getPos(this.fld);
	
	div.style.left 		= pos.x + "px";
	div.style.top 		= ( pos.y + this.fld.offsetHeight + this.oP.offsety ) + "px";
	div.style.width 	= this.fld.offsetWidth + "px";
	
	
	
	// set mouseover functions for div
	// when mouse pointer leaves div, set a timeout to remove the list after an interval
	// when mouse enters div, kill the timeout so the list won't be removed
	//
	div.onmouseover 	= function(){ pointer.killTimeout() }
	div.onmouseout 		= function(){ pointer.resetTimeout() }


	// add DIV to document
	//
	document.getElementsByTagName("body")[0].appendChild(div);
	
	
	
	// currently no item is highlighted
	//
	this.iHighlighted = 0;
	
	
	
	
	
	
	// remove list after an interval
	//
	var pointer = this;
	this.toID = setTimeout(function () { pointer.clearSuggestions() }, this.oP.timeout);
}















_bsn.AutoSuggest.prototype.changeHighlight = function(key)
{	
	var list = _bsn.DOM.getElement("as_ul");
	if (!list)
		return false;
	
	var n;

	if (key == 40)
		n = this.iHighlighted + 1;
	else if (key == 38)
		n = this.iHighlighted - 1;
	
	
	if (n > list.childNodes.length)
		n = list.childNodes.length;
	if (n < 1)
		n = 1;
	
	
	this.setHighlight(n);
}



_bsn.AutoSuggest.prototype.setHighlight = function(n)
{
	var list = _bsn.DOM.getElement("as_ul");
	if (!list)
		return false;
	
	if (this.iHighlighted > 0)
		this.clearHighlight();
	
	this.iHighlighted = Number(n);
	
	list.childNodes[this.iHighlighted-1].className = "as_highlight";


	this.killTimeout();
}


_bsn.AutoSuggest.prototype.clearHighlight = function()
{
	var list = _bsn.DOM.getElement("as_ul");
	if (!list)
		return false;
	
	if (this.iHighlighted > 0)
	{
		list.childNodes[this.iHighlighted-1].className = "";
		this.iHighlighted = 0;
	}
}


_bsn.AutoSuggest.prototype.setHighlightedValue = function ()
{
	if (this.iHighlighted)
	{
		
		this.sInput = this.aSuggestions[ this.iHighlighted-1 ].value;
		this.appendMember(true);
		// move cursor to end of input (safari)
		//
		this.fld.focus();
		if (this.fld.selectionStart)
			this.fld.setSelectionRange(this.sInput.length, this.sInput.length);
		

		this.clearSuggestions();
		
		// pass selected object to callback function, if exists
		//
		if (typeof(this.oP.callback) == "function")
			this.oP.callback( this.aSuggestions[this.iHighlighted-1] );
	}else{
		this.appendMember(false);

	}
}

_bsn.AutoSuggest.prototype.appendMember = function(isInternal){
	var checkSpan = null;
	var aSpan;
	var aDelete;
	var content;
	var newLi;
	var upDownSpan;
	
	newLi = document.createElement('li');
	if(isInternal)
		content = document.createTextNode(this.aSuggestions[ this.iHighlighted-1 ].value);			
	else{
		content = this.fld.value;
		if(content.length <= 3 ){
			alert(this.lbl_minAuthorLengthMessage);
			return;
		}
		
	}
	
	nResults = parseInt(this.fldnresults.value);		
	// Time to verify it is not repeated
	for(i = 0; i <= nResults; i++){
		var author = document.getElementById(this.fld.name+i);
		if(author){
			textValue = isInternal?this.aSuggestions[ this.iHighlighted-1 ].id:content; 
			if(author.value == textValue){
				alert(this.lbl_repeatedAuthors);
				return;
			}
		}
	}
	
	nameSpan = document.createElement('span');
	nameSpan.setAttribute('id', 'span'+this.fld.name+nResults);
	
	if(isInternal)
		nameSpan.appendChild(content);
	else
		nameSpan.appendChild(document.createTextNode(content));		
	nameSpan.style.padding = '2px';
	
	aSpan = document.createElement('span');
	aSpan.style.padding = '2px';
	aDelete = document.createElement('a');
	aDelete.setAttribute('href', 'javascript:removeAuthor(\'li'+this.fld.name+nResults+'\')');	
	aDelete.appendChild(document.createTextNode(this.lbl_delete));
	aSpan.appendChild(aDelete);
	
	upDownSpan = document.createElement('span');
	upDownSpan.style.padding = '2px';
	
	aUp = document.createElement('a');
	aUp.setAttribute('href', 'javascript:moveUp(\'li'+this.fld.name+nResults+'\')');
	imgUp = document.createElement('img');
	imgUp.setAttribute('src', this.lbl_up_image);
	imgUp.style.width = '16px';
	imgUp.style.height = '16px';		
	imgUp.setAttribute('alt', 'Go up');
	aUp.appendChild(imgUp);
	
	aDown = document.createElement('a');
	aDown.setAttribute('href', 'javascript:moveDown(\'li'+this.fld.name+nResults+'\')');
	imgDown = document.createElement('img');
	imgDown.setAttribute('src', this.lbl_down_image);
	imgDown.setAttribute('alt', 'Go down');
	imgDown.style.width = '16px';
	imgDown.style.height = '16px';	
	aDown.appendChild(imgDown);
	
	upDownSpan.appendChild(aUp);
	upDownSpan.appendChild(aDown);
	if(this.projectLeaders){
		checkSpan = document.createElement('span');
		checkSpan.style.padding = '2px';		

		check = document.createElement('input');
		check.setAttribute('id', 'check_'+this.fld.name+nResults);
		check.setAttribute('name', 'check_'+this.fld.name+nResults);		
		check.setAttribute('type', 'checkbox');
		
		label = document.createElement('label');
		label.appendChild(document.createTextNode(this.lbl_projectLeader));
		label.setAttribute('for', 'check_'+this.fld.name+nResults);
		
		checkSpan.appendChild(label);
		checkSpan.appendChild(check);
	}
			
	hiddenInput = document.createElement('input');
	hiddenInput.setAttribute('type', 'hidden');		
	hiddenInput.setAttribute('id', 'jform[' + this.fld.name+nResults + ']');
	hiddenInput.setAttribute('name', 'jform[' + this.fld.name+nResults + ']');
	if(isInternal)
		hiddenInput.setAttribute('value', this.aSuggestions[ this.iHighlighted-1 ].id);
	else
		hiddenInput.setAttribute('value', this.fld.value);
		
	newLi.setAttribute('id', 'li'+this.fld.name+nResults);	

	nResults++;
	this.fldnresults.setAttribute('value', nResults);
	
	newLi.appendChild(nameSpan);		
	newLi.appendChild(aSpan);
	newLi.appendChild(upDownSpan);	
	newLi.appendChild(hiddenInput);
	if(checkSpan != null) newLi.appendChild(checkSpan);	
	this.fldresults.appendChild(newLi);
	this.fld.value = '';
	
}




function removeAuthor(controlName){
	var ili = document.getElementById(controlName);
	var iliparent;
	if(ili){
		iliparent = ili.parentNode;
		iliparent.removeChild(ili);		
	}
}

function moveUp(controlName){
	var ili = document.getElementById(controlName);
	var suffix = controlName.substring(2, controlName.length); 
	var inputHidden = document.getElementById('jform[' + suffix + ']');
	var nameSpan = document.getElementById('span'+suffix);
	
	if(ili){
		var iliparent = ili.parentNode;
		var iliPrevious = ili.previousSibling;
		if(iliPrevious){			
			var previousSuffix = iliPrevious.id.substring(2, iliPrevious.id.length);			
			var previousInput = document.getElementById('jform['+previousSuffix+']');
			var namePreviousSpan = document.getElementById('span'+previousSuffix);

			// Intercambio del valor de los inputs
			var spanTmp = nameSpan.firstChild.nodeValue;
			nameSpan.firstChild.nodeValue = namePreviousSpan.firstChild.nodeValue;
			namePreviousSpan.firstChild.nodeValue = spanTmp;

			var tmp = inputHidden.value;			
			inputHidden.value = previousInput.value;
			previousInput.value = tmp;						
		}
	}
}

function moveDown(controlName){
	var ili = document.getElementById(controlName);
	var suffix = controlName.substring(2, controlName.length); 
	var inputHidden = document.getElementById('jform['+ suffix + ']');
	var nameSpan = document.getElementById('span'+suffix);
	
	if(ili){
		var iliparent = ili.parentNode;
		var iliNext = ili.nextSibling;
		if(iliNext){			
			var nextSuffix = iliNext.id.substring(2, iliNext.id.length);			
			var nextInput = document.getElementById('jform['+nextSuffix+']');
			var nameNextSpan = document.getElementById('span'+nextSuffix);

			// Intercambio del valor de los inputs y etiquetas
			var spanTmp = nameSpan.firstChild.nodeValue;
			nameSpan.firstChild.nodeValue = nameNextSpan.firstChild.nodeValue;
			nameNextSpan.firstChild.nodeValue = spanTmp;

			var tmp = inputHidden.value;			
			inputHidden.value = nextInput.value;
			nextInput.value = tmp;						
		}
	}
	
}

_bsn.AutoSuggest.prototype.killTimeout = function()
{
	clearTimeout(this.toID);
}

_bsn.AutoSuggest.prototype.resetTimeout = function()
{
	clearTimeout(this.toID);
	var pointer = this;
	this.toID = setTimeout(function () { pointer.clearSuggestions() }, 1000);
}







_bsn.AutoSuggest.prototype.clearSuggestions = function ()
{
	
	this.killTimeout();
	
	var ele = _bsn.DOM.getElement(this.idAs);
	var pointer = this;
	if (ele)
	{
		var fade = new _bsn.Fader(ele,1,0,250,function () { _bsn.DOM.removeElement(pointer.idAs) });
	}
}










// AJAX PROTOTYPE _____________________________________________


if (typeof(_bsn.Ajax) == "undefined")
	_bsn.Ajax = {}



_bsn.Ajax = function ()
{
	this.req = {};
	this.isIE = false;
}



_bsn.Ajax.prototype.makeRequest = function (url, meth, onComp, onErr)
{
	
	if (meth != "POST")
		meth = "GET";
	
	this.onComplete = onComp;
	this.onError = onErr;
	
	var pointer = this;
	
	// branch for native XMLHttpRequest object
	if (window.XMLHttpRequest)
	{
		this.req = new XMLHttpRequest();
		this.req.onreadystatechange = function () { pointer.processReqChange() };
		this.req.open("GET", url, true); //
		this.req.send(null);
	// branch for IE/Windows ActiveX version
	}
	else if (window.ActiveXObject)
	{
		this.req = new ActiveXObject("Microsoft.XMLHTTP");
		if (this.req)
		{
			this.req.onreadystatechange = function () { pointer.processReqChange() };
			this.req.open(meth, url, true);
			this.req.send();
		}
	}
}


_bsn.Ajax.prototype.processReqChange = function()
{
	
	// only if req shows "loaded"
	if (this.req.readyState == 4) {
		// only if "OK"
		if (this.req.status == 200)
		{
			this.onComplete( this.req );
		} else {
			this.onError( this.req.status );
		}
	}
}










// DOM PROTOTYPE _____________________________________________


if (typeof(_bsn.DOM) == "undefined")
	_bsn.DOM = {}




_bsn.DOM.createElement = function ( type, attr, cont, html )
{
	var ne = document.createElement( type );
	if (!ne)
		return false;
		
	for (var a in attr)
		ne[a] = attr[a];
		
	if (typeof(cont) == "string" && !html)
		ne.appendChild( document.createTextNode(cont) );
	else if (typeof(cont) == "string" && html)
		ne.innerHTML = cont;
	else if (typeof(cont) == "object")
		ne.appendChild( cont );

	return ne;
}





_bsn.DOM.clearElement = function ( id )
{
	var ele = this.getElement( id );
	
	if (!ele)
		return false;
	
	while (ele.childNodes.length)
		ele.removeChild( ele.childNodes[0] );
	
	return true;
}









_bsn.DOM.removeElement = function ( ele )
{
	var e = this.getElement(ele);
	
	if (!e)
		return false;
	else if (e.parentNode.removeChild(e))
		return true;
	else
		return false;
}





_bsn.DOM.replaceContent = function ( id, cont, html )
{
	var ele = this.getElement( id );
	
	if (!ele)
		return false;
	
	this.clearElement( ele );
	
	if (typeof(cont) == "string" && !html)
		ele.appendChild( document.createTextNode(cont) );
	else if (typeof(cont) == "string" && html)
		ele.innerHTML = cont;
	else if (typeof(cont) == "object")
		ele.appendChild( cont );
}









_bsn.DOM.getElement = function ( ele )
{
	if (typeof(ele) == "undefined")
	{
		return false;
	}
	else if (typeof(ele) == "string")
	{
		var re = document.getElementById( ele );
		if (!re)
			return false;
		else if (typeof(re.appendChild) != "undefined" ) {
			return re;
		} else {
			return false;
		}
	}
	else if (typeof(ele.appendChild) != "undefined")
		return ele;
	else
		return false;
}







_bsn.DOM.appendChildren = function ( id, arr )
{
	var ele = this.getElement( id );
	
	if (!ele)
		return false;
	
	
	if (typeof(arr) != "object")
		return false;
		
	for (var i=0;i<arr.length;i++)
	{
		var cont = arr[i];
		if (typeof(cont) == "string")
			ele.appendChild( document.createTextNode(cont) );
		else if (typeof(cont) == "object")
			ele.appendChild( cont );
	}
}









_bsn.DOM.getPos = function ( ele )
{
	var ele = this.getElement(ele);

	var obj = ele;

	var curleft = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	}
	else if (obj.x)
		curleft += obj.x;


	var obj = ele;
	
	var curtop = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if (obj.y)
		curtop += obj.y;

	return {x:curleft, y:curtop}
}










// FADER PROTOTYPE _____________________________________________



if (typeof(_bsn.Fader) == "undefined")
	_bsn.Fader = {}





_bsn.Fader = function (ele, from, to, fadetime, callback)
{	
	if (!ele)
		return false;
	
	this.ele = ele;
	
	this.from = from;
	this.to = to;
	
	this.callback = callback;
	
	this.nDur = fadetime;
		
	this.nInt = 50;
	this.nTime = 0;
	
	var p = this;
	this.nID = setInterval(function() { p._fade() }, this.nInt);
}




_bsn.Fader.prototype._fade = function()
{
	this.nTime += this.nInt;
	
	var ieop = Math.round( this._tween(this.nTime, this.from, this.to, this.nDur) * 100 );
	var op = ieop / 100;
	
	if (this.ele.filters) // internet explorer
	{
		try
		{
			this.ele.filters.item("DXImageTransform.Microsoft.Alpha").opacity = ieop;
		} catch (e) { 
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			this.ele.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity='+ieop+')';
		}
	}
	else // other browsers
	{
		this.ele.style.opacity = op;
	}
	
	
	if (this.nTime == this.nDur)
	{
		clearInterval( this.nID );
		if (this.callback != undefined)
			this.callback();
	}
}



_bsn.Fader.prototype._tween = function(t,b,c,d)
{
	return b + ( (c-b) * (t/d) );
}