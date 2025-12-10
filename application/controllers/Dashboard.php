<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Calcutta");
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model');
        $this->load->model('settings_model');   
        $this->load->model('Access_model');   
    }
    
	public function index()
	{
		#Redirect to Admin dashboard after authentication
        if ($this->session->userdata('user_login_access') == 1)
            redirect('dashboard/Dashboard');
            $data=array();
            #$data['settingsvalue'] = $this->dashboard_model->GetSettingsValue();
			$this->load->view('login');
	}
    public function errorPage($heading=null, $description=null) 
    {
        if (!$this->session->userdata('user_type'))
        {
            redirect('login');
        }
        else
        {
            if($heading && $description){ $heading=base64_decode($heading); $description=base64_decode($description); }
            else { $heading=403; $description="Oops, The page you are looking for might have been removed, <br> had its name changed or is temporarily unavailable ."; }

            $data['heading']=$heading; $data['description']=$description;
            $data['title'] = 'Something went wrong';
            $this->load->view('defultErrorPage', $data);
        }
    }

    function Dashboard()
    {
        if($this->session->userdata('user_login_access') != False) 
        {
          
        // $data['urldata'] = $this->Access_model->get_menu_data();

        // $access=$this->Access_model->getPermissionByAction();

        // if($access!='success')

        // {
        //     $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
        //     $data['title'] = 'Something went wrong';
        //     $this->load->view('errorPage', $data);
        //     return;

        // }

            $data['title'] = 'Dashboard';
            $this->load->view('dashboard/index',$data);
        }
        else
        {
            redirect(base_url() , 'refresh');
        }
    }


}