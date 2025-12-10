<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('dashboard_model');
        $this->load->model('employee_model');
        $this->load->model('general_model');
        $this->load->model('login_model');
        $this->load->model('settings_model');  
        $this->load->model('Access_model');   
    
        $this->load->model('Admin_model');
    
        
        
    }

    public function index() 
    {
        if (!$this->session->userdata('user_type'))
        {
            redirect('login');
        }
        else
        {
            redirect('Employee/Employees');
        }
    }
    
    public function Employees()
    {
        if ($this->session->userdata('user_login_access') != False)
        {   
             
            $data['urldata'] = $this->Access_model->get_menu_data();
            $access=$this->Access_model->getPermissionByAction();
            if($access!='success')
            {
                $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
                $data['title'] = 'Something went wrong';
                $this->load->view('errorPage', $data);
                return;
            }
            $data['title'] = $data['urldata']->menu_name;
            $data['employee'] = $this->employee_model->getallusers();
            $this->load->view('employees/employees', $data);
        }
        else
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function add_employee()
    {
        if ($this->session->userdata('user_login_access') != False)
        {   
            $data['title'] = 'Add User';
            $data['roledata'] = $this->Admin_model->GetRoledata();
           // $data['company_list'] = $this->general_model->getCompanyData();
            //$data['plant_list'] = $this->general_model->getUnitData();
            
            $this->load->view('employees/add_employee', $data);
        }
        else
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function checkusernameexist($em_username= null)
    {
        if(!$em_username)
        {
            $em_username = $this->input->post('em_username');
        }
        $cnt = $this->db->query("SELECT count(*) as cnt FROM employee WHERE em_username = '" . $em_username . "' ")->row()->cnt;
        if ($cnt > 0) 
        {
            echo "YES";
        } 
        else
        {
            echo "NO";
        }
    }


    public function Save()
    {
        if ($this->session->userdata('user_login_access') != False) 
        {
            $update_id = $this->input->post('update_id');
            $emcode = $this->input->post('emcode');
            $id = $this->input->post('emid');
            $fname = $this->input->post('first_name');
            $lname = $this->input->post('last_name');
            $emrand = substr($fname, 0, 3) . rand(1000, 2000);
            $contact = $this->input->post('contact');
            $status = $this->input->post('status');
            $user_type1 = $this->input->post('user_type');
            
            $email = $this->input->post('email');
            $pass = $this->input->post('password');
            $password = sha1($pass);
            $confirm = $this->input->post('confirm');
            $role = $this->input->post('role');
            $data = array();
            $em_image = '';
            $company_id = $this->input->post('company_id');

            // $plant_ids = array_map(function ($id) {
            //     return "'" . $id . "'";
            // }, $this->input->post('plant_id'));
            
            // $plant_id = implode(',', $plant_ids);
            $plant_id='';

            $user_type=implode(",", $user_type1);
           

            $data = array(
                            'plant_id' => $plant_id,
                            'reporting_auth_id' => $this->input->post('manager_id'),
                            'role_id' => $this->input->post('role_id'),
                         
                            'is_report_auth' => $this->input->post('is_report_auth')==''?'N':$this->input->post('is_report_auth') ,
                            'first_name' => $fname,
                            'last_name' => $lname,
                            'em_phone' => $contact,
                            'comp_id' => $company_id,
                            'em_email' => $email,
                            'em_role' => 'Employee',
                            'em_image' => $em_image,
                            'user_type' => $user_type,
                        );
            if ($update_id) 
            {
                 $insert = array(
                                    'status' => $this->input->post('status'),
                              );
                $data = array_merge($insert,$data);
                
                $success = $this->employee_model->Update($data,$update_id);
                $this->session->set_flashdata('success', 'User Updated Successfully');
                if($this->input->post('status')=='INACTIVE'){
                redirect('employee/');
                }else{
                redirect('employee/view?I='.base64_encode($update_id));
                }

            }
            else
            {
                $cnt = $this->db->query("SELECT count(*) as cnt FROM employee WHERE em_username = '" . $this->input->post('em_username') . "' ")->row()->cnt;

                 if ($cnt > 0) 
                {
                    $this->session->set_flashdata('error', 'Username Already Exist');
                    redirect('employee/Add_employee', 'refresh');
                    die;
                }
                 $insert = array(
                                    'em_id' => $emrand,
                                    'status' => 'ACTIVE',
                                    'em_code' => $this->input->post('em_username'),
                                    'em_username' => $this->input->post('em_username'),
                                    'em_password' => $password,
                              );
                $data = array_merge($insert,$data);
            // print_r($data);die;
                $success = $this->employee_model->Add($data);
                $this->session->set_flashdata('success', 'User added Successfully');
                redirect('employee/Employees', 'refresh');
            }

        } 
        else
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function view($id_param=null)
    {
        if ($this->session->userdata('user_login_access') != False) 
        {
            $id = base64_decode($this->input->get('I'));

            if ($id_param)
            {
                $id = base64_decode($id_param);
            }
            $data['title'] = 'Edit User';
            
            $data['roledata'] = $this->Admin_model->GetRoledata();
            //$data['company_list'] = $this->general_model->getCompanyData();
          //  $data['plant_list'] = $this->general_model->getUnitData();
             $data['basic'] = $this->employee_model->getallusers($id);
            $data['page_type'] = 'edit_employee';
            $this->session->set_userdata('title_name','Edit Employee' );
            $this->load->view('employees/edit_employee', $data);
        } 
        else
        {
            redirect(base_url(), 'refresh');
        }
    }



    public function reset_password_page($id_param=null)
    {
        if ($this->session->userdata('user_login_access') != False) 
        {
            $id = base64_decode($this->input->get('I'));

            if ($id_param)
            {
                $id = base64_decode($id_param);
            }

            $data['basic'] = $this->employee_model->GetBasic($id);
            // echo $this->db->last_query();
            // die;
            // $data['company_list'] = $this->common_model->get_companies_Data();
            // $data['plant_list'] = $this->common_model->get_m_business_plant();
             // $data['plant_list'] = $this->general_model->getstore_units();
             
            $data['roledata'] = $this->Admin_model->GetRoledata();
            $data['page_type'] = 'edit_employee';
            $this->session->set_userdata('title_name','Reset Password' );
            $this->load->view('employees/reset_password_page', $data);
        } 
        else
        {
            redirect(base_url(), 'refresh');
        }
    }
    public function Reset_Password_Hr()
    {
        if ($this->session->userdata('user_login_access') != False) {
            $id = $this->input->post('emid');
            $onep = $this->input->post('new1');
            $twop = $this->input->post('new2');
            if ($onep == $twop) {
                $data = array(
                    'em_password' => sha1($onep)
                );
                $success = $this->employee_model->Reset_Password($id, $data);
                $this->session->set_flashdata('success', 'Successfully Updated');
                redirect("employee/view/" . base64_encode($id));
            } else {
                $this->session->set_flashdata('error', 'Please enter valid password');
                redirect("employee/view/" .base64_encode($id)); 
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function Reset_Password()
    {
        if ($this->session->userdata('user_login_access') != False) 
        {
            $id = $this->input->post('emid');
            $onep = $this->input->post('new1');
            $twop = $this->input->post('new2');
            if ($onep == $twop) 
                {
                    $data = array();
                    $data = array(
                                    'em_password' => sha1($onep)
                                );
                    $success = $this->employee_model->Update($data,$id );
                    $this->session->set_flashdata('success', 'Successfully Updated');
                    redirect("employee/view?I=" . base64_encode($id));
                    //  echo "Successfully Updated";
                } 
                else
                {
                    $this->session->set_flashdata('feedback', 'Please enter valid password');
                    die;
                    redirect("employee/view?I=" .base64_encode($id)); 
                }
        }
        else
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function twoFactorAuth($id_param=null)
    {
        if ($this->session->userdata('user_login_access') != False) 
        {
            $id = base64_decode($this->input->get('I'));

            if ($id_param)
            {
                $id = base64_decode($id_param);
            }

            $data['basic'] = $this->employee_model->GetBasic($id);
            // echo $this->db->last_query();
            // die;
            // $data['company_list'] = $this->common_model->get_companies_Data();
            // $data['plant_list'] = $this->common_model->get_m_business_plant();
             // $data['plant_list'] = $this->general_model->getstore_units();
             
            $data['roledata'] = $this->Admin_model->GetRoledata();
            $this->session->set_userdata('title_name','Two Factor Authentication' );
            $data['title'] = 'Two Factor-Authentication';
            $this->load->view('employees/twoFactorAuth', $data);
        } 
        else
        {
            redirect(base_url(), 'refresh');
        }
    }
    public function saveAuthentication()
    {
        if ($this->session->userdata('user_login_access') != False) 
        {
            $id = $this->input->post('emid');
            $is_two_fact_auth = $this->input->post('is_two_fact_auth');
            $auth_password = $this->input->post('auth_password');

            // Default values if input is not provided
            if ($is_two_fact_auth=='') {
                $is_two_fact_auth = '0';  // Default to 0 if not provided
                $auth_password = null;
            }else{
                $auth_password = sha1($auth_password);
            }
            

            $data = array(
                'is_two_fact_auth' => $is_two_fact_auth,
                'auth_password' => $auth_password,
            );
            // print_r($data);die;
            $success = $this->employee_model->Update($data, $id);
            
            $this->session->set_flashdata('success', 'Successfully Updated');
            redirect("employee/twoFactorAuth?I=" . base64_encode($id));
        }
        else
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function notification($id_param=null)
    {
        if ($this->session->userdata('user_login_access') != False) 
        {
            $id = base64_decode($this->input->get('I'));

            if ($id_param)
            {
                $id = base64_decode($id_param);
            }

            $data['basic'] = $this->employee_model->GetBasic($id);
            // echo $this->db->last_query();
            // die;
            // $data['company_list'] = $this->common_model->get_companies_Data();
            // $data['plant_list'] = $this->common_model->get_m_business_plant();
             // $data['plant_list'] = $this->general_model->getstore_units();
             
            $data['roledata'] = $this->Admin_model->GetRoledata();
            $data['page_type'] = 'notification';      
            $this->session->set_userdata('title_name','Notification' );    
            $this->load->view('employees/notification', $data);
        } 
        else
        {
            redirect(base_url(), 'refresh');
        }
    }
    public function saveNotification()
    {
        if ($this->session->userdata('user_login_access') != False) 
        {
            $id = $this->input->post('emid');
            $is_two_fact_auth = $this->input->post('is_two_fact_auth');
            $auth_password = $this->input->post('auth_password');

            // Default values if input is not provided
            if ($is_two_fact_auth=='') {
                $is_two_fact_auth = '0';  // Default to 0 if not provided
                $auth_password = null;
            }else{
                $auth_password = sha1($auth_password);
            }
            

            $data = array(
                'is_two_fact_auth' => $is_two_fact_auth,
                'auth_password' => $auth_password,
            );
            // print_r($data);die;
            $success = $this->employee_model->Update($data, $id);
            
            $this->session->set_flashdata('success', 'Successfully Updated');
            redirect("employee/twoFactorAuth?I=" . base64_encode($id));
        }
        else
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function importUserExcel()
    {
    if (!empty($_FILES['excel_file']['name'])) {
        $file = fopen($_FILES['excel_file']['tmp_name'], 'r');

     
        $header = fgetcsv($file);
                fgetcsv($file);
        try {
            $inserted = 0;
            $skipped = [];


            while (($row = fgetcsv($file)) !== FALSE) {

       $em_username = isset($row[0]) ? trim($row[0]) : '';
        $first_name  = isset($row[1]) ? trim($row[1]) : '';
        $last_name   = isset($row[2]) ? trim($row[2]) : '';
        $em_email    = isset($row[3]) ? trim($row[3]) : '';
        $em_role     = isset($row[4]) ? trim($row[4]) : '';
        $role_id     = isset($row[5]) ? trim($row[5]) : '';
        $em_phone    = isset($row[6]) ? trim($row[6]) : '';
        $comp_id     = isset($row[7]) ? trim($row[7]) : '';
        $user_type   = isset($row[8]) ? trim($row[8]) : '';

           // echo $user_type; die;
           
             
                if (!empty($em_username)) {
                   
                  $cnt = $this->db->query("SELECT count(*) as cnt FROM employee WHERE em_username = '" . $em_username . "' ")->row()->cnt;
                    

                    if ($cnt == 0) {
                     
                         $emrand = substr($first_name, 0, 3) . rand(1000, 2000);
                      

                    
                        $data = [

                                     'plant_id' =>'',
                                  
                                    'role_id' => $role_id,
                                 
                                    'is_report_auth' => 'N' ,
                                    'first_name' => $first_name,
                                    'last_name' => $last_name,
                                    'em_phone' => $em_phone,
                                    'comp_id' => $comp_id,
                                    'em_email' => $em_email,
                                    'em_role' => 'Employee',
                                   
                                    'user_type' => $user_type,
                                    'em_id' => $emrand,
                                    'status' => 'ACTIVE',
                                    'em_code' => $em_username,
                                    'em_username' => $em_username,
                                    'em_password' => sha1('12345678'),
                        ];

                      //  print_r($data); die;
                        $this->db->insert('employee', $data);

                        $inserted++;
                    } else {
                        // keep track of skipped names
                        $skipped[] = $em_username;
                    }
                }
            }

            fclose($file);

            // ✅ Show appropriate flash messages
            if ($inserted > 0) {
                $this->session->set_flashdata('success', "$inserted Employee imported successfully!");
            }
            if (!empty($skipped)) {
                $this->session->set_flashdata('error', "These employee already exist: " . implode(', ', $skipped));
            }

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error loading file: ' . $e->getMessage());
        }
    } else {
        $this->session->set_flashdata('error', 'Please upload a valid Excel/CSV file.');
    }

    redirect('employee/employees'); 
}


}
