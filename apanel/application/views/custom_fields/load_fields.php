
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
    
    $class = '';
    switch($custom_field['mandatory']){
	case "yes": 
	    $class = "required";
	  break;

	case "email":
	    $class = "required email";
	  break;

	case "date":
	    $class = "required date";
	  break;
    } 
    
    echo "<tr><td colspan=\"2\" class=\"empty_line\" ></td></tr>\n";
    echo "<tr>\n";
    echo "  <th><label ".($custom_field['multilang'] == "yes" ? "class=multilang" : "")." >".$custom_field['title'].":</label></th>\n";
    echo "  <td class=\"custom_field\" >\n";
    
    if($custom_field['type'] != 'location'){
	$set_value = set_value('field'.$custom_field['id'], isset(${'field'.$custom_field['id']}) ? ${'field'.$custom_field['id']} : "");
    }
    
    switch($custom_field['type']){
        
        case 'text':
            $set_value = ($set_value == "" && !isset(${'field'.$custom_field['id']})) ? $params['value'] : $set_value;
            echo "<input class=\"".$class."\" type=\"text\" name=\"field".$custom_field['id']."\" value=\"".$set_value."\" >\n";
          break;
        
        case 'textarea':
            $set_value = ($set_value == "" && !isset(${'field'.$custom_field['id']})) ? $params['value'] : $set_value;
            echo "<textarea class=\"".$class." simple_editor\" name=\"field".$custom_field['id']."\" >".$set_value."</textarea>\n";
	    echo "<img src=\"".base_url('img/iconAdministration.png')."\" style=\"display:none;\" onload=\"simple_editor();\"  >";
          break;
        
        case 'dropdown':
            
            $optgroup = false;
            
            echo "<select class=\"".$class." name=\"field".$custom_field['id']."\" >\n";
            foreach($params['options'] as $key => $option){
          
                $selected = '';
                if($set_value == $key || ($set_value == "" && $option == 1)){
                    $selected = 'selected';
                }
                
                if($params['optgroups'][$key] == 1){
                    if($optgroup == true){
                        echo "</optgroup>\n";
                    }
                    $optgroup = true;
                    echo "<optgroup label=\"".$params['labels'][$key]."\" >\n";
                }
                else{
                    echo "<option ".$selected." value=\"".$key."\" >".$params['labels'][$key]."</option>\n";   
                }
                
                if($key+1 == count($params['options']) && $optgroup == true){
                    echo "</optgroup>\n";
                }
                
            }
            echo "</select>\n";
            
          break;
        
        case 'checkbox':
        case 'radio':
            
            $name_sufix = $custom_field['type'] == 'checkbox' ? '[]' : '';
            
	    if(count($params['options']) > 1){
		echo "<div class=\"menu_list\" >\n";
	    }
	    
            echo "<ul>\n";
            foreach($params['options'] as $key => $option){

                $checked = '';
                if(@in_array($key, $set_value) || ($key == $set_value && $set_value != "") || ($set_value == "" && $option == 1)){
                    $checked = 'checked';
                }
                
                echo "<li>\n";
                echo "<input type=\"".$custom_field['type']."\" 
                             name=\"field".$custom_field['id'].$name_sufix."\" 
                             id=\"option".$custom_field['id'].$key."\" 
                             value=\"".$key."\" 
                             ".$checked."
                             class=\"".$class."\" >\n";
                echo "<label for=\"option".$custom_field['id'].$key."\" >".$params['labels'][$key]."</label>\n";
                echo "</li>\n";
            }
            echo "</ul>\n";
            
	    if(count($params['options']) > 1){
		echo "</div>\n";
	    }
	    
          break;
      
        case 'date':
            echo "<input class=\"".$class."\" type=\"text\" class=\"datepicker\" name=\"field".$custom_field['id']."\" value=\"".$set_value."\" >\n";
          break;
      
        case 'media':
	    echo "<input class=\"".$class."\" class=\"image\" type=\"text\" readonly name=\"field".$custom_field['id']."\" id=\"custom_field_media\" value=\"".$set_value."\" style=\"width: 58%\">\n";                                       
	    echo "<a href   = \"".site_url('media/browse')."\" 
		     class  = \"load_jquery_ui_iframe\"
		     title  = \"".lang('label_browse')." ".lang('label_media')."\"
		     lang   = \"dialog-media-browser\"
		     target = \"custom_field_media\" >".lang('label_select')."</a>&nbsp;|&nbsp;<a href  = \"#\"
												  class = \"clear_jquery_ui_inputs\"
												  lang  = \"image\" >".lang('label_clear')."</a>\n";
          break;
     
	case 'location':
	  
	    $zoom = set_value('field'.$custom_field['id'].'[zoom]', isset(${'field'.$custom_field['id']}['zoom']) ? ${'field'.$custom_field['id']}['zoom'] : "");
	    $lat  = set_value('field'.$custom_field['id'].'[lng]',  isset(${'field'.$custom_field['id']}['lat'])  ? ${'field'.$custom_field['id']}['lat']  : "");
	    $lng  = set_value('field'.$custom_field['id'].'[lng]',  isset(${'field'.$custom_field['id']}['lng'])  ? ${'field'.$custom_field['id']}['lng']  : "");
	    
	    echo "<input type=\"hidden\" name=\"field".$custom_field['id']."[zoom]\" class=\"zoom".$custom_field['id']."\" value=\"".$zoom."\" >\n";
	    echo "<input type=\"hidden\" name=\"field".$custom_field['id']."[lat]\"  class=\"lat".$custom_field['id']."\"  value=\"".$lat."\">\n";
	    echo "<input type=\"hidden\" name=\"field".$custom_field['id']."[lng]\"  class=\"lng".$custom_field['id']."\"  value=\"".$lng."\" >\n";
	    
	    echo "<div class=\"map_canvas_custom_fields\" id=\"map_canvas".$custom_field['id']."\" ></div>\n";
	    
	    echo "<img src=\"".base_url('img/iconAdministration.png')."\" style=\"display:none;\" onload=\"initialize('".$custom_field['id']."', true);\"  >\n";
	    
	  break;
      
    }
    
    echo "  </td>\n";
    echo "</tr>\n";

} 

?>

