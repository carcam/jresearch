tinyMCE.importPluginLanguagePack("jresearch_automatic_citation","en");var _xmlHttpRequestObject=null;var _regexp=null;var _htmlTagsRegexp=null;var TinyMCE_JResearch_Automatic_Citation_Plugin={getInfo:function(){return{longname:"JResearch Automatic Citation",author:"Luis Galarraga",authorurl:"",infourl:"http://www.yoursite.com/docs/template.html",version:"0.1"}},initInstance:function(A){_regexp=new RegExp("(cite|citep|citeyear|nocite){((?:\\s*[-a-zA-Z0-9:.+_/]+\\s*,)*\\s*[-a-zA-Z0-9:.+_/]+\\s*)}|(bibliography){}","g");_htmlTagsRegexp=new RegExp("<(b|u|br|i|/br|/b|/u|/i)>","g")},getControlHTML:function(A){switch(A){case"generateBib":return tinyMCE.getButtonHTML(A,"lang_template_biblio","{$pluginurl}/images/bibliography.png","mceGenerateBib",true);case"citeP":return tinyMCE.getButtonHTML(A,"lang_template_citep","{$pluginurl}/images/citep.png","mceCiteP",true);case"cite":return tinyMCE.getButtonHTML(A,"lang_template_cite","{$pluginurl}/images/cite.png","mceCite",true);case"citeYear":return tinyMCE.getButtonHTML(A,"lang_template_citeyear","{$pluginurl}/images/citeyear.png","mceCiteYear",true);case"noCite":return tinyMCE.getButtonHTML(A,"lang_template_nocite","{$pluginurl}/images/nocite.png","mceNoCite",true)}return""},execCommand:function(D,A,C,E,B){switch(C){case"mceGenerateBib":tinyMCE.execInstanceCommand(D,"mceInsertContent",false,"bibliography{}");return true;case"mceCite":tinyMCE.execInstanceCommand(D,"mceInsertContent",false,"cite{");return true;case"mceCiteP":tinyMCE.execInstanceCommand(D,"mceInsertContent",false,"citep{");return true;case"mceCiteYear":tinyMCE.execInstanceCommand(D,"mceInsertContent",false,"citeyear{");return true;case"mceNoCite":tinyMCE.execInstanceCommand(D,"mceInsertContent",false,"nocite{");return true}return false},_getXmlHttpRequest:function(){if(_xmlHttpRequestObject==null){try{_xmlHttpRequestObject=new ActiveXObject("Msxml2.XMLHTTP")}catch(B){try{_xmlHttpRequestObject=new ActiveXObject("Microsoft.XMLHTTP")}catch(A){_xmlHttpRequestObject=null}}if(_xmlHttpRequestObject==null&&typeof XMLHttpRequest!="undefined"){try{_xmlHttpRequestObject=new XMLHttpRequest()}catch(B){_xmlHttpRequestObject=null}}if(_xmlHttpRequestObject==null&&window.createRequest){try{_xmlHttpRequestObject=window.createRequest()}catch(B){_xmlHttpRequestObject=null}}}return _xmlHttpRequestObject},handleEvent:function(H){if(H.type=="keyup"){var G=TinyMCE_JResearch_Automatic_Citation_Plugin._getXmlHttpRequest();if(G==null){return true}var I=tinyMCE.getContent();var E=tinyMCE.selectedInstance.selection.getBookmark();var C=_regexp.exec(I);var F=tinyMCE.selectedInstance.selection.getRng();if(!C){return true}var B=C[0];var D=C[1];var J=C[2];if(D==undefined||D==null||D==""){D="bibliography"}var A="index.php?option=com_jresearch&controller=publications&task=cite&command="+D+"&citekeys="+encodeURIComponent(J)+"&format=text";G.open("GET",A,true);G.onreadystatechange=function(L){if(G.readyState==4&&G.status==200){var K=G.responseText;if(K!=""){var M=I.replace(B,K);tinyMCE.setContent(M);E.start+=(TinyMCE_JResearch_Automatic_Citation_Plugin._calculateRawLength(K)-B.length);E.end+=(TinyMCE_JResearch_Automatic_Citation_Plugin._calculateRawLength(K)-B.length);tinyMCE.selectedInstance.selection.moveToBookmark(E)}}};G.send(null);return true}},_calculateRawLength:function(C){var A=null;var B=C.length;while(true){A=_htmlTagsRegexp.exec(C);if(A==null){break}B-=A[0].length}return B}};tinyMCE.addPlugin("jresearch_automatic_citation",TinyMCE_JResearch_Automatic_Citation_Plugin);