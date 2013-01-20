
<?php 

foreach($custom_fields as $custom_field){
    
    echo '<tr><td colspan="2" class="empty_line" ></td></tr>';
    echo '<tr>';
    echo '  <th><label '.($custom_field['multilang'] == "yes" ? "class=multilang" : "").' >'.$custom_field['title'].':</label></th>';
    echo '  <td>';
    
    $set_value = set_value('field'.$custom_field['id'], isset(${'field'.$custom_field['id']}) ? ${'field'.$custom_field['id']} : "");
    
    switch($custom_field['type']){
        
        case 'text':
            $set_value = $set_value == "" ? $custom_field['value'] : $set_value;
            echo '<input type="text" name="field'.$custom_field['id'].'" value="'.$set_value.'" >';
          break;
        
        case 'textarea':
            $set_value = $set_value == "" ? $custom_field['value'] : $set_value;
            echo '<textarea name="field'.$custom_field['id'].'" >'.$set_value.'</textarea>';
          break;
        
        case 'dropdown':
            
            $values_arr = explode(',', $custom_field['value']);
            foreach($values_arr as $value){
                $value = trim($value);
                $values[$value] = $value;
            }
            
            echo '<select name="field'.$custom_field['id'].'" >';
            echo    create_options_array($values, $set_value);
            echo '</select>';
            
          break;
        
        case 'checkbox':
            echo '<input type="checkbox" style="width: 16px;" name="field'.$custom_field['id'].'" value="'.$custom_field['value'].'" '.($set_value == $custom_field['value'] ? "checked" : "").' >';
          break;
      
        case 'date':
            echo '<input type="text" class="datepicker" name="field'.$custom_field['id'].'" value="'.$set_value.'" >';
          break;
        
    }
    
    echo '  </td>';
    echo '</tr>';

} 

?>

