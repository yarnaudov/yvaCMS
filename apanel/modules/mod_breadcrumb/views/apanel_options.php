<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label class="multilang" ><?php echo lang('label_text');?>:</label></th>
    <td>
        <input type="text" name="params[multilang][text][<?php echo $this->language_id;?>]" value="<?php echo set_value('params[multilang][text]['.$this->language_id.']', isset($params['text'][$this->language_id]) ? $params['text'][$this->language_id] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?php echo lang('label_separator');?>:</label></th>
    <td>
        <input type="text" name="params[separator]" value="<?php echo set_value('params[separator]', isset($params['separator']) ? $params['separator'] : "");?>" >
    </td>
</tr>