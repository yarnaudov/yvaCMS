<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label class="multilang" ><?=lang('label_label');?>:</label></th>
    <td>
        <input type="text" name="params[label][<?=$this->language_id;?>]" value="<?=set_value('params[label]['.$this->language_id.']', isset($params['label'][$this->language_id]) ? $params['label'][$this->language_id] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label class="multilang" ><?=lang('label_field_text');?>:</label></th>
    <td>
        <input type="text" name="params[field_text][<?=$this->language_id;?>]" value="<?=set_value('params[field_text]['.$this->language_id.']', isset($params['field_text'][$this->language_id]) ? $params['field_text'][$this->language_id] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_button');?>:</label></th>
    <td>
        <select name="params[show_button]" >
            <?=create_options_array($this->config->item('yes_no'), set_value('params[show_button]', isset($params['show_button']) ? $params['show_button'] : ""));?>
        </select>
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label class="multilang" ><?=lang('label_button_text');?>:</label></th>
    <td>
        <input type="text" name="params[button_text][<?=$this->language_id;?>]" value="<?=set_value('params[button_text]['.$this->language_id.']', isset($params['button_text'][$this->language_id]) ? $params['button_text'][$this->language_id] : "");?>" >
    </td>
</tr>