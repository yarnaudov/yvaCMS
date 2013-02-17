<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label class="multilang" ><?=lang('label_text');?>:</label></th>
    <td>
        <input name="params[multilang][text][<?=$this->language_id;?>]" value="<?=set_value('params[multilang][text]['.$this->language_id.']', isset($params['text'][$this->language_id]) ? $params['text'][$this->language_id] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_separator');?>:</label></th>
    <td>
        <input name="params[separator]" value="<?=set_value('params[separator]', isset($params['separator']) ? $params['separator'] : "");?>" >
    </td>
</tr>