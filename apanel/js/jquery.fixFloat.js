function fixFloat(elem, id) {
  
  if(elem.length == 0){
      return;
  }
  
  var msie6 = $.browser == 'msie' && $.browser.version < 7;
  
  if (!msie6) {
    
    var elem_copy = document.createElement('div');
    $(elem_copy).attr('id', id);
    $(elem_copy).css('display', 'none');
    elem.clone().appendTo(elem_copy);
    $(elem).parent().append(elem_copy);
    
    var top = elem.offset().top - parseFloat(elem.css('margin-top').replace(/auto/, 0));
    
    $(window).bind('scroll load', function (event) {
      // what the y position of the scroll is
      var y = $(this).scrollTop();
      
      // whether that's below the form
      if (y >= top+10) {
        // if so, ad the fixed class
        $(elem_copy).css('display', '');
        $(elem_copy).addClass('fixed');
        
      } else {
        // otherwise remove it
        $(elem_copy).css('display', 'none');
        $(elem_copy).removeClass('fixed');
      }
    });
    
  }
    
}