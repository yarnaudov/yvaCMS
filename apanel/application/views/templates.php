<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?php echo lang('label_template');?>:</label></th>
    <td>
                
        <select name="<?php echo $name;?>" >
            
            <?php if(isset($default) && $default == true){ ?>
            <option value="default" ><?php echo lang('label_default');?></option>
            <?php } ?>

            <?php foreach($this->templates as $template_dir => $template_files ){

                      echo '<optgroup label="'.$template_dir.'" >';

                      foreach($template_files as $template_file){
                          echo '<option '.($template == $template_file ? "selected" : "").' value="'.$template_file.'" >'.$template_file.'</option>';
                      }
                      
                      echo '</optgroup>';

                  } ?>

        </select>
    </td>
</tr>