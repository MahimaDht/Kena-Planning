<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Audit_master extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('dashboard_model');
        $this->load->model('employee_model');
        $this->load->model('login_model');
        $this->load->model('settings_model');  
        $this->load->model('general_model');  
        $this->load->model('Access_model'); 
        $this->load->model('Email_model');
        $this->load->model('AuditChecklist_model');
    }
    
    public function index() 
    {
        if (!$this->session->userdata('user_type'))
        {
            redirect('login');
        }
        else
        {
            redirect('Audit_master/element');
        }
    }
    
    public function element() {
        $access=$this->Access_model->getPermissionByActionSub('writep');
        if($access!='success')
        {
            $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
            $this->load->view('errorPage', $data);
            return;
        }
        $data['elements'] = $this->AuditChecklist_model->get_elements();
        $data['title'] = "Element";
        $this->load->view('Audit/audit_checklist_form_element', $data);
    }

    // Save element
    public function save_element() {

        if($this->session->userdata('user_login_access') != False) {
            $access = $this->Access_model->getPermissionByActionSub('writep');
            if ($access !== 'success') {
                list($data['heading'], $data['description']) = explode('/', $access);
                $this->load->view('errorPage', $data);
                return;
            }
        $data = [
            'questionnaire_for' => $this->input->post('questionnaire_for'),
            'element_name' => $this->input->post('element_name'),
            'checklist_for' => $this->input->post('checklist_for'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_login_id')
        ];
        $this->AuditChecklist_model->insert_element($data);
        redirect('Audit_master');
    }

    else{
        redirect(base_url() , 'refresh');
    }
    }
    public function editElement($id) {
            $access=$this->Access_model->getPermissionByActionSub('readp');
            if($access!='success')
            {
                $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
                $this->load->view('errorPage', $data);
                return;
            }
        $data['elements'] = $this->AuditChecklist_model->get_elements();
        $data['editelements'] = $this->AuditChecklist_model->get_elements($id);
        $data['title'] = "Element";
        $this->load->view('Audit/audit_checklist_form_element', $data);
    }
    public function save_edit_element() {

        if($this->session->userdata('user_login_access') != False) {

            $access = $this->Access_model->getPermissionByActionSub('writep');
            if ($access !== 'success') {
                list($data['heading'], $data['description']) = explode('/', $access);
                $this->load->view('errorPage', $data);
                return;
            }
        $edit_id = $this->input->post("edit_id");
        $data = [
            'questionnaire_for' => $this->input->post('questionnaire_for'),
            'element_name' => $this->input->post('element_name'),
            'checklist_for' => $this->input->post('checklist_for'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_login_id')
        ];
        if ($this->AuditChecklist_model->update_element($edit_id, $data)) {
            $this->session->set_flashdata('success', 'Element updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update element.');
        }
        redirect('Audit_master/editElement/' . $edit_id);
        }else{
            redirect(base_url() , 'refresh');
        }
    }
    public function deleteElement($edit_id) {
    $access = $this->Access_model->getPermissionByActionSub('deletep');
    if ($access != 'success') {
        list($data['heading'], $data['description']) = explode('/', $access);
        $this->load->view('errorPage', $data);
        return;
    }
    $data = [
            'is_deleted' => 1,
            'deleted_on' => date('Y-m-d H:i:s'),
            'deleted_by' => $this->session->userdata('user_login_id')
        ];
    if ($this->AuditChecklist_model->update_element($edit_id, $data)) {
            $this->session->set_flashdata('success', 'Element Deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to Delete Element.');
        }
        redirect('Audit_master/element');
    }



////////// Requirement /////////////////////////
public function requirement() {
    $access = $this->Access_model->getPermissionByActionSub('writep');
    if ($access != 'success') {
        list($data['heading'], $data['description']) = explode('/', $access);
        $this->load->view('errorPage', $data);
        return;
    }
    $data['requirements'] = $this->AuditChecklist_model->get_requirements();
    $data['elements'] = $this->AuditChecklist_model->get_elements();
    $data['title'] = "Requirements/Expectations";
    $this->load->view('Audit/audit_checklist_form_requirement', $data);
}

// Save requirement
public function save_requirement() {
    if ($this->session->userdata('user_login_access') != False) {
        $access = $this->Access_model->getPermissionByActionSub('writep');
        if ($access !== 'success') {
            list($data['heading'], $data['description']) = explode('/', $access);
            $this->load->view('errorPage', $data);
            return;
        }

        $data = [
            'dht_audit_checklist_element_id' => $this->input->post('element_id'),
            'requirement_description' => $this->input->post('requirement_description'),
            'max_score' => $this->input->post('max_score'),
            'department_id' => $this->input->post('department_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_login_id')
        ];
        $this->AuditChecklist_model->insert_requirement($data);
        redirect('Audit_master/requirement');
    } else {
        redirect(base_url(), 'refresh');
    }
}

// Edit requirement
public function editRequirement($id) {
    $access = $this->Access_model->getPermissionByActionSub('readp');
    if ($access != 'success') {
        list($data['heading'], $data['description']) = explode('/', $access);
        $this->load->view('errorPage', $data);
        return;
    }
    $data['editRequirement'] = $this->AuditChecklist_model->get_requirement_by_id($id);
    $data['requirements'] = $this->AuditChecklist_model->get_requirements();
    $data['elements'] = $this->AuditChecklist_model->get_elements();
    $data['title'] = "Requirement";
    $this->load->view('Audit/audit_checklist_form_requirement', $data);
}

// Save edited requirement
public function save_edit_requirement() {
    if ($this->session->userdata('user_login_access') != False) {
        $access = $this->Access_model->getPermissionByActionSub('writep');
        if ($access !== 'success') {
            list($data['heading'], $data['description']) = explode('/', $access);
            $this->load->view('errorPage', $data);
            return;
        }

        $edit_id = $this->input->post("edit_id");
        $data = [
            'dht_audit_checklist_element_id' => $this->input->post('element_id'),
            'requirement_description' => $this->input->post('requirement_description'),
            'max_score' => $this->input->post('max_score'),
            'department_id' => $this->input->post('department_id'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_login_id')
        ];
        if ($this->AuditChecklist_model->update_requirement($edit_id, $data)) {
            $this->session->set_flashdata('success', 'Requirement updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update requirement.');
        }
        redirect('Audit_master/editRequirement/' . $edit_id);
    } else {
        redirect(base_url(), 'refresh');
    }
}

public function deleteRequirement($edit_id) {
    $access = $this->Access_model->getPermissionByActionSub('deletep');
    if ($access != 'success') {
        list($data['heading'], $data['description']) = explode('/', $access);
        $this->load->view('errorPage', $data);
        return;
    }
    $data = [
            'is_deleted' => 1,
            'deleted_on' => date('Y-m-d H:i:s'),
            'deleted_by' => $this->session->userdata('user_login_id')
        ];
    if ($this->AuditChecklist_model->update_requirement($edit_id, $data)) {
            $this->session->set_flashdata('success', 'Requirement Deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to Delete requirement.');
        }
        redirect('Audit_master/requirement');
    }

}