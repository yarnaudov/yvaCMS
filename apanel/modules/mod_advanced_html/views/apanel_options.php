<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?php echo lang('label_mod_advanced_html_source_file');?>:</label></th>
    <td> 
        <input type="text" name="params[source_file]" value="<?php echo set_value('params[source_file]', isset($params['source_file']) ? $params['source_file'] : "");?>" >           
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?php echo lang('label_mod_advanced_html_source_format');?>:</label></th>
    <td>    
	<?php $source_format = set_value('params[source_format]', isset($params['source_format']) ? $params['source_format'] : ""); ?>
        <select name="params[source_format]" >
            <option value="php"  <?php echo $source_format == 'php'  ? 'selected' : '';?> >php</option>
            <option value="json" <?php echo $source_format == 'json' ? 'selected' : '';?> >json</option>
	    <option value="ini"  <?php echo $source_format == 'ini'  ? 'selected' : '';?> >ini</option>
            <option value="xml"  <?php echo $source_format == 'xml'  ? 'selected' : '';?> >xml</option>
        </select>
    </td>
</tr>