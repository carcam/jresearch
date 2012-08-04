/**
 * Plugin for J!Research automatic citation
 *
 * @author Luis Galárraga
 * @copyright Copyright  2009, Luis Galárraga, All rights reserved.
 * @license GNU/GPL
 */

(function() {

	tinymce.PluginManager.requireLangPack('jresearch');
	/**
	* Pattern used to search cite commands.
	*/
	var _regexp = null;


	/**
	* Pattern used to search for html tags in strings returned by server.
	*/
	var _htmlTagsRegexp = null;

	/** 
	* Plugin object
	*/
	var TinyMCE_JResearch_Automatic_Citation_Plugin = {		

		init : function(ed, url) {
			var t = this, s = {}, vp;

			t.editor = ed;
			
			// Initialize regular expresion for searching cite commands
			_regexp = new RegExp("(cite|citep|citeyear|nocite){((?:\\s*[-a-zA-Z0-9:.+_/]+\\s*,)*\\s*[-a-zA-Z0-9:.+_/]+\\s*)}|(bibliography){}", "g");				
			_htmlTagsRegexp = new RegExp("<(b|u|br|i|/br|/b|/u|/i)>", "g");

			// Register available commands
			ed.addCommand('mceGenerateBib', function(){
			      ed.execCommand('mceInsertContent', false, 'bibliography{}');
			});
			
			ed.addCommand('mceCite', function(){
			      ed.execCommand('mceInsertContent', false, 'cite{');
			}); 				

			ed.addCommand('mceCiteP', function(){
			      ed.execCommand('mceInsertContent', false, 'citep{');
			}); 				

			ed.addCommand('mceCiteYear', function(){
			      ed.execCommand('mceInsertContent', false, 'citeyear{');
			}); 				

			ed.addCommand('mceNoCite', function(){
			      ed.execCommand('mceInsertContent', false, 'nocite{');
			}); 				

			ed.onKeyUp.add(this._handleEvent);  

			// Now time to add buttons
			ed.addButton('cite_', {title : 'jresearch.cite', cmd : 'mceCite', image: url + '/images/cite.png'});
			ed.addButton('citep', {title : 'jresearch.citep', cmd : 'mceCiteP' , image: url + '/images/citep.png'});
			ed.addButton('citeyear', {title : 'jresearch.citeyear', cmd : 'mceCiteYear', image: url + '/images/citeyear.png'});
			ed.addButton('nocite', {title : 'jresearch.nocite', cmd : 'mceNoCite', image: url + '/images/nocite.png'});
			ed.addButton('bibliography', {title : 'jresearch.bibliography', cmd : 'mceGenerateBib', image: url + '/images/bibliography.png'});

		},

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
				  infourl : 'http://joomla-research.com/docs.html',
				  version : "2.0"
			  };
		  },



		  /**
		  * Scans the editor content to see if the user has entered one of the supported commands
		  * in order to send the instruction to the server via an AJAX request.
		  *
		  * @param {Editor} ed Editor reference
		  * @param {Event} e HTML editor event reference.
		  * @return true - pass to next handler in chain, false - stop chain execution
		  * @type boolean
		  */
		  _handleEvent : function(ed, e) {			      
			      // Get editor content
			  var _tempcontent = ed.getContent();
			  // Get cursor position
			  var marker = ed.selection.getBookmark();
			  // Search for commands
			  var matchingArray = _regexp.exec(_tempcontent);			
			  // To track current text node
			  var range = ed.selection.getRng();
			  
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
				      command = 'bibliography';
			  
			  // Construct the query string
			  var queryString = "index.php?option=com_jresearch&controller=publications&task=cite&command="+command+"&citekeys="+encodeURIComponent(citekeys)+"&format=text";
			  tinymce.util.XHR.send({
				url: queryString,
				method: "get",
				success: function(resultCite){      
				    if(resultCite != ''){
					  // Replace the command occurrence
					  var _tempcontent_new =_tempcontent.replace(matchedString, resultCite);						    
					  ed.setContent(_tempcontent_new);
		  
					// Recalculate cursor position for cite commands                    	                                    
					marker.start += (TinyMCE_JResearch_Automatic_Citation_Plugin._calculateRawLength(resultCite) - matchedString.length);
							    marker.end += (TinyMCE_JResearch_Automatic_Citation_Plugin._calculateRawLength(resultCite) - matchedString.length);	                   	
				      
					  //Now put cursor back in a suitable position
					  ed.selection.moveToBookmark(marker);
					  }                        
				  }

			  });
			  
			  return true;		    

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


	tinymce.create('tinymce.plugins.JResearch_Automatic_Citation', TinyMCE_JResearch_Automatic_Citation_Plugin);

	// Register plugin
	tinymce.PluginManager.add('jresearch', tinymce.plugins.JResearch_Automatic_Citation);
})();
