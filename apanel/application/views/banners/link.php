<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_link');?>:</label></th>
    <td>
        <input type="text" name="params[link]" value="<?=set_value('params[link]', isset($params['link']) ? $params['link'] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_text');?>:</label></th>
    <td>
        <input type="text" name="params[text]" value="<?=set_value('params[text]', isset($params['text']) ? $params['text'] : "");?>" >
    </td>
</tr>