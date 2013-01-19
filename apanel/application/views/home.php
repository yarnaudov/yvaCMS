
<?php $this->session->unset_userdata('good_msg');
      $this->session->unset_userdata('error_msg'); ?>

<table style="width: 100%;" id="page_content" >
    
    <tr>
        <td style="width: 50%;vertical-align: top;" >
            
            <div class="home_menu" >
    
                <?php $menus = $this->Adm_menu->getMainMenus();
                      foreach($menus as $menu){     
                        if($menu['alias'] == 'components'){
                            $component_menu = $menu;
                            continue;
                        } ?>
                <div class="menu" >
                    <a href="<?=site_url($menu['alias']);?>" >
                        <img src="<?=base_url('img/icon'.ucfirst($menu['alias']).'.png');?>" >
                        <span><?=$menu['title_'.get_lang()];?></span>
                    </a>
                </div>
                <?php } ?>
                
                <div class="components" ><?=$component_menu['title_'.get_lang()];?></div>
                
                <?php $components = $this->Adm_menu->getChildrenMenus($component_menu['id'], 0);
                      foreach($components as $component){ ?>
                <div class="menu" >
                    <a href="<?=site_url($component['alias']);?>" >
                        <img src="<?=base_url($component['alias'] . '/img/icon'.ucfirst(str_replace('components/', '', $component['alias'])).'.png');?>" >
                        <span><?=$component['title_'.get_lang()];?></span>
                    </a>
                </div>
                <?php } ?>
                
            </div>
            
        </td>
        
        
        <td style="width: 50%;vertical-align: top;" >
            
            <div class="home_info">
    
                <table class="list" border="0" cellpadding="0" cellspacing="2" >

                    <caption><?=lang('label_last_changed_articles');?></caption>
                    
                    <tr>
                        <th style="width:3%;" >#</th>
                        <th>Title</th>
                        <th style="width: 5%;" ><?=lang('label_status');?></th>
                        <th style="width: 6%;" ><?=lang('label_author');?></th>
                        <th style="width: 22%;" ><?=lang('label_date');?></th>
                    </tr>
                    
                    <?php foreach($articles as $numb => $article){
                            $row_class = $numb&1 ? "odd" : "even"; ?>
                    
                    <tr class="<?=$row_class;?>" >
                        <td><?=($numb+1);?></td>
                        <td style="text-align: left;" >
                            <a href="<?=site_url('articles/edit/'.$article['article_id']);?>" >
                              <?=$article['title_'.Language::getDefault()];?>
                            </a>
                        </td>
                        <td>
                            <?php if($article['status'] == 'yes'){ ?>
                            <img class="status_img" alt="no"  src="<?=base_url('img/iconActive.png');?>" >
                            <?php }elseif($article['status'] == 'no'){ ?>
                            <img class="status_img" alt="yes" src="<?=base_url('img/iconBlock.png');?>" >
                            <?php } ?>
                        </td>
                        <td><?=$this->User->getDetails($article['created_by'], 'user');?></td>
                        <td><?=$article['created_on'];?></td>
                    </tr>
                    
                    <?php } ?>
                                    
                    <?php if(count($articles) == 0){ ?>
                    <tr>
                        <td colspan="9" ><?=lang('msg_no_results_found');?></td>
                    </tr>
                    <?php } ?>

                </table>

            </div>
            
        </td>
        
    </tr>
    
</table>
