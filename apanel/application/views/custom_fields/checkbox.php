<?php !isset($input_type) ? $input_type = 'checkbox' : ''; ?>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_options');?>:</label></th>
    <td>
        <ul id="checkboxes" >
            <?php $options = set_value('params[options]', isset($params['options']) ? $params['options'] : "");
                  $labels  = set_value('params[labels]', isset($params['labels']) ? $params['labels'] : "");
                  for($key = 0; $key < count($labels); $key++){ ?>
            <li>
                <input class="hidden"   type="hidden"      name="params[options][]" value="0" >
                <input class="checkbox" type="<?=$input_type;?>" name="params[options][]" value="1" <?=(isset($options[$key]) && $options[$key] == 1) ? "checked" : "";?> >
                <input class="text"     type="text"        name="params[labels][]"  value="<?=isset($labels[$key]) ? $labels[$key] : "";?>" >
                <a class="styled delete" >&nbsp;</a>
            </li>
            <?php } ?>
        </ul>
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th></th>
    <td>
        <a class="styled add" ><?=lang('label_add');?></a>
        <script type="text/javascript" >
    
            $('a.add').click(function(event){

                event.preventDefault();

                var li = $('#checkboxes li').first();

                if($(li).css('display') == 'none'){

                    $('#checkboxes li').first().removeAttr('style');

                }else{

                    var clone_li = $(li).clone();

                    $(clone_li).css('display', 'none');
                    $(clone_li).find('input.text').val('');
                    $(clone_li).find('input.checkbox').removeAttr('checked');
                    $('#checkboxes').append(clone_li);

                    $(clone_li).toggle(250);

                }

            });
    
            $('a.delete').live('click', function(event){

                event.preventDefault();

                $(this).parent().toggle('slow', function() {
                    if($('#checkboxes li').length > 1){
                        $(this).remove();
                    }
                });

            });
    
            $('input.checkbox').bind('click load', function(){

                $('input.checkbox').each(function(){
                    if($(this).is(':checked')){
                        $(this).parent().find('input.hidden').attr('disabled', true);
                    }
                    else{
                        $(this).parent().find('input.hidden').removeAttr('disabled'); 
                    }
                });

            });

            $('input.checkbox').trigger('load');
            $('#checkboxes').sortable();
    
        </script>
    </td>
</tr>