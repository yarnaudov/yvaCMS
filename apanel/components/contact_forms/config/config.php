<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['mandatory_options'] = array('no'    => 'label_no',
                                     'yes'   => 'label_yes', 
                                     'email' => 'label_email',
                                     'date'  => 'label_date');

$config['custom_field_types'] = array('text'     => 'label_text_field',    
                                      'textarea' => 'label_textarea_field',
                                      'dropdown' => 'label_dropdown_field',
                                      'checkbox' => 'label_checkbox_field',
                                      'radio'    => 'label_radio_field',
                                      'date'     => 'label_date_field',
									  'file'     => 'label_file');