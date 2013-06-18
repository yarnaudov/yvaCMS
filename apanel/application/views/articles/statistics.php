
<!--[if IE]><script language="javascript" type="text/javascript" src="<?=base_url('js/jqplot/excanvas.js');?>"></script><![endif]-->

<form name="list" action="<?=current_url(true);?>" method="post" >
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconArticles_25.png');?>" >
            <span><?=lang('label_articles');?></span>
	    <span>&nbsp;»&nbsp;</span>
            <span><?=lang('label_statistics');?>Statistics</span>
        </div>
	
	<div class="actions" >
            <a href="<?=site_url('articles');?>" class="styled cancel" ><?=lang('label_cancel');?></a>            
	</div>
        
    </div>
    <!-- end page header -->
    
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>
    
    <!-- start page content -->
    <div id="page_content" >

	<div id="filter_content" >
		
            <div class="search" >
		<label>Start date:</label>
		<input type="text" class="start_end_dates datepicker" name="filters[start_date]" value="<?=$filters['start_date'];?>" >
		<label>End date:</label>
		<input type="text" class="start_end_dates datepicker" name="filters[end_date]" value="<?=$filters['end_date'];?>" >
                <button class="styled" type="submit" name="search" ><?=lang('label_search');?></button>
            </div>
		
            <div class="filter" >
			
                <select name="filters[article]" >
                    <option value="none" > - <?=lang('label_select');?> <?=lang('label_article');?> - </option>
                    <?=create_options_array($articles, isset($filters['article']) ? $filters['article'] : "");?>
                </select>

            </div>
		
	</div>
	
	<div id="chart1" style="height:300px; width:100%;"></div>

	<!--<?php '<pre>'.print_r($line1).'</pre>'; ?>-->
	
	<script class="code" type="text/javascript">
	    
	    var line1= JSON.parse('<?=json_encode($line1);?>');
	    console.log(line1);
	    var plot1 = $.jqplot ('chart1', [line1], {
		axes:{
		    xaxis:{
			renderer: $.jqplot.DateAxisRenderer,
			tickOptions:{formatString:'%b&nbsp;%#d'},
			min: '<?=$filters['start_date'];?>',
			max: '<?=$filters['end_date'];?>',
			tickInterval: '1 day'
		    },
		    yaxis:{
		    
		    }
		},
		series:[{lineWidth: 2}],
		highlighter: {
		    show: true,
		    sizeAdjust: 7.5
		},
		cursor: {
		    show: false
		}
	    });
	    
	    $('.start_end_dates').datepicker({
		showOn: 'focus',
		dateFormat: 'yy-mm-dd',
		buttonImage: '<?=base_url('img/iconCalendar.png');?>',
		buttonImageOnly: true
	    });
		    
	</script>

    </div>
    <!-- end page content -->
    
</form>