var link = null;
var divComments = null;
var post = null;
var showcomm = null;

function showComments(show, messageShow, messageHide){
	linkElement = getShowCommentsLink();	
	commentsContainer = getDivComments();
	if(show == 1){
		linkElement.setAttribute('href', 'javascript:showComments(0,\''+messageShow+'\',\''+messageHide+'\')');
		linkElement.innerHTML = messageHide;
		commentsContainer.style.display = 'block';		

	}else{
		linkElement.setAttribute('href', 'javascript:showComments(1,\''+messageShow+'\',\''+messageHide+'\')');
		linkElement.innerHTML = messageShow;
		commentsContainer.style.display = 'none';				
	}
	showStatus = getShowCommentsHiddenField();
	showStatus.value = show;
}

function getShowCommentsHiddenField(){
	if(showcomm == null)
		showcomm = document.getElementById('showcomm');
	return showcomm;	
}

function getDivComments(){
	if(divComments == null)
		divComments = document.getElementById('divcomments');
	return divComments;	
}

function getShowCommentsLink(){
	if(link == null)
		link = document.getElementById('showComments');
		
	return link;
}

function getLinkPost(){
	if(post == null)
		post = document.getElementById('postComment');
	
	return post;	
}

function postComment(){
	var form = document.getElementById('commentForm');
	form.style.display = 'block';
	var postLink = getLinkPost();
	postLink.style.display = 'none';
}

function validateCommentForm(f){
	if (document.formvalidator.isValid(f)) {
		return true; 
	}else {
		alert('Please, provide a content for your comment.');
		return false;
	}	
}
