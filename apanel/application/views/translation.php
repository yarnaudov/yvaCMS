
<div class="box" >
    <span class="header" ><?=lang('label_translation');?></span>

    <div class="box_content" >
        <table class="box_table" cellpadding="0" cellspacing="0" >

            <tr>
                <td>
                    <select name="translation" >
                        <?=create_options('languages', 'id', 'title', $this->trl, array('status' => 'yes'));?>
                    </select>
                    
                    <script type="text/javascript" >
            
                        <?php if($this->router->method == 'edit'){ ?>
                        $('select[name=translation]').bind('change', function(){
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