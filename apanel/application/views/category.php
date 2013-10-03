<tr>	      			
    <th><label><?php echo lang('label_category');?>:</label></th>
    <td>
	
        <select name="category" >
            <?php echo create_options_array($categories, set_value('category', isset($category_id) ? $category_id : ""));?>
        </select>

        <script type="text/javascript" >
            
            $(document).ready(function() {
                
                if($('#custom_fields').length == 1){
                    
                    $('select[name=category]').change(function(){

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

                    });
                }
                
            });
            
        </script>
        
    </td>
</tr>