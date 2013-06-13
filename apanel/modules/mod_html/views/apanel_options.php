<tr>
    <td colspan="2" class="empty_line" >
	<?php $this->jquery_ext->add_plugin("codemirror"); ?>
            
        <script>
	    $(function(){
		try{
		    var editor = CodeMirror.fromTextArea(document.getElementById('code'), {mode: 'text/html', tabMode: 'indent', lineNumbers: true});
		}
		catch(err){}	    
	    });
	</script>
	
    </td>
</tr>
                                
<tr>	      			
    <th><label><?=lang('label_html');?>:</label></th>
    <td>
        <textarea id="code" style="height: 100px;" name="params[html]" ><?=set_value('params[html]', isset($params['html']) ? $params['html'] : "");?></textarea>
    </td>
</tr>