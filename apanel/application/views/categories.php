<tr>	      			
    <th><label><?=lang('label_category');?>:</label></th>
    <td>
        <select name="category" >
            <?=create_options_array($categories, set_value('category', isset($category_id) ? $category_id : ""));?>
        </select>

        <script type="text/javascript" >
            
            $(document).ready(function() {
                
                if($('#custom_fields').length == 1){
                    
                    $('select[name=category]').change(function(){

                        $.get('<?=site_url('home/ajax/load_custom_fields');?>?extension=<?=$this->extension;?>&extension_key='+$(this).val(), function(data){
			    
			    if(data.search('script') != -1){
                                parent.$('.required').each(function(){
                                    $(this).removeClass('required');
                                });
                                parent.$('form').submit();
                                return;
                            }
			    
                            $('#custom_fields').css('display', 'none');
                            $('#custom_fields').html(data);
                            $('#custom_fields').toggle('slow');
                        });

                    });
                }
                
            });
            
        </script>
        
    </td>
</tr>