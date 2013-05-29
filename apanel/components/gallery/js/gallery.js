
$(document).ready(function() {
    
    $('input.file').bind('change', function(){
        
        var files = new Array();
        
        $(this.files).each(function(){
            files.push(this.name);
        });
        
        $('input.text').val(files.join(','));
        
    });

});