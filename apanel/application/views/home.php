<?php echo $this->load->view('messages');?>

<table style="width: 100%;" id="page_content" >
    
    <tr>
        <td style="width: 50%;vertical-align: top;" >
            
            <div class="home_menu" >
    
                <?php $menus = $this->Ap_menu->getMenus('general');
                      foreach($menus as $menu){     
                        if($menu['alias'] == 'components'){
                            $component_menu = $menu;
                            continue;
                        } ?>
                <div class="menu" >
                    <a href="<?php echo site_url($menu['alias']);?>" >
                        <img src="<?php echo base_url('img/icon'.ucfirst($menu['alias']).'.png');?>" >
                        <span><?php echo $menu['title_'.get_lang()];?></span>
                    </a>
                </div>
                <?php } ?>
                
                <div class="components" ><?php echo $component_menu['title_'.get_lang()];?></div>
                
                <?php $components = $this->Ap_menu->getComponents();
                      foreach($components as $component){ ?>
                <div class="menu" >
                    <a href="<?php echo site_url($component['alias']);?>" >
                        <img src="<?php echo base_url($component['alias'] . '/img/icon'.ucfirst(str_replace('components/', '', $component['alias'])).'.png');?>" >
                        <span><?php echo $component['title_'.get_lang()];?></span>
                    </a>
                </div>
                <?php } ?>
                
            </div>
            
        </td>
        
        
        <td style="width: 50%;vertical-align: top;" >
            
            <div class="home_info">
    
                <table class="list" border="0" cellpadding="0" cellspacing="2" >

                    <caption><?php echo lang('label_last_changed_articles');?></caption>
                    
                    <tr>
                        <th style="width:3%;" >#</th>
                        <th>Title</th>
                        <th style="width: 5%;" ><?php echo lang('label_status');?></th>
                        <th style="width: 6%;" ><?php echo lang('label_author');?></th>
                        <th style="width: 22%;" ><?php echo lang('label_date');?></th>
                    </tr>
                    
                    <?php foreach($articles as $numb => $article){
                            $row_class = $numb&1 ? "odd" : "even"; ?>
                    
                    <tr class="<?php echo $row_class;?>" >
                        <td><?php echo ($numb+1);?></td>
                        <td style="text-align: left;" >
                            <a href="<?php echo site_url('articles/edit/'.$article['id']);?>" >
                              <?php echo $article['title'];?>
                            </a>
                        </td>
                        <td>
                            <?php if($article['status'] == 'yes'){ ?>
                            <img class="status_img" alt="no"  src="<?php echo base_url('img/iconActive.png');?>" >
                            <?php }elseif($article['status'] == 'no'){ ?>
                            <img class="status_img" alt="yes" src="<?php echo base_url('img/iconBlock.png');?>" >
                            <?php } ?>
                        </td>
                        <td><?php echo $this->User->getDetails($article['created_by'], 'user');?></td>
                        <td><?php echo $article['created_on'];?></td>
                    </tr>
                    
                    <?php } ?>
                                    
                    <?php if(count($articles) == 0){ ?>
                    <tr>
                        <td colspan="9" ><?php echo lang('msg_no_results_found');?></td>
                    </tr>
                    <?php } ?>

                </table>

            </div>
            
        </td>
        
    </tr>
    
</table>
