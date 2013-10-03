<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label class="multilang" ><?php echo lang('label_label');?>:</label></th>
    <td>
        <input type="text" name="params[multilang][label][<?php echo $this->language_id;?>]" value="<?php echo set_value('params[multilang][label]['.$this->language_id.']', isset($params['label'][$this->language_id]) ? $params['label'][$this->language_id] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label class="multilang" ><?php echo lang('label_field_text');?>:</label></th>
    <td>
        <input type="text" name="params[multilang][field_text][<?php echo $this->language_id;?>]" value="<?php echo set_value('params[multilang][field_text]['.$this->language_id.']', isset($params['field_text'][$this->language_id]) ? $params['field_text'][$this->language_id] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?php echo lang('label_button');?>:</label></th>
    <td>
        <select name="params[show_button]" >
            <?php echo create_options_array($this->config->item('yes_no'), set_value('params[show_button]', isset($params['show_button']) ? $params['show_button'] : ""));?>
        </select>
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label class="multilang" ><?php echo lang('label_button_text');?>:</label></th>
    <td>
        <input type="text" name="params[multilang][button_text][<?php echo $this->language_id;?>]" value="<?php echo set_value('params[multilang][button_text]['.$this->language_id.']', isset($params['button_text'][$this->language_id]) ? $params['button_text'][$this->language_id] : "");?>" >
    </td>
</tr>