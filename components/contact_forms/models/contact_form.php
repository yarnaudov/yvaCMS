<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_form extends CI_Model {
    
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
    
    function _send($contact_form_id)
    {
        
        $contact_form = self::getDetails($contact_form_id);
        
        $to  = explode(',', $contact_form['to']);
        if(!empty($contact_form['cc'])){
            $cc  = explode(',', $contact_form['cc']);
        }
        if(!empty($contact_form['bcc'])){
            $bcc = explode(',', $contact_form['bcc']);
        }
        
        $subject = lang('msg_cf_mail_subject');
        $subject = str_replace('{site_name}', $this->Settings->getSiteName(), $subject);
        $subject = str_replace('{contact_form}', $contact_form['title_'.get_lang()], $subject);
        
        $message_body = lang('msg_cf_mail_body_top');
        $message_body = str_replace('{contact_form}', $contact_form['title_'.get_lang()], $message_body);
        foreach($contact_form['fields'] as $number => $field){
            
            if($number == 'captcha'){
                continue;
            }
            
            $message_body .= '<strong>'.$field['label_'.get_lang()].'</strong>: '.$_POST['field'.$number].'<br/>';
            
        }
        
        
        require_once APPPATH.'libraries/swift/swift_required.php';
           
        $mailer = $this->Settings->getMailer();
           
        if($mailer == 'smtp'){
                
            $transport = Swift_SmtpTransport::newInstance($this->Settings->getSSMTHost(), $this->Settings->getSSMTPort());

            $ssmt_security = $this->Settings->getSSMTSecurity();
            if(!empty($ssmt_security)){
                $transport->setEncryption($ssmt_security);
            }

            $ssmt_user = $this->Settings->getSSMTUser();
            $ssmt_pass = $this->Settings->getSSMTPass();

            if(!empty($ssmt_user)){
                $transport->setUsername($ssmt_user);
                $transport->ssetPassword($ssmt_pass);
            }
            
        }
        elseif($mailer == 'sendmail'){
            $transport = Swift_SendmailTransport::newInstance($this->Settings->getSendmail() . ' -bs');
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
        ->setFrom(array($this->Settings->getFromEmail() => $this->Settings->getFromName()));

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
        

        // And optionally an alternative body
        //->addPart('<q>Here is the message itself</q>', 'text/html');
        
        $result = $mailer->send($message);
        
        //echo "--->".$result."<---------<br/>";
        
        if($result == 1){
            $this->session->set_userdata('contact_form_msg', lang('msg_cf_send'));
        }
        else{
            $this->session->set_userdata('contact_form_msg', lang('msg_cf_error'));
        }
        
        redirect($this->uri->segment(1));
        exit;
        
    }
    
}