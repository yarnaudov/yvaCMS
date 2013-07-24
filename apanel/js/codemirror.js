
function loadCodeMirror(){

    try{

	var width = $('#code').width();		    
	var editor = CodeMirror.fromTextArea(document.getElementById('code'), {
	    mode: 'text/html', 
	    tabMode: 'indent', 
	    lineNumbers: true,
	    lineWrapping: true
	});

	editor.setSize(width, 400);

    }
    catch(err){}	
    
}