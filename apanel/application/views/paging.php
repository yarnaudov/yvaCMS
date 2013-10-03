<?php  
    $max_visible_pages = $this->config->item('max_visible_pages');
    $flag_less = true;
    $flag_more = true;
?>

<div id="paging" >

    <label><?php echo lang('label_display');?> #</label>    
    <select name="page_results" >
        <?php echo create_options_array($this->config->item('results_limits'), $limit);?>
    </select>

    <?php if($max_pages > 1){ ?>
    
    <ul class="pagination" >

        <?php if($this->page == 1){ ?>
            <li class="previous-off" ><?php echo lang('label_prev');?></li>
            <li class="current" >1</li>
        <?php }else{ ?>
            <li class="previous" ><a href="<?php echo current_url();?>?page=<?php echo ($this->page-1);?>" ><?php echo lang('label_prev');?></a></li>
            <li><a class="active" href="<?php echo current_url();?>?page=1" >1</a></li>
        <?php } ?>

        <?php for($i=2; $i < $max_pages; $i++){

            if($i < $this->page-($max_visible_pages/2) && $i < $max_pages-$max_visible_pages){
                if($flag_less == true){
                    echo "<li class=\"previous-off\" >. . .</li>\n";
                }
                $flag_less = false;
                continue;
            }
            elseif($i > $this->page+($max_visible_pages/2) && $i > $max_visible_pages){
                if($flag_more == true){
                    echo "<li class=\"previous-off\" >. . .</li>\n";
                }
                $flag_more = false;
                continue;
            }

            if($this->page == $i){
                echo "<li class=\"current\" >".$i."</li>\n";
            }
            else{
                echo "<li><a class=\"active\" href=\"".current_url()."?"."page=".$i."\" >".$i."</a></li>\n";
            } 

        } ?>

        <?php if($this->page == $max_pages){ ?>                    
            <li class="current" ><?php echo $max_pages;?></li>
            <li class="previous-off" ><?php echo lang('label_next');?></li>
        <?php }else{ ?>
            <li><a class="active" href="<?php echo current_url();?>?page=<?php echo $max_pages;?>" ><?php echo $max_pages;?></a></li>
            <li class="previous" ><a href="<?php echo current_url();?>?page=<?php echo ($this->page+1);?>" ><?php echo lang('label_next');?></a></li>
        <?php } ?>

    </ul>
    
    <span>
        <?php echo lang('label_page')." ".$this->page." ".lang('label_of')." ".$max_pages;?>
    </span>
    
    <?php } ?>

</div>