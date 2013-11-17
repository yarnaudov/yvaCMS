
function autoHeightIframe(frameid){
    
    var currentfr = parent.document.getElementById(frameid);
                           
    $(currentfr).css('display', 'block');
    $(currentfr).css('height', $('#main').height());

                                                                                                        
}
