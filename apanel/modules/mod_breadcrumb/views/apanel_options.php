<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label class="multilang" ><?=lang('label_text');?>:</label></th>
    <td>
        <input name="params[text_<?=$this->trl;?>]" value="<?=set_value('params[text_'.$this->trl.']', isset($params['text_'.$this->trl]) ? $params['text_'.$this->trl] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_separator');?>:</label></th>
    <td>
        <input name="params[separator]" value="<?=set_value('params[separator]', isset($params['separator']) ? $params['separator'] : "");?>" >
    </td>
</tr>