
<?php 
$this->load->language('components/com_cf_labels');
$this->load->model('contact_forms/Contact_form');
$contact_forms = $this->Contact_form->getContactForms();
?>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?php echo lang('label_contact_form');?>:</label></th>
    <td>        
        <select name="params[contact_form_id]" >
            <?php foreach($contact_forms as $contact_form){ ?>
            <option <?php echo $contact_form['id'] == set_value('params[contact_form_id]', isset($params['contact_form_id']) ? $params['contact_form_id'] : "") ? "selected" : "";?> 
                    value="<?php echo $contact_form['id'];?>" ><?php echo $contact_form['title'];?></option>
            <?php } ?>
        </select>
    </td>
</tr>
