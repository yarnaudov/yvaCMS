<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_mod_advanced_html_source_file');?>:</label></th>
    <td> 
        <input type="text" name="params[source_file]" value="<?=set_value('params[source_file]', isset($params['source_file']) ? $params['source_file'] : "");?>" >           
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_mod_advanced_html_source_format');?>:</label></th>
    <td>    
	<?php $source_format = set_value('params[source_format]', isset($params['source_format']) ? $params['source_format'] : ""); ?>
        <select name="params[source_format]" >
            <option value="php"  <?=$source_format == 'php'  ? 'selected' : '';?> >php</option>
            <option value="json" <?=$source_format == 'json' ? 'selected' : '';?> >json</option>
	    <option value="ini"  <?=$source_format == 'ini'  ? 'selected' : '';?> >ini</option>
        </select>
    </td>
</tr>