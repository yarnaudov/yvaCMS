<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config['rotate_degrees']  = array(90 => 90, 180 => 180, 270 => 270, 360 => 360);

/*
 * albums
 */
$config['aspect_ratio']  = array("0", "1/1", "4/3", "16/9");

/*
 * albums
 */
$config['water_mark_positions'] = array("top_left"     => "com_gallery_label_top_left", 
										"top_right"    => "com_gallery_label_top_right",
	                                    "bottom_left"  => "com_gallery_label_bottom_left", 	                                     
	                                    "bottom_right" => "com_gallery_label_bottom_right");

/*
 * images
 */
$config['images_dir']         = "images";
$config['images_origin_dir']  = $config['images_dir']."/origin";
$config['images_croped_dir']  = $config['images_dir']."/croped";
$config['images_tmp_dir']     = $config['images_dir']."/tmp";
$config['images_resized_dir'] = $config['images_dir']."/resized";

$config['allowed_image_ext']  = array('gif', 'png', 'jpeg', 'pjpeg', 'jpg');
$config['max_image_size']     = 3145728;