
<!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo base_url('js/jqplot/excanvas.js');?>"></script><![endif]-->

<form name="list" action="<?php echo current_url(true);?>" method="post" >
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?php echo base_url('img/iconArticles_25.png');?>" >
            <span><?php echo lang('label_articles');?></span>
	    <span>&nbsp;»&nbsp;</span>
            <span><?php echo lang('label_statistics');?>Statistics</span>
        </div>
	
	<div class="actions" >
            <a href="<?php echo site_url('articles');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>            
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
		<label><?php echo lang('label_from');?>:</label>
		<input type="text" class="start_date datepicker" name="filters[start_date]" value="<?php echo $filters['start_date'];?>" >
		<label><?php echo lang('label_to');?>:</label>
		<input type="text" class="end_date datepicker" name="filters[end_date]" value="<?php echo $filters['end_date'];?>" >
                <button class="styled" type="submit" name="search" ><?php echo lang('label_show');?></button>
            </div>
		
            <div class="filter" >
			
                <select name="filters[banner]" >
                    <option value="none" > - <?php echo lang('label_select');?> <?php echo lang('label_banner');?> - </option>
                    <?php echo create_options_array($banners, isset($filters['banner']) ? $filters['banner'] : "");?>
                </select>
		
		<input type="checkbox" name="filters[type][]" value="1" <?php echo (!isset($filters['type']) || in_array(1, $filters['type'])) ? 'checked' : '';?> >
		<label><?php echo lang('label_impressions');?></label>
				
		<input type="checkbox" name="filters[type][]" value="2" <?php echo (!isset($filters['type']) || in_array(2, $filters['type'])) ? 'checked' : '';?> >
		<label><?php echo lang('label_clicks');?></label>

            </div>
		
	</div>
	
	<br/>
	<div id="chart1" style="height:300px;margin-right: -7px;"></div>
	
	<script class="code" type="text/javascript">
	    
	    var line1 = JSON.parse('<?php echo json_encode($line1);?>');
	    var line2 = JSON.parse('<?php echo json_encode($line2);?>');
	    //console.log(line1);
	    var plot1 = $.jqplot ('chart1', [line1, line2], {
		seriesColors: [ "#0D82DB", "#EAA228"],
		axes:{
		    xaxis:{
			renderer: $.jqplot.DateAxisRenderer,
			tickOptions:{
                            formatString:'%d/%m',
                            markSize: 1,
			    fontSize: '9pt'
                        },
			min: '<?php echo $filters['start_date'];?>',
			max: '<?php echo $filters['end_date'];?>',
			tickInterval: '1 day'
		    },
		    yaxis:{
                        tickOptions:{
                            markSize: 0,
			    fontSize: '9pt'
                        },
                        min: 0,
                        max: <?php echo ($max_views+1);?>
		    }
		},
		series:[
		{
                    lineWidth: 1
                },
		{
		    lineWidth: 1
		}],
		highlighter: {
		    show: true,
		    sizeAdjust: 10.0,
		    lineWidthAdjust: 5.5
		},
		cursor: {
		    show: false
		},
                grid: {
                    borderWidth: 0,
                    shadow: false
                },
		legend: {
		    show: true,
		    location: 'ne',
		    marginTop: 10,
		    marginRight: 10,
		    labels: ['<?php echo lang('label_impressions');?>', '<?php echo lang('label_clicks');?>'],
		    fontSize: '12pt'
		}
	    });
	    
	    $(window).on('resize load', function() {
		plot1.replot();
	    });
	    
	    $('input[type=checkbox]').on('click load', function(event){
		plot1.series[$(this).val()-1].show = $(this).is(':checked');
		plot1.replot();		
	    });
	    $('input[type=checkbox]').trigger('load');

	    $( ".start_date" ).datepicker({
		showOn: 'focus',
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
		  $( ".end_date" ).datepicker( "option", "minDate", selectedDate );
		}
	    });
	    $( ".end_date" ).datepicker({
		showOn: 'focus',
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		maxDate: 0,
		onClose: function( selectedDate ) {
		  $( ".start_date" ).datepicker( "option", "maxDate", selectedDate );
		}
	    });
	    
	    $( ".start_date" ).datepicker( "option", "maxDate", "<?php echo $filters['end_date'];?>" );
	    $( ".end_date" ).datepicker( "option", "minDate", "<?php echo $filters['start_date'];?>" );
		    
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
	    .filter input{
		float: left;
		margin: 3px 0 0 10px;
	    }
	    .filter label,
	    .filter select{
		float: left;
	    }
	    .filter label{
		margin: 2px 0 0 2px;
	    }
        </style>
        
        <br/><br/>
        <table class="list" cellpadding="0" cellspacing="2" >
            
            <tr>
                <th style="width:1%;"  >#</th>
                <th style="width:10%;" ><?php echo lang('label_date');?></th>
                <th style="width:7%;"  ><?php echo lang('label_impressions');?></th>
		<th style="width:7%;"  ><?php echo lang('label_clicks');?></th>
            </tr>
            
            <?php $numb = 0;
		  foreach($statistics as $statistic){
		      $numb++; ?>
            <tr>
                <td><?php echo $numb;?></td>
                <td><?php echo $statistic['date'];?></td>
                <td><a class="details" href="<?php echo site_url('statistics/details/banner/'.$filters['banner'].'/'.$statistic['date'].'/1');?>" ><?php echo $statistic['impressions'];?></a></td>
		<td><a class="details" href="<?php echo site_url('statistics/details/banner/'.$filters['banner'].'/'.$statistic['date'].'/2');?>" ><?php echo $statistic['clicks'];?></a></td>
            </tr>
                
            <?php } ?>
		
            <tr>
                <th colspan="2" ><?php echo lang('label_total');?></th>
                <th><?php echo $total_impressions;?></th>
		 <th><?php echo $total_clicks;?></th>
            </tr>
                
	    <?php if(count($statistics) == 0){ ?>
	    <tr>
                <td colspan="8" >No data to display</td>
            </tr>
	    <?php } ?>
            
        </table>

    </div>
    <!-- end page content -->
    
</form>

<!-- start jquery UI -->
<div id="dialog-statistic-details" title="<?php echo lang('label_details');?>" >
    <p></p>
</div>