
<?php if(count($custom_fields) > 0){ ?>
<tr><td colspan="2" class="empty_line" ></td></tr>
<tr>
    <td colspan="2" class="empty_line" >
        <fieldset style="border:none;border-top: 1px solid #aaa;padding-left: 10px;">
            <legend style="font-weight: bold;padding: 0 5px;" ><?=lang('label_custom_fields');?></legend>
        </fieldset>
    </td>
</tr>
<?php } ?>

<?php 

foreach($custom_fields as $custom_field){

    $params = $custom_field['params'];
    
    $required = $custom_field['required'] == "yes" ? "class=required" : "";
    
    echo '<tr><td colspan="2" class="empty_line" ></td></tr>';
    echo '<tr>';
    echo '  <th><label '.($custom_field['multilang'] == "yes" ? "class=multilang" : "").' >'.$custom_field['title'].':</label></th>';
    echo '  <td class="custom_field" >';
    
    $set_value = set_value('field'.$custom_field['id'], isset(${'field'.$custom_field['id']}) ? ${'field'.$custom_field['id']} : "");
    
    switch($custom_field['type']){
        
        case 'text':
            $set_value = ($set_value == "" && !isset(${'field'.$custom_field['id']})) ? $params['value'] : $set_value;
            echo '<input '.$required.' type="text" name="field'.$custom_field['id'].'" value="'.$set_value.'" >';
          break;
        
        case 'textarea':
            $set_value = ($set_value == "" && !isset(${'field'.$custom_field['id']})) ? $params['value'] : $set_value;
            echo '<textarea '.$required.' name="field'.$custom_field['id'].'" >'.$set_value.'</textarea>';
          break;
        
        case 'dropdown':
            
            $optgroup = false;
            
            echo '<select '.$required.' name="field'.$custom_field['id'].'" >';
            foreach($params['options'] as $key => $option){
          
                $selected = '';
                if($set_value == $key || ($set_value == "" && $option == 1)){
                    $selected = 'selected';
                }
                
                if($params['optgroups'][$key] == 1){
                    if($optgroup == true){
                        echo '</optgroup>';
                    }
                    $optgroup = true;
                    echo '<optgroup label="'.$params['labels'][$key].'" >';
                }
                else{
                    echo '<option '.$selected.' value="'.$key.'" >'.$params['labels'][$key].'</option>';   
                }
                
                if($key+1 == count($params['options']) && $optgroup == true){
                    echo '</optgroup>';
                }
                
            }
            echo '</select>';
            
          break;
        
        case 'checkbox':
        case 'radio':
            
            $name_sufix = $custom_field['type'] == 'checkbox' ? '[]' : '';
            
            echo '<ul>';
            foreach($params['options'] as $key => $option){
            
                $checked = '';
                if(@in_array($key, $set_value) || $key == $set_value || ($set_value == "" && $option == 1)){
                    $checked = 'checked';
                }
                
                echo '<li>';
                echo '<input type="'.$custom_field['type'].'" 
                             name="field'.$custom_field['id'].$name_sufix.'" 
                             id="option'.$custom_field['id'].$key.'" 
                             value="'.$key.'" 
                             '.$checked.'
                             '.$required.' >';
                echo '<label for="option'.$custom_field['id'].$key.'" >'.$params['labels'][$key].'</label>';
                echo '</li>';
            }
            echo '</ul>';
            
          break;
      
        case 'date':
            echo '<input '.$required.' type="text" class="datepicker" name="field'.$custom_field['id'].'" value="'.$set_value.'" >';
          break;
      
        case 'media':
	    echo '<input '.$required.' class="image" type="text" readonly name="field'.$custom_field['id'].'" id="custom_field_media" value="'.$set_value.'" style="width: 58%">';                                       
	    echo '<a href="'.site_url('media/browse').'" 
		     class = "load_jquery_ui_iframe"
		     title="'.lang('label_browse').' '.lang('label_media').'"
		     lang  = "dialog-media-browser"
		     target = "custom_field_media" >'.lang('label_select').'</a>&nbsp;|&nbsp;<a href  = "#"
												class = "clear_jquery_ui_inputs"
												lang  = "image" >'.lang('label_clear').'</a>';
          break;
        
    }
    
    echo '  </td>';
    echo '</tr>';

} 

?>

