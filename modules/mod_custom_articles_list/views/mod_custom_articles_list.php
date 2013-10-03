
<ul class="articles_list">
        
    <?php foreach($articles as $article){    
              $class = '';
              if($article['alias'] == $article_alias){
                  $class = 'current';
              } ?>
    
    <li <?php echo $class != '' ? 'class="'.$class.'"' : '';?> >
        <a href="<?php echo $this->menu_link.'/article:'.$article['alias'];?>" ><?php echo $article['title'];?></a>
    </li>

    <?php } ?>

</ul>