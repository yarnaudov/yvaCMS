
function autoHeightIframe(frameid){
            
    
    //console.log('height: '+$('#main').height());
    
    var currentfr = parent.document.getElementById(frameid);
                           
    $(currentfr).css('display', 'block');
    $(currentfr).css('height', $('#main').height());

                                                                                                        
}
