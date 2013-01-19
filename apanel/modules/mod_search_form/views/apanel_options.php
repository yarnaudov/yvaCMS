<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label class="multilang" ><?=lang('label_label');?>:</label></th>
    <td>
        <input type="text" name="params[label_<?=$this->trl;?>]" value="<?=set_value('params[label_'.$this->trl.']', isset($params['label_'.$this->trl]) ? $params['label_'.$this->trl] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label class="multilang" ><?=lang('label_field_text');?>:</label></th>
    <td>
        <input type="text" name="params[field_text_<?=$this->trl;?>]" value="<?=set_value('params[field_text_'.$this->trl.']', isset($params['field_text_'.$this->trl]) ? $params['field_text_'.$this->trl] : "");?>" >
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
        <input type="text" name="params[button_text_<?=$this->trl;?>]" value="<?=set_value('params[button_text_'.$this->trl.']', isset($params['button_text_'.$this->trl]) ? $params['button_text_'.$this->trl] : "");?>" >
    </td>
</tr>