/**
 * $RCSfile: editor_plugin_src.js,v $
 * $Revision: 1.12 $
 * $Date: 2006/02/22 20:06:23 $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2006, Moxiecode Systems AB, All rights reserved.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('jresearch_automatic_citation', 'en'); // <- Add a comma separated list of all supported languages

/****
 * Steps for creating a plugin from this template:
 *
 * 1. Change all "template" to the name of your plugin.
 * 2. Remove all the callbacks in this file that you don't need.
 * 3. Remove the popup.htm file if you don't need any popups.
 * 4. Add your custom logic to the callbacks you needed.
 * 5. Write documentation in a readme.txt file on how to use the plugin.
 * 6. Upload it under the "Plugins" section at sourceforge.
 *
 ****/
 
/**
 * Object used for AJAX requests.
 */ 
var _xmlHttpRequestObject = null; 

/**
 * Pattern used to search cite commands.
 */
var _regexp = null;


/**
 * Pattern used to search for html tags in strings returned by server.
 */
var _htmlTagsRegexp = null;


// Singleton class
var TinyMCE_JResearch_Automatic_Citation_Plugin = {
	/**
	 * Returns information about the plugin as a name/value array.
	 * The current keys are longname, author, authorurl, infourl and version.
	 *
	 * @returns Name/value array containing information about the plugin.
	 * @type Array 
	 */
	getInfo : function() {
		return {
			longname : 'JResearch Automatic Citation',
			author : 'Luis Galarraga',
			authorurl : '',
			infourl : 'http://www.yoursite.com/docs/template.html',
			version : "0.1"
		};
	},
	
	/**
	 * Gets executed when a TinyMCE editor instance is initialized.
	 *
	 * @param {TinyMCE_Control} Initialized TinyMCE editor control instance. 
	 */
	initInstance : function(inst) {
		// Initialize regular expresion for searching cite commands
		_regexp = new RegExp("(cite|citep|citeyear|nocite){((?:\\s*[-a-zA-Z0-9:.+_/]+\\s*,)*\\s*[-a-zA-Z0-9:.+_/]+\\s*)}|(bibliography){}", "g");				
		_htmlTagsRegexp = new RegExp("<(b|u|br|i|/br|/b|/u|/i)>", "g");
	},


	/**
	 * Returns the HTML code for a specific control or empty string if this plugin doesn't have that control.
	 * A control can be a button, select list or any other HTML item to present in the TinyMCE user interface.
	 * The variable {$editor_id} will be replaced with the current editor instance id and {$pluginurl} will be replaced
	 * with the URL of the plugin. Language variables such as {$lang_somekey} will also be replaced with contents from
	 * the language packs.
	 *
	 * @param {string} cn Editor control/button name to get HTML for.
	 * @return HTML code for a specific control or empty string.
	 * @type string
	 */
	getControlHTML : function(cn) {
		switch (cn) {
			case "generateBib":
				return tinyMCE.getButtonHTML(cn, 'lang_template_biblio', '{$pluginurl}/images/bibliography.png', 'mceGenerateBib', true);
			case "citeP":
				return tinyMCE.getButtonHTML(cn, 'lang_template_citep', '{$pluginurl}/images/citep.png', 'mceCiteP', true);
			case "cite":
				return tinyMCE.getButtonHTML(cn, 'lang_template_cite', '{$pluginurl}/images/cite.png', 'mceCite', true);
			case "citeYear":
				return tinyMCE.getButtonHTML(cn, 'lang_template_citeyear', '{$pluginurl}/images/citeyear.png', 'mceCiteYear', true);
			case "noCite":
				return tinyMCE.getButtonHTML(cn, 'lang_template_nocite', '{$pluginurl}/images/nocite.png', 'mceNoCite', true);
		}

		return "";
	},

	/**
	 * Executes a specific command, this function handles plugin commands.
	 *
	 * @param {string} editor_id TinyMCE editor instance id that issued the command.
	 * @param {HTMLElement} element Body or root element for the editor instance.
	 * @param {string} command Command name to be executed.
	 * @param {string} user_interface True/false if a user interface should be presented.
	 * @param {mixed} value Custom value argument, can be anything.
	 * @return true/false if the command was executed by this plugin or not.
	 * @type
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		switch (command) {
			// Remember to have the "mce" prefix for commands so they don't intersect with built in ones in the browser.
			case "mceGenerateBib":
				// It inserts \bibliography{} so editor will request the list of cited records.
				tinyMCE.execInstanceCommand(editor_id, 'mceInsertContent', false, "bibliography{}");
				return true;
			case "mceCite":
				// It inserts \cite{} command. The user should provide one or more citekeys.
				tinyMCE.execInstanceCommand(editor_id, 'mceInsertContent', false, "cite{");
				return true;
			case "mceCiteP":
				// It inserts \citep{} command. The user should provide one or more citekeys.
				tinyMCE.execInstanceCommand(editor_id, 'mceInsertContent', false, "citep{");
				return true;
			case "mceCiteYear":
				// It inserts \citeyear{} command. The user should provide one or more citekeys.
				tinyMCE.execInstanceCommand(editor_id, 'mceInsertContent', false, "citeyear{");
				return true;
			case "mceNoCite":
				// It inserts \nocite{} command. The user should provide one or more citekeys.
				tinyMCE.execInstanceCommand(editor_id, 'mceInsertContent', false, "nocite{");
				return true;
		}

		// Pass to next handler in chain
		return false;
	},

	
	/**
	 * Returns a reference to the XMLHTTPRequest object for AJAX requests.
	 * @return XMLHTTPRequest object for AJAX requests.
	 * @type XMLHTTPRequest
	 */
	_getXmlHttpRequest : function(){
		if(_xmlHttpRequestObject == null){
			try {
				_xmlHttpRequestObject = new ActiveXObject("Msxml2.XMLHTTP");
	 		} catch (e) {
			  	try {
	   				_xmlHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	  			} catch (E) {
	   				_xmlHttpRequestObject = null;
	  			}
	 		}
	
			if (_xmlHttpRequestObject == null && typeof XMLHttpRequest!= 'undefined') {
				try {
					_xmlHttpRequestObject = new XMLHttpRequest();
				} catch (e) {
					_xmlHttpRequestObject = null;
				}
			}
			
			if (_xmlHttpRequestObject == null && window.createRequest) {
				try {
					_xmlHttpRequestObject = window.createRequest();
				} catch (e) {
					_xmlHttpRequestObject = null;
				}
			}		
		}

		return _xmlHttpRequestObject;
	},

	/**
	 * Gets called when TinyMCE handles events such as keydown, mousedown etc. TinyMCE
	 * doesn't listen on all types of events so custom event handling may be required for
	 * some purposes.
	 *
	 * @param {Event} e HTML editor event reference.
	 * @return true - pass to next handler in chain, false - stop chain execution
	 * @type boolean
	 */
	handleEvent : function(e) {
		if (e.type == "keyup") {
		    // Get object for HTTP request
		    var xmlHttp = TinyMCE_JResearch_Automatic_Citation_Plugin._getXmlHttpRequest();
		    if(xmlHttp == null)
		    	return true;		   
			
		  	// Get editor content
		    var _tempcontent = tinyMCE.getContent();
		    // Get cursor position
		    var marker = tinyMCE.selectedInstance.selection.getBookmark();
			// Search for commands
			var matchingArray = _regexp.exec(_tempcontent);			
			// To track current text node
			var range = tinyMCE.selectedInstance.selection.getRng();
			
			// If no matches, just return
			if(!matchingArray)
				return true;
			
			// Get the matched string
		    var matchedString = matchingArray[0];		    
		    // Get the command
		    var command = matchingArray[1];		    
		    // Get the citekeys
		    var citekeys = matchingArray[2];				
			if(command == undefined || command == null || command == '')
				command = 'bibliography';		    // Construct the query string
		    var queryString = "index.php?option=com_jresearch&controller=publications&task=cite&command="+command+"&citekeys="+encodeURIComponent(citekeys)+"&format=text";
		    // Make the request
		    xmlHttp.open("GET", queryString, true);
		    xmlHttp.onreadystatechange = function(event){
		    	if(xmlHttp.readyState == 4 && xmlHttp.status == 200){			    		
		    		var resultCite = xmlHttp.responseText;
		    		if(resultCite != ''){
			    		// Replace the command occurrence
				    	var _tempcontent_new =_tempcontent.replace(matchedString, resultCite);						    
	                    tinyMCE.setContent(_tempcontent_new);
	
	                    // Recalculate cursor position for cite commands                    	                                    
	                    marker.start += (TinyMCE_JResearch_Automatic_Citation_Plugin._calculateRawLength(resultCite) - matchedString.length);
						marker.end += (TinyMCE_JResearch_Automatic_Citation_Plugin._calculateRawLength(resultCite) - matchedString.length);	                   	
	                    
	                    //Now put cursor back in a suitable position
		               	tinyMCE.selectedInstance.selection.moveToBookmark(marker);
		    		}                        
		    	}

		    };
		    
		    xmlHttp.send(null);
		    return true;		    
		}

	},
	
	/**
	 * Calculates the length of a string without considering the contained HTML tags.
	 * @param {string} String to analyze
	 * @return int String of the raw length of the string not considering html tags like b,strong,u,i.
	 */
	_calculateRawLength: function(str){
		var matchedArray = null;
		var strlength = str.length;
		while(true){
			matchedArray = _htmlTagsRegexp.exec(str);
			if(matchedArray ==  null)
				break;
			strlength -= matchedArray[0].length;
		}
		
		return strlength;
	}


};

// Adds the plugin class to the list of available TinyMCE plugins
tinyMCE.addPlugin("jresearch_automatic_citation", TinyMCE_JResearch_Automatic_Citation_Plugin);
