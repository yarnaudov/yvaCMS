
<script type="text/javascript" src="<?=base_url('modules/mod_pull/js/mod_pull.js');?>" ></script>

<form action="<?=site_url('pulls/vote');?>" method="post" >

		<ul class="pull<?=$pull['pull_id'];?>" >
			
				<li><?=$pull['title'];?></li>
				
				<?php foreach($pull['answers'] as $answer){ ?>
				<li>
					  <input type="radio" name="answers<?=$pull['pull_id'];?>" id="answer<?=$answer['answer_id'];?>" value="<?=$answer['answer_id']; ?>" class="<?=$pull['pull_id'];?>" >
					  <label for="answer<?=$answer['answer_id'];?>" ><?=$answer['title'];?></label>
				</li>
				<?php } ?>
			
			  <li>
			  	  <div class="actions" >
			  				<button type="submit" name="vote" disabled ><?=lang('label_mod_pull_vote');?></button>
			  				<a href="<?=current_url();?>?show_votes=<?=$pull['pull_id'];?>" class="show_votes" ><?=lang('label_mod_pull_show_votes');?></a>
			  	  </div>
			  	  <div class="loading" style="display: none;" >
			  	      <span><?=lang('label_mod_pull_please_wait');?></span>
			  	      <img src="<?=base_url('modules/mod_pull/images/loading.gif');?>" alt="loading..." >
			      </div>
			  </li>
			
		</ul>

</form>
