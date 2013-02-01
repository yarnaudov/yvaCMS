
<?php 

foreach($custom_fields as $custom_field){

    $params = $custom_field['params'];
    
    echo '<tr><td colspan="2" class="empty_line" ></td></tr>';
    echo '<tr>';
    echo '  <th><label '.($custom_field['multilang'] == "yes" ? "class=multilang" : "").' >'.$custom_field['title'].':</label></th>';
    echo '  <td class="custom_field" >';
    
    $set_value = set_value('field'.$custom_field['id'], isset(${'field'.$custom_field['id']}) ? ${'field'.$custom_field['id']} : "");
    
    switch($custom_field['type']){
        
        case 'text':
            $set_value = ($set_value == "" && !isset(${'field'.$custom_field['id']})) ? $params['value'] : $set_value;
            echo '<input type="text" name="field'.$custom_field['id'].'" value="'.$set_value.'" >';
          break;
        
        case 'textarea':
            $set_value = ($set_value == "" && !isset(${'field'.$custom_field['id']})) ? $params['value'] : $set_value;
            echo '<textarea name="field'.$custom_field['id'].'" >'.$set_value.'</textarea>';
          break;
        
        case 'dropdown':
            
            echo '<select name="field'.$custom_field['id'].'" >';
            foreach($params['options'] as $key => $option){
          
                $selected = '';
                if($set_value == $key || ($set_value == "" && $option == 1)){
                    $selected = 'selected';
                }
                
                echo '<option '.$selected.' value="'.$key.'" >'.$params['labels'][$key].'</option>';   
            }
            echo '</select>';
            
          break;
        
        case 'checkbox':
        case 'radio':
            
            echo '<ul>';
            foreach($params['options'] as $key => $option){
            
                $checked = '';
                if(@in_array($key, $set_value) || ($set_value == "" && $option == 1)){
                    $checked = 'checked';
                }
                
                echo '<li>';
                echo '<input type="'.$custom_field['type'].'" 
                             name="field'.$custom_field['id'].'[]" 
                             id="option'.$custom_field['id'].$key.'" 
                             value="'.$key.'" 
                             '.$checked.' >';
                echo '<label for="option'.$custom_field['id'].$key.'" >'.$params['labels'][$key].'</label>';
                echo '</li>';
            }
            echo '</ul>';
            
          break;
      
        case 'date':
            echo '<input type="text" class="datepicker" name="field'.$custom_field['id'].'" value="'.$set_value.'" >';
          break;
        
    }
    
    echo '  </td>';
    echo '</tr>';

} 

?>

