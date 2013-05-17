<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_value');?>:</label></th>
    <td>
        <textarea name="params[value]" ><?=set_value('params[value]', isset($params['value']) ? $params['value'] : "");?></textarea>
    </td>
</tr>