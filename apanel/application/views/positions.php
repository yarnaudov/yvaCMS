<tr>	      			
    <th><label><?php echo lang('label_position');?>:</label></th>
    <td>

        <input type="text" name="position" disabled style="display: none;" >

        <select name="position" >
            <?php echo create_options_array($positions, set_value('position', isset($position) ? $position : ""));?>
            <option value="value" >(<?php echo lang('label_value');?>)</option>
        </select>

        <script type="text/javascript" >
            
            $(document).ready(function() {
                        
                var position;

                $('select[name=position]').bind('change', function(){

                    if($(this).val() == 'value'){

                        $(this).css('display', 'none');
                        $(this).attr('disabled', true);
                        $('input[name=position]').css('display', 'inline');
                        $('input[name=position]').attr('disabled', false);
                        $('input[name=position]').focus();

                    }
                    else if($('#custom_fields').length == 1){

                        var posts = new Object();
			posts.extension      = '<?php echo $this->extension;?>';
			posts.model          = '<?php echo $this->model;?>';
			posts.element_id     = '<?php echo $this->element_id;?>';
			posts.extension_key  = $(this).val();
			
                        $.post('<?php echo site_url('home/ajax/load_custom_fields');?>', posts, function(data){
			    						    
                            $('#custom_fields').css('display', 'none');
			    document.getElementById('custom_fields').innerHTML = data;
                            $('#custom_fields').toggle('slow');

                        });

                    }

                });
            
                $('input[name=position]').blur(function(){

                    if($(this).val() == ''){

                      $(this).css('display', 'none');
                      $(this).attr('disabled', true);
                      $('select[name=position]').css('display', 'inline');
                      $('select[name=position]').attr('disabled', false);
                      $('select[name=position]').val(position);
                      $('select[name=position]').trigger('change');

                    }

                });
                
            });
            
        </script>
        
    </td>
</tr>