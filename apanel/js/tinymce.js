
function simple_editor(){
    
    tinyMCE.init({	
	// General options
	mode : "specific_textareas",
        language : LANG,
        editor_selector : "simple_editor",
        theme : "advanced",
        plugins : "safari,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",
        height: '100',
        width: '100%',
        
        content_css : 'templates/'+TEMPLATE+'/../css/global.css?' + new Date().getTime(),
        
        gecko_spellcheck : true,
        
        force_br_newlines : false, 
        force_p_newlines : true, 
        forced_root_block : 'p',
	invalid_elements : "script,applet",//iframe",
        
        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink",
        theme_advanced_buttons2 : "tablecontrols",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        
        relative_urls : true,
        remove_script_host : false,
        document_base_url : DOCUMENT_BASE_URL
    });
    
}

$(document).ready(function() {
    
    simple_editor();
    
    if($('.editor').attr('name') == 'text'){                
        var tmce_height = '500';
    }
    else{
        var tmce_height = '150';
    }
    
    tinyMCE.init({
        // General options
        mode : "specific_textareas",
        language : LANG,
        editor_selector : "editor",
        theme : "advanced",
        plugins : "safari,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",
        height: tmce_height,
        width: '100%',
        
        content_css : 'templates/'+TEMPLATE+'/../css/global.css?' + new Date().getTime(),
        
        gecko_spellcheck : true,
        
        force_br_newlines : false, 
        force_p_newlines : true, 
        forced_root_block : 'p',
	      invalid_elements : "script,applet",//iframe",
        
        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl,|,fullscreen",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        
        relative_urls : true,
        remove_script_host : false,
        document_base_url : DOCUMENT_BASE_URL,

        // Example content CSS (should be your site CSS)
        //content_css : "/apanel_new/css/tinymce.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "lists/template_list.js",
        external_link_list_url : "lists/link_list.js",
        external_image_list_url : "lists/image_list.js",
        media_external_list_url : "lists/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
                username : "Some User",
                staffid : "991234"
        },
        
        onchange_callback : addEvents,
        
        oninit : addEvents

    });
    
    
    
    function addEvents(){
     
        tinyMCE.dom.Event.add(tinyMCE.activeEditor.dom.select('img.module'), 'click', function(e) {
           $('a[lang=dialog-select-module]').trigger('click');
        });
     
    }    
    
});