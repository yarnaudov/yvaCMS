
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
	
	<div id="chart1" style="height:300px;"></div>

	<!--<?php '<pre>'.print_r($line1).'</pre>'; ?>-->
	
	<script class="code" type="text/javascript">
	    
	    var line1= JSON.parse('<?=json_encode($line1);?>');
	    console.log(line1);
	    var plot1 = $.jqplot ('chart1', [line1], {
		axes:{
		    xaxis:{
			renderer: $.jqplot.DateAxisRenderer,
			tickOptions:{
                            formatString:'%b&nbsp;%#d',
                            markSize: 1
                        },
			min: '<?=$filters['start_date'];?>',
			max: '<?=$filters['end_date'];?>',
			tickInterval: '1 day'
		    },
		    yaxis:{
                        tickOptions:{
                            markSize: 0
                        },
                        min: 0,
                        max: <?=($max_views+1);?>
		    }
		},
		series:[{
                    lineWidth: 1
                }],
		highlighter: {
		    show: true,
		    sizeAdjust: 7.5
		},
		cursor: {
		    show: false
		},
                grid: {
                    borderWidth: 0,
                    shadow: false
                }
	    });
	    
	    $('.start_end_dates').datepicker({
		showOn: 'focus',
		dateFormat: 'yy-mm-dd',
		buttonImage: '<?=base_url('img/iconCalendar.png');?>',
		buttonImageOnly: true
	    });
		    
	</script>
        
        <style>
            .search{
                float: right !important;
            }
            .search label{
                float: left;
                display: block;
                margin: 3px 3px 0 10px;
            }
            .filter{
                float: left !important;
            }
        </style>
        
        <br/><br/>
        <table class="list" cellpadding="0" cellspacing="2" >
            
            <tr>
                <th style="width:3%;"  rowspan="2" >#</th>
                <th style="width:12%;" rowspan="2" ><?=lang('label_date');?></th>
                <th style="width:3%;"  rowspan="2" >Прочетена</th>
                <th style="width:82%;" colspan="4" >Детайли</th>
            </tr>
            
            <tr>
                <th style="width:3%;"  >IP</th>
                <th style="width:3%;"  >User Agent</th>
                <th style="width:3%;"  >Page URL</th>
                <th style="width:82%;" >User Reffer</th>
            </tr>
            
            <?php foreach($statistics as $numb => $statistic){ ?>
            <tr>
                <td rowspan="<?=$statistic['views']+1;?>" ><?=($numb+1);?></td>
                <td rowspan="<?=$statistic['views']+1;?>" ><?=$statistic['created_on'];?></td>
                <td rowspan="<?=$statistic['views']+1;?>" ><?=$statistic['views'];?></td>
            </tr>
            
            <?php for($i = 1; $i <= $statistic['views']; $i++){ ?>
            <tr>
                <td></td>
                <td><?=$statistic['details'];?></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
            
            <?php } ?>
            
        </table>

    </div>
    <!-- end page content -->
    
</form>