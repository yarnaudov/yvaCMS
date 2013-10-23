
<h3><?php echo $category['title'];?></h3>

<ul class="articles_list" >
        
<?php foreach($articles as $article){ ?>
    
<li>
    <a href="<?php echo site_url('article/'.$article['alias']);?>" ><?php echo $article['title']; ?></a>
</li>

<?php } ?>

</ul>
