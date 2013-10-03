<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?php echo lang('label_html');?>:</label></th>
    <td>
        <textarea id="code" style="height: 100px;" name="params[html]" ><?php echo set_value('params[html]', isset($params['html']) ? $params['html'] : "");?></textarea>	
	<img src="<?php echo base_url('img/iconAdministration.png');?>" style="display:none;" onload="loadCodeMirror();"  >	
    </td>
</tr>