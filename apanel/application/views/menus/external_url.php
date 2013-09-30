<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_external_url');?>:</label></th>
    <td>
        <input type="text" name="params[url]" value="<?=set_value('params[url]', isset($params['url']) ? $params['url'] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label>&nbsp;</label></th>
    <td>
        * <?=lang('msg_external_url');?>
    </td>
</tr>