
<ul>
        
    <?php foreach($articles as $article){    
              $class = '';
              if($article['alias'] == $article_alias){
                  $class = 'current';
              } ?>
    
    <li <?=$class != '' ? 'class="'.$class.'"' : '';?> >
        <a href="<?=site_url('article:'.$article['alias']);?>" ><?=$article['title'];?></a>
    </li>

    <?php } ?>

</ul>