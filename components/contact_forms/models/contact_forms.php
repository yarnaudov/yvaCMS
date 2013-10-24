<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_forms extends CI_Model {
    
    public function run($data = array())
    {
	
		$this->load->language('components/com_cf');
        
        $this->jquery_ext->add_plugin('validation');
		$this->jquery_ext->add_library('check_captcha.js');
        $this->jquery_ext->add_library('../components/contact_forms/js/contacts.js');
        
        # load validation library language file
        $file = 'validation/localization/messages_'.get_lang().'.js';
        if(file_exists('js/'.$file)){
            $this->jquery_ext->add_library($file);
        }
		
        # Load jquery_ui and search for .datepicker
        $this->jquery_ext->add_plugin('jquery_ui');
        $script = "$('.datepicker').datepicker({
                        showOn: 'button',
                        dateFormat: 'yy-mm-dd',
                        buttonImage: '".base_url('components/contact_forms/img/iconCalendar.png')."',
                        buttonImageOnly: true,              
                        buttonText: '".lang('label_cf_select_date')."'
                    });";
        $this->jquery_ext->add_script($script);
		
		if($this->input->get('contact_form_id')){
			$contact_form_id = $this->input->get('contact_form_id');
		}
		else{
			$contact_form_id = $data['contact_form_id'];
		}
			
		$contact_form = $this->getDetails($contact_form_id);
		
		# check end send email if data ok
		if($this->input->post('send')){
            $this->send($contact_form);
        }

		foreach($contact_form['fields'] as $number => $field){
			
			if($number == 'captcha') continue;
			
			switch($field['mandatory']){
				case "yes": 
					$contact_form['fields'][$number]['class'] = "required";
				break;
				case "email":
					$contact_form['fields'][$number]['class'] = "required email";
				break;
				case "date":
					$contact_form['fields'][$number]['class'] = "required date";
				break;
			}
			
			if($field['type'] == 'file'){
				$field_mimes = self::_get_file_mimes($field);
				$contact_form['fields'][$number]['mimes'] = implode(',', $field_mimes);				
			}
			
		}
		
		$templates = isset($this->Content->templates['contact_forms']) ? $this->Content->templates['contact_forms'] : array();

		$view = 'contact_form';
		if(isset($templates['contact_form'])){
			$view = $templates['contact_form'];
		}
		
		return $this->load->view($view, compact('contact_form'), true);
	
    }
    
    public function getDetails($id, $field = null)
    {
        
        $query = "SELECT 
                      *
                    FROM
                      com_contacts_forms ccf
                      LEFT JOIN com_contacts_forms_data ccfd ON (ccf.id = ccfd.contact_form_id AND ccfd.language_id = '".$this->language_id."')
                    WHERE
                      ccf.id = '".$id."' ";
        
        $contact_form = $this->db->query($query);  	
        $contact_form = $contact_form->result_array();

        if(empty($contact_form)){
            return;
        }

        $contact_form[0]['fields'] = json_decode($contact_form[0]['fields'], true);
        
        if($field == null){            
            return $contact_form[0];
        }
        else{  	
            return $contact_form[0][$field];
        }

    }
    
	private function _get_file_mimes($field)
	{
		
		$field_mimes = array();
				  
		if(!isset($this->mimes)){
			$this->load->library('upload');
			$this->upload->mimes_types('jpg');
			$this->mimes = $this->upload->mimes;
		}

		$exts = explode('|', $field['allowed_ext']);
		foreach($exts as $ext){					
			if(isset($this->mimes[$ext])){						
				if(is_array($this->mimes[$ext])){
					$field_mimes = array_merge($field_mimes, $this->mimes[$ext]);
				}
				else{
					$field_mimes[] = $this->mimes[$ext];
				}
			}
		}
		
		return $field_mimes;
		
	}
	
    public function send($contact_form)
    {
	
		$contact_form_id = $contact_form['id'];
        $message_data = array();
		$attachments = array();
        
        $to  = explode(',', $contact_form['to']);
        if(!empty($contact_form['cc'])){
            $cc  = explode(',', $contact_form['cc']);
        }
        if(!empty($contact_form['bcc'])){
            $bcc = explode(',', $contact_form['bcc']);
        }
        
		# check if there are to/cc comming from form
		if(isset($_POST['to'])){
			$bcc = isset($bcc) ? $bcc . ',' . $to : $to;
			$to = $this->input->post('to', true);
		}
		if(isset($_POST['cc'])){
			$bcc = isset($bcc) ? $bcc . ',' . $cc : $cc;
			$cc = $this->input->post('cc', true);
		}
		if(isset($_POST['bcc'])){
			$bcc = isset($bcc) ? $bcc . ',' . $this->input->post('bcc', true) : $this->input->post('bcc', true);
		}
	
        $subject = lang('msg_cf_mail_subject');
        $subject = str_replace('{site_name}', $this->Setting->getSiteName(), $subject);
        $subject = str_replace('{contact_form}', $contact_form['title'], $subject);
        
        $message_body = lang('msg_cf_mail_body_top');
        $message_body = str_replace('{contact_form}', $contact_form['title'], $message_body);
        foreach($contact_form['fields'] as $number => $field){
       
            if($number == 'captcha'){
                continue;
            }
			
			if($field['type'] == 'file'){
				
				$field_mimes = self::_get_file_mimes($field);
				
				if($field['max_size'] != '' && $_FILES['field'.$number]['size'] > $field['max_size']){
					continue;
				}
				
				if(count($field_mimes) > 0 && !in_array($_FILES['field'.$number]['type'], $field_mimes)){
					continue;
				}

				$attachments[] = array($_FILES['field'.$number]['tmp_name'], $_FILES['field'.$number]['name']);
				
				continue;
				
			}
            
            switch($field['type']){
                
              case 'checkbox':              
                  
                  $posts = isset($_POST['field'.$number]) ? $this->input->post('field'.$number, true) : array();
                  
                  foreach($posts as $post){
                      $values[] = $field['labels'][$post];
                  }
                  $value = implode(", ", $values);
                  
              break;
          
              case 'dropdown':
              case 'radio':
                 $value = isset($_POST['field'.$number]) ? $field['labels'][$this->input->post('field'.$number, true)] : '';
              break;
            
              default:
                  $value = isset($_POST['field'.$number]) ? $this->input->post('field'.$number, true) : '';
              break;
          
            }
            
            $message_body .= '<strong>'.$field['label'].'</strong>: '.$value.'<br/>';
			$message_data[$field['label']] = $value;
            
        }

        require_once APPPATH.'libraries/swift/swift_required.php';
           
        $mailer = $this->Setting->getMailer();
           
        if($mailer == 'smtp'){
                
            $transport = Swift_SmtpTransport::newInstance($this->Setting->getSSMTHost(), $this->Setting->getSSMTPort());

            $ssmt_security = $this->Setting->getSSMTSecurity();
            if(!empty($ssmt_security)){
                $transport->setEncryption($ssmt_security);
            }

            $ssmt_user = $this->Setting->getSSMTUser();
            $ssmt_pass = $this->Setting->getSSMTPass();

            if(!empty($ssmt_user)){
                $transport->setUsername($ssmt_user);
                $transport->ssetPassword($ssmt_pass);
            }
            
        }
        elseif($mailer == 'sendmail'){
            $transport = Swift_SendmailTransport::newInstance($this->Setting->getSendmail() . ' -bs');
        }
        else{    
            $transport = Swift_MailTransport::newInstance();
        }
                
        $mailer = Swift_Mailer::newInstance($transport);
        
        // Create the message
        $message = Swift_Message::newInstance()

        // Give the message a subject
        ->setSubject($subject)

        // Set the From address with an associative array
        ->setFrom(array($this->Setting->getFromEmail() => $this->Setting->getFromName()));

        // Set the To addresses with an associative array
        $message->setTo($to);
                
        // Set the Cc addresses with an associative array
        if(isset($cc)){
			$message->setCc($cc);
        }
                
        // Set the Bcc addresses with an associative array
        if(isset($bcc)){
			$message->setBcc($bcc);
        }

        // Give it a body
        $message->setBody($message_body, 'text/html');
        
		// attach file if available
		foreach($attachments as $attachment){
			$message->attach(
				Swift_Attachment::fromPath($attachment[0])->setFilename($attachment[1])
			);
		}
		
        // And optionally an alternative body
        //->addPart('<q>Here is the message itself</q>', 'text/html');
		
        $result = $mailer->send($message);
        
        //echo "--->".$result."<---------<br/>";
        
        if($result == 1){
	    $msg = !empty($contact_form['msg_success']) ? $contact_form['msg_success'] : lang('msg_cf_send');
            $this->session->set_flashdata('contact_form_msg'.$contact_form['id'], $msg);
	    self::_save_in_db($contact_form['id'], $message_data);
        }
        else{
	    $msg = !empty($contact_form['msg_error']) ? $contact_form['msg_error'] : lang('msg_cf_error');
            $this->session->set_flashdata('contact_form_msg'.$contact_form['id'], $msg);
        }
        
		if(!empty($_SERVER['HTTP_REFERER'])){
			redirect($_SERVER['HTTP_REFERER']);
		}
		else{
			redirect(current_url());
		}
        exit;
        
    }
    
    private function _save_in_db($contact_form_id, $message_data)
    {
	
	$this->load->library('user_agent');
	
	$data['contact_form_id'] = $contact_form_id;
	$data['created_on'] = date('Y-m-d H:i:s');
	
	# get user agent
	if ($this->agent->is_browser()){
	    $data['user_agent'] = $this->agent->browser().' '.$this->agent->version();
	}
	elseif ($this->agent->is_robot()){
	    $data['user_agent'] = $this->agent->robot();
	}
	elseif ($this->agent->is_mobile()){
	    $data['user_agent'] = $this->agent->mobile();
	}
	else{
	    $data['user_agent'] = 'Unidentified User Agent';
	}
	
	if($this->agent->is_referral()){
	    $data['page_url'] = $this->agent->referrer();
	}
	
	$data['ip'] = $this->input->ip_address();
	
	$data['message'] = json_encode($message_data);

	$this->db->insert('com_contacts_forms_messages', $data);
	
    }
    
}