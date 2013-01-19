function scrollIntoView(element, container) {
       
    var containerTop    = $(container).scrollTop(); 
    var containerBottom = containerTop + $(container).height(); 
    var elemTop         = element.offsetTop;
    var elemBottom      = elemTop + $(element).height();

    if (elemTop < containerTop) {
        $(container).scrollTop(elemTop);
    } else if (elemBottom > containerBottom) {
        $(container).scrollTop(elemBottom - $(container).height());
    }
    
}