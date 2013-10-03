
<?php if(count($this->Language->getLanguages(array('status' => 'yes'))) > 1 ){ ?>

<div class="box" >
    <span class="header" ><?php echo lang('label_translation');?></span>

    <div class="box_content" >
        <table class="box_table" cellpadding="0" cellspacing="0" >

            <tr>
                <td>
                    <select name="translation" >
                        <?php echo create_options('languages', 'id', 'title', $this->language_id, array('status' => 'yes'));?>
                    </select>
                    
                    <script type="text/javascript" >
            
                        <?php if($this->router->method == 'edit'){ ?>
                        $('select[name=translation]').bind('change', function(){
                            $('.required').each(function(){
                                $(this).removeClass('required');
                            });
                            $('form').append('<input type="hidden" name="uset_posts" value="true" >');
                            $('form').submit();
                        });
                        <?php }else{ ?>
                        $('select[name=translation]').attr('disabled', true);
                        <?php } ?>
                            
                    </script>
        
                </td>
            </tr>

        </table>
    </div>

</div>

<?php } ?>