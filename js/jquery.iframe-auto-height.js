
function autoHeightIframe(frameid){
                                                             
    var currentfr = parent.document.getElementById(frameid);
                           
    $(currentfr).css('display', 'block');
    if(currentfr.contentDocument && currentfr.contentDocument.body.offsetHeight){ //ns6 syntax
        $(currentfr).css('height', currentfr.contentDocument.body.offsetHeight+12);
        //currentfr.style.height = currentfr.contentDocument.body.offsetHeight+12;
    }             
    else if (currentfr.Document && currentfr.Document.body.scrollHeight){ //ie5+ syntax 
        $(currentfr).css('height', currentfr.contentDocument.body.offsetHeight+12);
        //currentfr.style.height = currentfr.Document.body.scrollHeight+2;
    }
                                                                                                        
}
