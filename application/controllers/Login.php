<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Calcutta");
        $this->load->model('login_model');
        $this->load->model('dashboard_model');
        $this->load->model('settings_model');
  
    }
    
	public function index()
	{
		#Redirect to Admin dashboard after authentication
        if ($this->session->userdata('user_login_access') == 1)
        {
            redirect(base_url() . 'dashboard');
        }

        $data=array();
		$this->load->view('login');
	}


	public function login2()
	{
		#Redirect to Admin dashboard after authentication
        if ($this->session->userdata('user_login_access') == 1)
        {
            redirect(base_url() . 'dashboard');
        }

        $data=array();
		$this->load->view('login2');
	}
	public function Login_Auth()
	{
		$response = array();
	    //Recieving post input of email, password from request
	    $email = $this->input->post('username');
	    $lat = $this->input->post('latt');
	    $long = $this->input->post('longg');
	    $password = sha1($this->input->post('password'));
		$remember = $this->input->post('remember');

		#Login input validation\
		$this->load->library('form_validation');
	    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('username', 'Enter User Name', 'trim|xss_clean|required|min_length[2]');
		$this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required|min_length[4]');
		

		if($this->form_validation->run() == FALSE)
		{ 
			$validation_errors = validation_errors(); // Get all validation errors as a string
    		$this->session->set_flashdata('feedback', $validation_errors);
			redirect(base_url() . 'login', 'refresh');
		}
		else
		{
	        //Validating login
	        $login_status = $this->validate_login($email, $password,$lat,$long);
	        $response['login_status'] = $login_status;
	        if ($login_status == 'success')
	        {

				$this->session->set_flashdata('feedback','Success');
	        	if($remember)
	        	{
	        		setcookie('email',$email,time() + (86400 * 30));
	        		setcookie('password',$this->input->post('password'),time() + (86400 * 30));
	        	} 
	        	else 
	        	{
	        		if(isset($_COOKIE['email']))
	        		{
	        			setcookie('email',' ');
	        		}
	        		if(isset($_COOKIE['password']))
	        		{
	        			setcookie('password',' ');
	        		}
	        	}
				redirect(base_url() . 'login', 'refresh');
	        
	        }
			else
			{			
				$this->session->set_flashdata('feedback','Please enter valid login details !');
				redirect(base_url() . 'login', 'refresh');	
			}
		}
	}

	 function validate_login($email = '', $password = '',$lat='',$long='') {
        $credential = array('em_username' => $email, 'em_password' => $password,'status' => 'ACTIVE');
        $query = $this->login_model->getUserForLogin($credential);
        if ($query->num_rows() > 0) {


            $row = $query->row();

            $roles=$this->db->query("SELECT b.rolename FROM employee as a, uac_roles as b WHERE a.role_id=b.id AND a.em_id='$row->em_id' ");

            $role_array=array();
            foreach ($roles->result() as $value) 
            {
                array_push($role_array, $value->rolename);
            }  


            $this->session->set_userdata('user_login_access', '1');
            $this->session->set_userdata('user_login_id', $row->em_id);
            $this->session->set_userdata('user_id', $row->id);
            $this->session->set_userdata('name', $row->first_name);
            $this->session->set_userdata('title_name','Dashboard' );
            $this->session->set_userdata('namel', $row->last_name);
            $this->session->set_userdata('email', $row->em_email);
            $this->session->set_userdata('user_image', $row->em_image);

            $this->session->set_userdata('user_type', $row->em_role);
            $this->session->set_userdata('user_type_id', $row->role_id);
            $this->session->set_userdata('user_role', $role_array);

            $this->session->set_userdata('company_id', $row->comp_id);
            date_default_timezone_set("Asia/Kolkata");
            $data =  array(
				         	'emp_id' => $this->session->userdata('user_login_id'),
				         	'emp_role' => $this->session->userdata('user_type'),
				         	'action' => 'Successfully Login',
				         	'ip_address' => $_SERVER['REMOTE_ADDR'],
				         	'login_date' => date('Y-m-d H:i:s'),
				         	'latitude' => $lat,
				         	'longitude' => $long
         	);
            $email = $this->session->userdata('email');
            $name = $this->session->userdata('name');
            $namel = $this->session->userdata('namel');
            $emp_id = $this->session->userdata('user_login_id');
            $ip = $_SERVER['REMOTE_ADDR'];
         	$datetime = date('Y-m-d H:i:s');
           // $this->sendmail($name,$namel,$email,$emp_id,$ip,$datetime);
            
         	$this->db->insert('login_log',$data);
            return 'success';
        }
	}
    /*Logout method*/
    function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('feedback', 'logged_out');
        redirect(base_url(), 'refresh');
    }

		
	
}