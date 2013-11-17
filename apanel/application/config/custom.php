<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['no_login']           = array('',
                                      'login',
                                      'logout',
                                      'no_access',
                                      'media/browse',
                                      'media/image_settings',
                                      'modules/article_list',
                                      'home/ajax',
                                      'modules/types',
                                      'menus/types',
                                      'banners/types',
                                      'articles/history',
                                      'articles/images',
                                      'statistics/details');

$config['yes_no']             = array('yes'             => 'label_yes', 
                                      'no'              => 'label_no');

$config['mandatory_options']  = array('no'              => 'label_no',
                                      'yes'             => 'label_yes', 
                                      'email'           => 'label_email',
                                      'date'            => 'label_date');

$config['statuses']           = array('yes'             => 'label_active', 
                                      'no'              => 'label_inactive', 
                                      'trash'           => 'label_trashed');

$config['accesses']           = array('public'          => 'label_public', 
                                      'registred'       => 'label_registred');

$config['menu_targets']       = array('_parent'         => 'label_parent_window', 
                                      '_blank'          => 'label_new_window');

$config['menu_types']         = array('article'         => 'label_article',    
                                      'articles_list'   => 'label_articles_list',
				      'custom_articles_list' => 'label_custom_articles_list',
                                      'menu'            => 'label_menu',
                                      'external_url'    => 'label_external_url',
				      'sitemap'         => 'label_sitemap');

$config['custom_field_types'] = array('text'            => 'label_text_field',    
                                      'textarea'        => 'label_textarea_field',
                                      'dropdown'        => 'label_dropdown_field',
                                      'checkbox'        => 'label_checkbox_field',
                                      'radio'           => 'label_radio_field',
                                      'date'            => 'label_date_field',
				      'media'           => 'label_media_field',
				      'location'        => 'label_location_field');

$config['module_display']     = array('all'                 => 'label_all_pages', 
                                      'on_selected'         => 'label_on_selected',
                                      'all_except_selected' => 'label_all_except_selected');

$config['default_paging_limit'] = 20;
$config['max_visible_pages']    = 5;

$config['results_limits'] = array(5  => 5, 
                                  10 => 10, 
                                  15 => 15, 
                                  20 => 20, 
                                  25 => 25, 
                                  30 => 30, 
                                  'all' => 'label_all');

$config['banner_types'] = array('image' => 'label_image',    
                                'flash' => 'label_flash',
                                'html'  => 'label_html',
                                'link'  => 'label_link',
                                /*'popup' => 'label_popup'*/);

$config['media_dir'] = "media";

/* End of file custom.php */
/* Location: ./application/config/custom.php */
