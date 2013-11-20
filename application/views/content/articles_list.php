<?php
    $results_per_page = $menu['params']['number_per_page'];
    if($results_per_page != 'all'){
	$articles = array_chunk($articles, $results_per_page);
	$max_pages = count($articles);
    }
    else{
	$articles[0] = $articles;
	$max_pages = 1;	
    }
    
    $page = $this->input->get('page') == false ? 1 : $this->input->get('page');
    $page = ($page < 1 || $page > $max_pages) ? 1 : $page;
    
    $articles = count($articles) == 0 ? array() : $articles[$page-1];
?>

<ul class="articles_list" >

    <?php foreach($articles as $article){ ?>
    <li>
        <a href="<?php echo $menu['link'].'/article/'.$article['alias']; ?>" ><?php echo $article['title']; ?></a>
    </li>
    <?php } ?>

</ul>

<?php if($max_pages > 1){ ?>
<li class="pages" >

    <?php if(($page-1) < 0){ ?>
    <a class="disabled" href="#" >></a>
    <?php }else{ ?>
    <a class="btn" href="<?=current_url();?>?page=<?=$page-1;?>" ><</a>
    <?php } ?>

    <?php for($i = 1; $i <= $max_pages; $i++){ ?>
    <a <?=$page==$i ? 'class="current"' : ''?> href="<?=current_url();?>?page=<?=$i;?>" ><?=$i;?></a>
    <?php } ?>

    <?php if(($page+1) > $max_pages){ ?>
    <a class="disabled" href="#" >></a>
    <?php }else{ ?>
    <a href="<?=current_url();?>?page=<?=$page+1;?>" >></a>
    <?php } ?>

</li>
<?php } ?>
