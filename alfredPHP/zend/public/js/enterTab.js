$(document).ready(function() {
	
	$('body').on('keydown', 'input.enterTab, select.enterTab, textarea.enterTab', function(e) {
	    var keyCode = e.keyCode || e.which;
	    if (keyCode==13) {
	    	e.preventDefault();
	    	var focusable = $('input:enabled,a:enabled,select:enabled,button:enabled,textarea:enabled').filter(':visible:not(".notTab")');
	    	focusable.eq(focusable.index(this)+1).focus();
	    	return false;
	    }
	});

	$('body').on('keydown', 'input.enterSubmit, select.enterSubmit, textarea.enterSubmit', function(e) {
	    var keyCode = e.keyCode || e.which;
	    if (keyCode==13) {
	    	e.preventDefault();
	    	var focusable = $('input:enabled,a:enabled,select:enabled,button:enabled,textarea:enabled').filter(':visible:not(".notTab")');
	    	focusable.eq(focusable.index(this)+1).click();
	    	return false;
	    }
	});
	
});