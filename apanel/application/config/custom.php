<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['no_login']           = array('',
                                      'login',
                                      'logout',
                                      'no_access',
                                      'media/browse',
                                      'modules/article_list',
                                      'home/ajax',
                                      'modules/types',
                                      'menus/types',
                                      'banners/types',
                                      'articles/history',
                                      'articles/images');

$config['yes_no']             = array('yes'             => 'label_yes', 
                                      'no'              => 'label_no');

$config['statuses']           = array('yes'             => 'label_active', 
                                      'no'              => 'label_inactive', 
                                      'trash'           => 'label_trashed');

$config['accesses']           = array('public'          => 'label_public', 
                                      'registred'       => 'label_registred');

$config['menu_targets']       = array('_parent'         => 'label_parent_window', 
                                      '_blank'          => 'label_new_window');

$config['menu_types']         = array('article'         => 'label_article',    
                                      'articles_list'   => 'label_articles_list',
                                      'menu'            => 'label_menu',
                                      'external_url'    => 'label_external_url');

$config['custom_field_types'] = array('text'            => 'label_text_field',    
                                      'textarea'        => 'label_textarea_field',
                                      'dropdown'        => 'label_dropdown_field',
                                      'checkbox'        => 'label_checkbox_field',
                                      'date'            => 'label_date_field');

$config['module_display']     = array('all'                 => 'label_all_pages', 
                                      'on_selected'         => 'label_on_selected',
                                      'all_except_selected' => 'label_all_except_selected');

$config['image_width']  = array(300 => 300, 
                                400 => 400, 
                                500 => 500, 
                                600 => 600, 
                                700 => 700, 
                                800 => 800, 
                                900 => 900, 
                                1000 => 1000);

$config['image_height']  = array(300 => 300, 
                                400 => 400, 
                                500 => 500, 
                                600 => 600, 
                                700 => 700, 
                                800 => 800, 
                                900 => 900, 
                                1000 => 1000);

$config['thumb_width']  = array(100 => 100, 
                                110 => 110, 
                                120 => 120, 
                                130 => 130, 
                                140 => 140, 
                                150 => 150,
                                160 => 160);

$config['thumb_height'] = array(70 => 70, 
                                80 => 80, 
                                90 => 90, 
                                100 => 100, 
                                110 => 110, 
                                120 => 120,
                                130 => 130, 
                                140 => 140, 
                                150 => 150,
                                160 => 160);

$config['default_image_width']  = 700;
$config['default_image_height'] = 600;
$config['default_thumb_width']  = 130;
$config['default_thumb_height'] = 100;

$config['images_dir'] = "images";
$config['thumbs_dir'] = $config['images_dir']."/thumbs";

$config['allowed_image_ext'] = array('gif', 'png', 'jpeg', 'pjpeg', 'jpg');
$config['max_image_size']    = 3145728;

$config['default_paging_limit'] = 20;
$config['max_visible_pages']    = 5;

$config['results_limits'] = array(5  => 5, 
                                  10 => 10, 
                                  15 => 15, 
                                  20 => 20, 
                                  25 => 25, 
                                  30 => 30, 
                                  'all' => 'label_all');

$config['banner_size']  = array('250x250' => '250x250',
                                '300x250' => '300x250', 
                                '120x600' => '120x600', 
                                '160x600' => '160x600', 
                                '728x90'  => '728x90');

$config['banner_types'] = array('image' => 'label_image',    
                                'flash' => 'label_flash',
                                'html'  => 'label_html',
                                'link'  => 'label_link',
                                /*'popup' => 'label_popup'*/);

$config['media_dir'] = "media";


/* Group access */
$config['group_accesses'] = array('articles'                    => 'label_articles_add',
                                  'categories/articles'         => 'label_articles_categories',
                                  'custom_fields/articles'      => 'label_articles_custom_fields',
    
                                  'menus'                       => 'label_menus_add',
                                  'categories/menus'            => 'label_menus_categories',
                                  'custom_fields/menus'         => 'label_menus_custom_fields',
    
                                  'images'                      => 'label_images_add',
                                  'categories/images'           => 'label_images_categories',
                                  'custom_fields/images'        => 'label_images_custom_fields',
    
                                  'banners'                     => 'label_banners_add',
                                  'categories/banners'          => 'label_banners_categories',
                                  'custom_fields/banners'       => 'label_banners_custom_fields',
    
                                  'languages'                   => 'label_languages_add',
                                  'custom_fields/languages'     => 'label_languages_custom_fields',
    
                                  'users'                       => 'label_users_add',
                                  'groups/users'                => 'label_groups_add',
                                  'custom_fields/users'         => 'label_users_custom_fields',
                                                                        
                                  'modules'                     => 'label_modules_add',
                                  'categories/modules'          => 'label_modules_categories',
                                  'custom_fields/modules'       => 'label_modules_custom_fields',
    
                                  'settings'                    => 'label_settings_add');

/* End of file custom.php */
/* Location: ./application/config/custom.php */
