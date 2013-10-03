
<div id="page_content" >
    
    <table class="list" style="width: 450px;margin: -12px 0 5px -15px;" cellspacing="2" cellpadding="0" >

        <tr>
            <th style="width:3%;" >#</th>
            <th><?php echo lang('label_date');?></th>
            <th><?php echo lang('label_language');?></th>
            <th><?php echo lang('label_user');?></th>
        </tr>

        <?php foreach($history as $key => $value){ ?>
        <tr>
            <td><?php echo ($key+1);?></td>
            <td>
                <a href="<?php echo site_url('articles/edit/'.$value['article_id'].'/history/'.$value['updated_on']);?>" >
                    <?php echo $value['updated_on'];?>
                </a>
            </td>
            <td><?php echo $this->Language->getDetails($value['language_id'], 'title');?></td>
            <td><?php echo $this->User->getDetails($value['updated_by'], 'user');?></td>
        </tr>
        <?php } ?>

        <?php if(count($history) == 0){ ?>
        <tr>
            <td colspan="9" ><?php echo lang('msg_no_results_found');?></td>
        </tr>
        <?php } ?>
            
    </table>
    
</div>

<script type="text/javascript" >autoHeightIframe('jquery_ui_iframe');</script>