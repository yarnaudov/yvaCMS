
<ul class="articles_list">
        
    <?php foreach($articles as $article){    
              $class = '';
              if($article['alias'] == $article_alias){
                  $class = 'current';
              } ?>
    
    <li <?=$class != '' ? 'class="'.$class.'"' : '';?> >
        <a href="<?=$this->menu_link.'/article:'.$article['alias'];?>" ><?=$article['title'];?></a>
    </li>

    <?php } ?>

</ul>