<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class General extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('dashboard_model');
        $this->load->model('employee_model');
        $this->load->model('login_model');
        $this->load->model('settings_model');  
        $this->load->model('general_model');  
        $this->load->model('Access_model');   
        $this->load->model('Asn_model');   
        $this->load->model('Email_model');   
        $this->load->model('master_model');   
    }



    public function setting_and_privacy()
    {   
        if (!$this->session->userdata('user_login_access'))
        {
            redirect(base_url(), 'refresh');
            return;
        }


            $data['title'] = 'Setting and Privacy';
            $data['document_master_array'] = $this->master_model->get_document_master();
            $this->load->view('general/setting_and_privacy',$data);

    }
    public function profile()
    {   
        if (!$this->session->userdata('user_login_access'))
        {
            redirect(base_url(), 'refresh');
            return;
        }


            $data['title'] = 'Profile';
            $this->load->view('general/profile',$data);

    }

    public function analytics()
    {   
        if (!$this->session->userdata('user_login_access'))
        {
            redirect(base_url(), 'refresh');
            return;
        }


            $data['title'] = 'Analytics';
            $this->load->view('general/analytics',$data);

    }




public function update_profile() {
    if (!$this->session->userdata('user_login_access')) {
        redirect(base_url(), 'refresh');
        return;
    }

    $emid = $this->input->post('emid');

    $this->form_validation->set_rules('username', 'Username', 'required|is_unique_except[employee.em_username.em_id]|trim');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('general/setting_and_privacy?type=account');
        return;
    }

    $username = $this->input->post('username');

    // Check if a file was uploaded
    if (!empty($_FILES['profile_picture']['name'])) {
        // File upload configuration
        $config['upload_path'] = './uploads/user/profile/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = uniqid();

        $this->upload->initialize($config);

        if ($this->upload->do_upload('profile_picture')) {
            $upload_data = $this->upload->data();
            $profile_picture = $upload_data['file_name'];
            $image_with_path = 'uploads/user/profile/' . $profile_picture;

            $data = array('em_image' => $image_with_path, 'em_username' => $username);

            $success = $this->employee_model->updateEmployeeData($emid, $data);

            if ($success) {
                $this->session->set_flashdata('success', 'Profile updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Profile update failed.');
            }
        } else {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $error);

            // Update username only if upload fails
            $data = array('em_username' => $username);

            log_audit_action($emid, 'Profile Updated', 'User updated their username or profile image');



            $this->employee_model->updateEmployeeData($emid, $data);
        }
    } else {
        // No file was uploaded, update username only
        $data = array('em_username' => $username);
            log_audit_action($emid, 'Profile Updated', 'User updated their username');
        $this->employee_model->updateEmployeeData($emid, $data);
        $this->session->set_flashdata('success', 'Profile updated successfully.');
    }

    redirect('general/setting_and_privacy?type=account');
}

     public function Reset_Password() {
        if ($this->session->userdata('user_login_access') != FALSE) {

            $this->form_validation->set_rules('old', 'Current Password', 'required');
            $this->form_validation->set_rules('new1', 'New Password', 'required|min_length[6]');
            $this->form_validation->set_rules('new2', 'Verify Password', 'required|matches[new1]');

            if ($this->form_validation->run() == FALSE) {
                // Validation failed, redirect back to the form with errors
                $this->session->set_flashdata('error', validation_errors());
                redirect('general/setting_and_privacy?type=password');
            } else {
                $id = $this->input->post('emid');
                $oldp = sha1($this->input->post('old')); // Use sha256 or bcrypt
                $onep = $this->input->post('new1');
                $twop = $this->input->post('new2');

                $pass = $this->employee_model->GetEmployeeId($id);

                if ($pass && $pass->em_password == $oldp) {
                    if ($onep == $twop) {
                        $data = array('em_password' => sha1($onep)); // Use sha256 or bcrypt

                        $success = $this->employee_model->updateEmployeeData($id, $data);

                        if ($success) {
            log_audit_action($id, 'Profile Updated', 'User updated their password');
                            $this->session->set_flashdata('success', 'Successfully Updated');
                        } else {
                            $this->session->set_flashdata('error', 'Update Failed. Please try again.');
                        }

                    } else {
                        $this->session->set_flashdata('error', 'New password and Verify password do not match');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Please enter a valid old password');
                }
                redirect('general/setting_and_privacy?type=password');
            }

        } else {
            redirect(base_url(), 'refresh');
        }
    }
public function getSupplierDetailsWithContactPersonAndEmailTemplate()
{
    // Check for user authentication
    if (!$this->session->userdata('user_login_access')) {
        echo json_encode(['error' => 'Unauthorized access', 'csrf_token' => $this->security->get_csrf_hash()]);
        return;
    }

    // Get supplier_id from POST
    $supplier_id = $this->input->post('supplier_id', true);
    $template_id = $this->input->post('template_id', true);
    $doc_id = $this->input->post('doc_id', true);

    $doc_id = base64_decode(base64_decode($doc_id));

    if (empty($supplier_id)) {
        echo json_encode(['error' => 'Invalid supplier ID', 'csrf_token' => $this->security->get_csrf_hash()]);
        return;
    }

    // Fetch supplier and contact details
    $sql = "SELECT a.code, a.name, b.Name AS contact_personname, b.Designation, 
                   b.Email, b.Mobile, b.Landline, b.Dept  
            FROM OVND AS a 
            LEFT JOIN VND1 AS b ON a.code = b.code 
            WHERE a.code = ? AND b.Email != ''
            ORDER BY b.Line";

    $query = $this->db->query($sql, [$supplier_id]);
    $result = $query->result_array();

    if (empty($result)) {
        echo json_encode(['error' => 'No contacts found', 'csrf_token' => $this->security->get_csrf_hash()]);
        return;
    }

    // Prepare contacts list
    $contacts = array_map(function ($row) {
        return [
            'contact_personname' => $row['contact_personname'],
            'Designation' => $row['Designation'],
            'Email' => $row['Email'],
            'Mobile' => $row['Mobile'],
            'Landline' => $row['Landline'],
            'Dept' => $row['Dept']
        ];
    }, $result);




    $po_head_array = $this->db->query("select * from OPOR as a where  a.DocYear+a.Doc+CAST(a.DocNo as varchar) = '$doc_id'")->row();

    $document_date = '';
    if(!empty($po_head_array)){

    $document_date = date('d/m/Y',strtotime($po_head_array->DocDt));
    }


    // Fetch email template
    $email_input_attributes = json_encode(['supplier_name' => $result[0]['name'] , 'document_number' => $doc_id, 'document_date' => $document_date  ]);
    $email_template = $this->general_model->email_template_editor_function($template_id, $email_input_attributes);

    // Get updated CSRF token
    $csrf_token = $this->security->get_csrf_hash();

    // Return JSON response with CSRF token
    echo json_encode([
        'header' => ['code' => $result[0]['code'], 'name' => $result[0]['name']],
        'contacts' => $contacts,
        'email_template_body' => $email_template['emailContent'],
        'email_template_subject' => $email_template['toSubject'],
        'csrf_token' => $csrf_token // ✅ Send updated CSRF token
    ]);
}



public function getSupplierDetailsWithContactPersonAndEmailTemplateJwo()
{
    // Check for user authentication
    if (!$this->session->userdata('user_login_access')) {
        echo json_encode(['error' => 'Unauthorized access', 'csrf_token' => $this->security->get_csrf_hash()]);
        return;
    }

    // Get supplier_id from POST
    $supplier_id = $this->input->post('supplier_id', true);
    $template_id = $this->input->post('template_id', true);
    $doc_id = $this->input->post('doc_id', true);

    $doc_id = base64_decode(base64_decode($doc_id));

    if (empty($supplier_id)) {
        echo json_encode(['error' => 'Invalid supplier ID', 'csrf_token' => $this->security->get_csrf_hash()]);
        return;
    }

    // Fetch supplier and contact details
    $sql = "SELECT a.code, a.name, b.Name AS contact_personname, b.Designation, 
                   b.Email, b.Mobile, b.Landline, b.Dept  
            FROM OVND AS a 
            LEFT JOIN VND1 AS b ON a.code = b.code 
            WHERE a.code = ? AND b.Email != ''
            ORDER BY b.Line";

    $query = $this->db->query($sql, [$supplier_id]);
    $result = $query->result_array();

    if (empty($result)) {
        echo json_encode(['error' => 'No contacts found', 'csrf_token' => $this->security->get_csrf_hash()]);
        return;
    }

    // Prepare contacts list
    $contacts = array_map(function ($row) {
        return [
            'contact_personname' => $row['contact_personname'],
            'Designation' => $row['Designation'],
            'Email' => $row['Email'],
            'Mobile' => $row['Mobile'],
            'Landline' => $row['Landline'],
            'Dept' => $row['Dept']
        ];
    }, $result);


        $po_head_array = $this->db->query("SELECT * from OJWPO as a where  a.DocYear+a.Doc+CAST(a.DocNo as varchar) = '$doc_id'")->row();

        $document_date = '';
        if(!empty($po_head_array)){

        $document_date = date('d/m/Y',strtotime($po_head_array->DocDt));
    }


    // Fetch email template
    $email_input_attributes = json_encode(['supplier_name' => $result[0]['name'] , 'document_number' => $doc_id, 'document_date' => $document_date  ]);
    $email_template = $this->general_model->email_template_editor_function($template_id, $email_input_attributes);

    // Get updated CSRF token
    $csrf_token = $this->security->get_csrf_hash();

    // Return JSON response with CSRF token
    echo json_encode([
        'header' => ['code' => $result[0]['code'], 'name' => $result[0]['name']],
        'contacts' => $contacts,
        'email_template_body' => $email_template['emailContent'],
        'email_template_subject' => $email_template['toSubject'],
        'csrf_token' => $csrf_token // ✅ Send updated CSRF token
    ]);
}
public function get_item() {
    header('Content-Type: application/json');

    if (!$this->session->userdata('user_login_access')) {
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    $user_type = $this->session->userdata('user_type');
    $user_login_id = $this->session->userdata('user_login_id');
    $employee_data = $this->employee_model->getallusers($user_login_id);

    $search = $this->input->get('search');
    $unit_code = $this->input->get('unit_code');

    // If no unit code provided, return empty response
    if (empty($unit_code)) {
        echo json_encode(['results' => []]);
        return;
    }

    $sql = "
        SELECT DISTINCT 
            i.ItemCode AS id,
            CONCAT(i.ItemCode, ' - ', it.name) AS text,
            it.StockUoM,
            it.RefNo,
            it.DrgNo
        FROM OPOR a 
        INNER JOIN OVND v ON a.Vendor = v.code 
        LEFT JOIN VND1 v1 ON v.code = v1.code AND v.name = a.ContPers 
        INNER JOIN POR1 i ON a.docyear = i.docyear AND a.Doc = i.Doc AND a.DocNo = i.Docno AND a.changelog = i.ChangeLog 
        INNER JOIN POR4 id ON a.docyear = id.docyear AND a.doc = id.Doc AND a.docno = id.docno 
            AND a.changelog = id.ChangeLog AND i.ItemCode = id.ItemCode 
            AND id.DelDate IN (
                SELECT TOP 1 MAX(dd.DelDate) 
                FROM POR4 dd 
                WHERE dd.docyear = id.docyear AND dd.doc = id.Doc 
                    AND dd.docno = id.docno AND dd.changelog = id.ChangeLog 
                    AND id.ItemCode = dd.ItemCode
            )
        INNER JOIN OITM it ON i.ItemCode = it.code 
        WHERE a.ChangeLog IN (
            SELECT MAX(b.changelog) 
            FROM OPOR b 
            WHERE a.docyear = b.docyear AND a.doc = b.Doc AND a.docno = b.docno
        ) 
        AND a.DocStatus = 'O' 
        AND i.POPQty > ISNULL(i.RecPQty, 0)
        AND a.Unit = ?
    ";

    if (!empty($search)) {
        $sql .= " AND (i.ItemCode LIKE ? OR it.name LIKE ?)";
        $query = $this->db->query($sql . " ORDER BY i.ItemCode", [$unit_code, "%$search%", "%$search%"]);
    } else {
        $query = $this->db->query($sql . " ORDER BY i.ItemCode", [$unit_code]);
    }

    $result = $query->result();

    echo json_encode(['results' => $result]);
}


}