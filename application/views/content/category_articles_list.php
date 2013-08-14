
<h3><?=$category['title'];?></h3>

<ul class="articles_list" >
        
<?php foreach($articles as $article){ ?>
    
<li>
    <a href="<?=site_url('article:'.$article['alias']);?>" ><?=$article['title']; ?></a>
</li>

<?php } ?>

</ul>
