<?php !isset($input_type) ? $input_type = 'checkbox' : '';
      !isset($action)     ? $action     = ''         : ''; ?>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_options');?>:</label></th>
    <td>
        <ul id="checkboxes" >
            <?php $options   = set_value('params[options]',   isset($params['options'])   ? $params['options']   : "");
		  !is_array($options) ? $options = array(0) : '';
                  $labels    = set_value('params[labels]',    isset($params['labels'])    ? $params['labels']    : "");
                  $optgroups = set_value('params[optgroups]', isset($params['optgroups']) ? $params['optgroups'] : "");
                  foreach($options as $key => $option){ ?>
            <li>
                <input class="option_hidden"   type="hidden"            name="params[options][<?=$key;?>]"   value="0" >
                <input class="option"          type="<?=$input_type;?>" name="params[options][<?=$key;?>]"   value="1" <?=(isset($options[$key]) && $options[$key] == 1) ? "checked" : "";?> >
                <input class="text"            type="text"              name="params[labels][<?=$key;?>]"    value="<?=isset($labels[$key]) ? $labels[$key] : "";?>" >
                
                <?php if($action == 'dropdown'){ ?>
                <input class="optgroup_hidden" type="hidden"            name="params[optgroups][<?=$key;?>]" value="0" >
                <input class="optgroup"        type="checkbox"          name="params[optgroups][<?=$key;?>]" value="1" <?=(isset($optgroups[$key]) && $optgroups[$key] == 1) ? "checked" : "";?> title="Make this option group">
                <?php } ?>
                
                <img src="<?=base_url('img/iconMove.png');?>" class="handle" alt="move" >
                
                <a class="styled delete" title="<?=lang('label_delete');?>" >&nbsp;</a>
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
    
            $('a.delete').die('click');
            $('input.option').die('click change');
            $('input.optgroup').die('click change');
    
            $('a.add').click(function(event){

                event.preventDefault();

                var li = $('#checkboxes li').first();

                if($(li).css('display') == 'none'){

                    $('#checkboxes li').first().removeAttr('style');

                }else{

                    var clone_li = $(li).clone();

                    $(clone_li).css('display', 'none');
                    $(clone_li).find('input.text').attr('value', '').attr('name', 'params[labels][]');
                    $(clone_li).find('input.option').removeAttr('checked').removeAttr('disabled').attr('name', 'params[options][]');
		    $(clone_li).find('input.option_hidden').attr('name', 'params[options][]');		    
                    $(clone_li).find('input.optgroup').removeAttr('checked').attr('name', 'params[optgroups][]');
		    $(clone_li).find('input.optgroup_hidden').attr('name', 'params[optgroups][]');
		    
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
                    else{
                        $(this).toggle('slow');
                    }
                });

            });
    
            $('input.option').live('click change', function(){
              
                $('input.option').each(function(){
       
                    if($(this).is(':checked')){
                        $(this).parent().find('input.option_hidden').attr('disabled', true);
                    }
                    else{
                        $(this).parent().find('input.option_hidden').removeAttr('disabled'); 
                    }

                });

            });

            $('input.optgroup').live('click change', function(){

                if($(this).is(':checked')){
                    $(this).parent().find('input.text').css('font-weight', 'bold');
                    $(this).parent().find('input.option').attr('disabled', true);
                    $(this).parent().find('input.optgroup_hidden').attr('disabled', true);
                }
                else{
                    $(this).parent().find('input.text').css('font-weight', 'normal');
                    $(this).parent().find('input.option').removeAttr('disabled');
                    $(this).parent().find('input.optgroup_hidden').removeAttr('disabled');
                }
                
            });

            $('input.option').first().trigger('change');
            
            $('input.optgroup').each(function(){
                $(this).trigger('change');
            });
            
            $('#checkboxes').sortable({ handle: ".handle" });
    
        </script>
    </td>
</tr>