
<div id="page_content" >
    
    <table class="list" style="width: 450px;margin: -12px 0 5px -15px;" cellspacing="2" cellpadding="0" >

        <tr>
            <th style="width:3%;" >#</th>
            <th><?=lang('label_date');?></th>
            <th><?=lang('label_language');?></th>
            <th><?=lang('label_user');?></th>
        </tr>

        <?php foreach($history as $key => $value){ ?>
        <tr>
            <td><?=($key+1);?></td>
            <td>
                <a href="<?=site_url('articles/edit/'.$value['article_id'].'/history/'.$value['updated_on']);?>" >
                    <?=$value['updated_on'];?>
                </a>
            </td>
            <td><?=$this->Language->getDetails($value['language_id'], 'title');?></td>
            <td><?=$this->User->getDetails($value['updated_by'], 'user');?></td>
        </tr>
        <?php } ?>

        <?php if(count($history) == 0){ ?>
        <tr>
            <td colspan="9" ><?=lang('msg_no_results_found');?></td>
        </tr>
        <?php } ?>
            
    </table>
    
</div>

<script type="text/javascript" >autoHeightIframe('jquery_ui_iframe');</script>