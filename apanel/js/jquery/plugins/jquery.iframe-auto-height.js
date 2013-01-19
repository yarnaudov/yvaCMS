
function autoHeightIframe(frameid){
        
    var currentfr    = parent.document.getElementById(frameid);
    var iframeHeight = $(currentfr).contents().find("#main").height();
                          
    $(currentfr).css('display', 'block');
    $(currentfr).css('height', iframeHeight);
         
}
