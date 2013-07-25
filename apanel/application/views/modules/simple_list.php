
<div id="page_content" >
    
    <table class="menu_list modules_list_header" >
          
        <tr>
            <th style="width: 7px;" ></th>
            <th style="width: 212px;"><?=lang('label_title');?></th>
            <th><?=lang('label_type');?></th>
        </tr>
            
    </table>
    
    <div class="menu_list modules_list" >
        
        <table class="menu_list modules_list" >
      
            <?php foreach($modules as $numb => $module){ ?>
            <tr>
                <td style="width: 1%;">
                    <input id="module<?=$module['id'];?>" type="radio" value="<?=$module['id'];?>" name="modules" >
                </td>
                <td>
                    <label for="module<?=$module['id'];?>" >
                      <span class="title" ><?=$module['title'];?></span>
                      <span class="type"  ><?=lang('label_'.$module['type']);?></span>
                    </label>
                </td>
            </tr>    
            <?php } ?>  
            
        </table>
        
    </div>    
    
    <div class="modules_params" >
        
        <div class="modules_params_form" >
            
            <div>
                <label><?=lang('label_type');?>:</label>
                <select name="type" >
                    <option value="normal"       >Normal</option>
                    <option value="popup"        >Popup</option>
                    <option value="popup_iframe" >Popup in iframe</option>
                </select>
            </div>
            
            <div>            
                <label><?=lang('label_label');?>:</label>
                <input type="text" name="label" class="popup" >
            </div>
            
            <div>            
                <label><?=lang('label_button');?> <?=lang('label_cancel');?>:</label>
                <input type="checkbox" name="close_button" class="popup" >
            </div>
            
        </div>
        
        <button class="styled save" ><?=lang('label_save');?></button>
        <button class="styled cancel" ><?=lang('label_cancel');?></button>
        
    </div>
    
</div>

<script type="text/javascript" >

var module = parent.tinyMCE.activeEditor.selection.getNode().alt;
if(module){
    
    var params = module.split(';');
    for(var i in params){
        var param = params[i].split('=');

        if(param[0] == 'id'){
            $('input[id=module'+param[1]+']').attr('checked', true);
            scrollIntoView($('input[id=module'+param[1]+']').parent().parent()[0], $('div.modules_list'));
        }
        else if(param[0] == 'type'){
            $('select[name=type]').val(param[1]);
        }
        else if(param[0] == 'label'){
            $('input[name=label]').val(param[1]);
        }
        else if(param[0] == 'close_button'){
            $('input[name=close_button]').removeAttr('checked');
            if(param[1] == 'true'){
                $('input[name=close_button]').attr('checked', param[1]);
            }
        }

    }

}

$('.cancel').click(function(){
    //parent.tinyMCE.activeEditor.selection.select(parent.tinyMCE.activeEditor.dom.select('p')[0]);
    parent.tinyMCE.activeEditor.selection.collapse(false);
    parent.$( '#jquery_ui' ).dialog( "close" );
});

$('.save').click(function(){
    
    var id    = $('input[name=modules]:checked').val();
    var title = $('input[name=modules]:checked').parent().parent().find('.type').html();
    var type  = $('select[name=type]').val();
    
    var params = 'id='+id+';type='+type;
 
    if(type != 'normal'){
    
        var label        = $('input[name=label]').val();
        var close_button = $('input[name=close_button]').is(':checked');
        
        params = params+';label='+label+';close_button='+close_button;
    
    }
    
    parent.tinyMCE.execCommand('mceInsertContent', false, '<img src=\"<?=APANEL_DIR;?>/img/module.png\" alt=\"'+params+'\" class=\"module\" title=\"'+title+'\" >');
    parent.$('#jquery_ui').dialog('close');

    return false;
    
});

$('select[name=type]').bind('change', function(){

    $('.popup').removeAttr('disabled');
    if($(this).val() == "normal"){
        $('.popup').attr('disabled', true);
    }
    
});
$('select[name=type]').trigger('change');

</script>