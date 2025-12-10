<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_master extends CI_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Kolkata");
        // $this->load->model('Access_model');
        $this->load->model('Admin_model');
        $this->load->model('settings_model');
        $this->load->model('employee_model');  
        $this->load->model('Access_model');   
    }
    
    
	public function index() 
	{
        if (!$this->session->userdata('user_type'))
        {
			redirect('login');
        }
        else
        {
            redirect('Admin_master/role');
        }
    }

    //////////////////////////////////////// Role Master ////////////////////////////////////////////////

    public function role() 
    {   

        if ($this->session->userdata('user_type') && in_array("SUPER ADMIN", $this->session->userdata('user_role')))
        {

            $this->session->set_userdata('title_name','User Role' );

             // $this->session->userdata('user_role');
        
            $role_id = $this->session->userdata('user_type');
            
            $data['roledata'] = $this->Admin_model->GetRoledata();
            $data['title'] = 'Role';
            $this->load->view('Admin_master/role', $data);
           
        }
        else
        {
            
            redirect('login');
        }
    }

    public function save_role()
    {
        if ($this->session->userdata('user_type'))
        {
            $id = $this->input->post('id');

            $data = array(
                'rolename' => strtoupper($this->input->post('rolename')),
            );

            if (!empty($id))
            {
                $checker = $this->db->query("SELECT COUNT(*) as checker FROM uac_roles WHERE rolename='".$this->input->post('rolename')."' AND id!='".$id."' ")->row()->checker;

                if ($checker == 0) 
                {
                    $this->db->where('id', $id);
                    $update = $this->db->update('uac_roles', $data);
                    if ($update == true) 
                    {
                        $this->session->set_flashdata('success', 'Successfully Updated !');
                    } 
                    else 
                    {
                        $this->session->set_flashdata('error', 'Error Occured !');
                    }
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Role Already Exists !');
                }
            } 
            else 
            {
                $checker = $this->db->query("SELECT COUNT(*) as checker FROM uac_roles WHERE rolename='".$this->input->post('rolename')."' ")->row()->checker;

                if ($checker == 0) 
                {
                    $create = $this->db->insert('uac_roles', $data);
                    if ($create == true) 
                    {
                        $this->session->set_flashdata('success', 'Successfully Added !');
                    } 
                    else 
                    {
                        $this->session->set_flashdata('error', 'Error Occured !');
                    }
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Role Already Exists !');
                }
            }
            redirect('Admin_master/role');
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function edit_role($id=null)
    {
        if ($this->session->userdata('user_type')) 
        {
            $id=base64_decode($id);

            $role_id = $this->session->userdata('user_type');
            
            $data['roledata'] = $this->Admin_model->GetRoledata();
            $data['title'] = 'Edit Role';
            $data['editdata'] = $this->Admin_model->GetRoledata($id);

            $this->load->view('Admin_master/role', $data);
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function remove_role($id)
    {
        $id=base64_decode($id);

        $checker = $this->db->query("SELECT COUNT(*) as checker FROM uac_roles WHERE id ='$id' ")->row()->checker;
        if ($checker == 0)
        {
            $this->db->where('id', $id);
            $delete = $this->db->delete('uac_roles');

            if ($delete == true) 
            {
                $this->session->set_flashdata('success', 'Successfully Removed !');
            } 
            else 
            {
                $this->session->set_flashdata('error', 'Error in Removal !');
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'This role is used in user, so it can not be removed !');
        }        

        redirect('Admin_master/role');
    }

    //////////////////////////////////////// Menu Master ////////////////////////////////////////////////

    public function menus() 
    {
        if ($this->session->userdata('user_type') && in_array("SUPER ADMIN", $this->session->userdata('user_role')))
        {
            $role_id = $this->session->userdata('user_type');
            
            $data['menudata'] = $this->Admin_model->GetMenudata();
            $this->session->set_userdata('title_name','Menu' );
            $data['title'] = 'Menu';
            
            $this->load->view('Admin_master/menus', $data);
        }
        else
        {
            redirect('login');
        }
    }

    public function save_menus()
    {
        if ($this->session->userdata('user_type'))
        {
            $id = $this->input->post('id');

            $data = array(
                'menu_name' => $this->input->post('menu_name'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'parent_id' => $this->input->post('parent_id')==''?null:$this->input->post('parent_id'),
                'menu_sequence' => $this->input->post('menu_sequence'),
                'active' => $this->input->post('active'),
            );

            if (!empty($id))
            {
                $checker = $this->db->query("SELECT COUNT(*) as checker FROM uac_menus WHERE ( menu_name='".$this->input->post('menu_name')."' OR  url='".$this->input->post('url')."' ) AND url!='#' AND id!='".$id."' ")->row()->checker;

                if ($checker == 0) 
                {
                    $this->db->where('id', $id);
                    $update = $this->db->update('uac_menus', $data);
                    if ($update == true) 
                    {
                        $this->session->set_flashdata('success', 'Successfully Updated !');
                    } 
                    else 
                    {
                        $this->session->set_flashdata('error', 'Error Occured !');
                    }
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Menu Already Exists !');
                }
            } 
            else 
            {   
          
                $checker = $this->db->query("SELECT COUNT(*) as checker FROM uac_menus WHERE ( menu_name='".$this->input->post('menu_name')."' OR  url='".$this->input->post('url')."' )  AND url!='#' ")->row()->checker;

                if ($checker == 0) 
                {
                    $create = $this->db->insert('uac_menus', $data);
                    if ($create == true) 
                    {
                        $this->session->set_flashdata('success', 'Successfully Added !');
                    } 
                    else 
                    {
                        $this->session->set_flashdata('error', 'Error Occured !');
                    }
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Menu Already Exists !');
                }
            }
            redirect('Admin_master/menus');
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function edit_menus($id)
    {
        if ($this->session->userdata('user_type')) 
        {
            $id=base64_decode($id);

            $role_id = $this->session->userdata('user_type');
            
            $data['menudata'] = $this->Admin_model->GetMenudata();
            $data['editdata'] = $this->Admin_model->GetMenudata($id);
            $data['title'] = 'Menu';

            $this->load->view('Admin_master/menus', $data);
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function remove_menus($id)
    {
        if ($this->session->userdata('user_type')) 
        {
            $id=base64_decode($id);

            $checker = $this->db->query("SELECT COUNT(*) as checker FROM uac_menus WHERE parent_id='$id' and active=1 ")->row()->checker;

            if ($checker == 0)
            {
                // $this->db->where('id', $id);
                // $delete = $this->db->delete('uac_menus');
                $delete = $this->db->query(" Update uac_menus SET active=0 Where id='$id' ");

                if ($delete == true) 
                {
                    $this->session->set_flashdata('success', 'Successfully Removed !');
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Error in Removal !');
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'This menu is assigned as parent to a menu, so it can not be removed !');
            }        

            redirect('Admin_master/menus');
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }



    //////////////////////////////////////// Sub Menu Master ////////////////////////////////////////////////

    public function sub_menus() 
    {
        if ($this->session->userdata('user_type') && in_array("SUPER ADMIN", $this->session->userdata('user_role')))
        {
            $role_id = $this->session->userdata('user_type');
            
            $data['menudata'] = $this->Admin_model->GetMenudata();
            $data['submenudata'] = $this->Admin_model->GetSubMenudata();
            $data['title'] = 'Sub Menu';
            
            $this->load->view('Admin_master/sub_menus', $data);
        }
        else
        {
            redirect('login');
        }
    }

    public function save_sub_menus()
    {
        if ($this->session->userdata('user_type'))
        {
            $id = $this->input->post('id');

            $data = array(
                'sub_menu_name' => $this->input->post('sub_menu_name'),
                'sub_url' => $this->input->post('sub_url'),
                'menu_id' => $this->input->post('menu_id'),
                'active' => $this->input->post('active'),
            );

            if (!empty($id))
            {
                $checker = $this->db->query("SELECT COUNT(*) as checker FROM uac_sub_menu WHERE ( sub_menu_name='".$this->input->post('sub_menu_name')."' OR  sub_url='".$this->input->post('sub_url')."' ) AND id!='".$id."' ")->row()->checker;

                if ($checker == 0) 
                {
                    $this->db->where('id', $id);
                    $update = $this->db->update('uac_sub_menu', $data);
                    if ($update == true) 
                    {
                        $this->session->set_flashdata('success', 'Successfully Updated !');
                    } 
                    else 
                    {
                        $this->session->set_flashdata('error', 'Error Occured !');
                    }
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Menu Already Exists !');
                }
            } 
            else 
            {   
          
                $checker = $this->db->query("SELECT COUNT(*) as checker FROM uac_sub_menu WHERE ( sub_menu_name='".$this->input->post('sub_menu_name')."' OR  sub_url='".$this->input->post('sub_url')."' )  ")->row()->checker;

                if ($checker == 0) 
                {
                    $create = $this->db->insert('uac_sub_menu', $data);
                    if ($create == true) 
                    {
                        $this->session->set_flashdata('success', 'Successfully Added !');
                    } 
                    else 
                    {
                        $this->session->set_flashdata('error', 'Error Occured !');
                    }
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Menu Already Exists !');
                }
            }
            redirect('Admin_master/sub_menus');
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function edit_sub_menus($id)
    {
        if ($this->session->userdata('user_type')) 
        {
            $id=base64_decode($id);

            $role_id = $this->session->userdata('user_type');
            
            $data['menudata'] = $this->Admin_model->GetMenudata();
            $data['submenudata'] = $this->Admin_model->GetSubMenudata();
            $data['editdata'] = $this->Admin_model->GetSubMenudata($id);
            $data['title'] = 'Sub Menu';

            $this->load->view('Admin_master/sub_menus', $data);
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function remove_sub_menus($id)
    {
        if ($this->session->userdata('user_type')) 
        {
            $id=base64_decode($id);

                $this->db->where('id', $id);
                $delete = $this->db->delete('uac_sub_menu');

                if ($delete == true) 
                {
                    $this->session->set_flashdata('success', 'Successfully Removed !');
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Error in Removal !');
                }
                 

            redirect('Admin_master/sub_menus');
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }

    
    //////////////////////////////////////// User Access Master ////////////////////////////////////////////////

    // public function userAccess() 
    // {
    //     if ($this->session->userdata('user_type') && in_array("SUPER ADMIN", $this->session->userdata('user_role')))
    //     {
    //         $role_id = $this->session->userdata('user_type');
            
    //         $data['userdata'] = $this->Admin_model->GetUserdata();
            
    //         $this->load->view('Admin_master/userAccess', $data);
    //     }
    //     else
    //     {
    //         redirect('login');
    //     }
    // }

    public function roleAccess() 
    {
        if ($this->session->userdata('user_type') && in_array("SUPER ADMIN", $this->session->userdata('user_role')))
        {
            $role_id = $this->session->userdata('user_type');
            $data['roledata'] = $this->Admin_model->GetRoledata();
            $data['title'] = 'Role';
            
            $this->load->view('Admin_master/roleAccess', $data);
        }
        else
        {
            redirect('login');
        }
    }

    public function fetch_userAccess()
    {
        if ($this->session->userdata('user_type'))
        {
            $login_role_id = $this->session->userdata('user_type');
            $data['title'] = 'Fetch user access';
            // $data['sidebar_menu'] = $this->Access_model->get_sidebar_menu($login_role_id);


            $role_id = base64_decode($this->input->get('role_id'));

            $data['parent_id']='';
            if($this->input->get('parent_id'))
                $data['parent_id'] = base64_decode($this->input->get('parent_id'));


            $data['parentMenuList'] = $this->Admin_model->GetParentMenudata();
            $data['roleList'] = $this->Admin_model->GetRoledata();

            $data['role_id']=$role_id;
            $data['roledata'] = $this->Admin_model->GetRoledata($role_id);
            $data['menudata'] = $this->Admin_model->GetMenuByParentdata('',$data['parent_id']);
            $this->load->view('Admin_master/fetch_userAccess', $data);
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function set_userAccess()
    {
        if ($this->session->userdata('user_type'))
        {
            // $role_id=$this->input->post('role_id');

            // $this->db->where('role_id', $role_id);
            // $this->db->delete('uac_access_controls');

            // $cnt=0;

            // $count_menu = count($this->input->post('menu_id'));
            // for($x = 0; $x < $count_menu; $x++) 
            // {
            //     $readp=$this->input->post('readp_'.$this->input->post('menu_id')[$x]);
            //     $writep=$this->input->post('writep_'.$this->input->post('menu_id')[$x]);
            //     $updatep=$this->input->post('updatep_'.$this->input->post('menu_id')[$x]);
            //     $deletep=$this->input->post('deletep_'.$this->input->post('menu_id')[$x]);
            //     $start_time=$this->input->post('start_time_'.$this->input->post('menu_id')[$x]);
            //     $end_time=$this->input->post('end_time_'.$this->input->post('menu_id')[$x]);

            //     if(!empty($readp) || !empty($writep) || !empty($updatep) || !empty($deletep))
            //     {
            //         $data=array(
            //             'menu_id' => $this->input->post('menu_id')[$x],
            //             'role_id' => $role_id,
            //             'readp' => $readp =='' ? '0':'1',
            //             'writep' => $writep =='' ? '0':'1',
            //             'updatep' => $updatep =='' ? '0':'1',
            //             'deletep' => $deletep =='' ? '0':'1',
            //             'start_time' => $start_time =='' ? null:$start_time,
            //             'end_time' => $end_time =='' ? null:$end_time,
            //         );

            //         $create=$this->db->insert('uac_access_controls', $data);
            //         if($create==true) { $cnt++; }
            //     }
            // }

            $role_id = $this->input->post('role_id');
            $parent_id = $this->input->post('parent_id');
                $cnt = 0;

                $count_menu = count($this->input->post('menu_id'));
                for ($x = 0; $x < $count_menu; $x++) {
                    $menu_id = $this->input->post('menu_id')[$x];
                    $readp = $this->input->post('readp_' . $menu_id);
                    $writep = $this->input->post('writep_' . $menu_id);
                    $updatep = $this->input->post('updatep_' . $menu_id);
                    $deletep = $this->input->post('deletep_' . $menu_id);
                    $start_time = $this->input->post('start_time_' . $menu_id);
                    $end_time = $this->input->post('end_time_' . $menu_id);

                    $data = array(
                        'menu_id' => $menu_id,
                        'role_id' => $role_id,
                        'readp' => $readp == '' ? '0' : '1',
                        'writep' => $writep == '' ? '0' : '1',
                        'updatep' => $updatep == '' ? '0' : '1',
                        'deletep' => $deletep == '' ? '0' : '1',
                        'start_time' => $start_time == '' ? null : $start_time,
                        'end_time' => $end_time == '' ? null : $end_time,
                    );

                    // Check if permission record exists
                    $this->db->where('menu_id', $menu_id);
                    $this->db->where('role_id', $role_id);
                    $query = $this->db->get('uac_access_controls');

                    if(!empty($readp) || !empty($writep) || !empty($updatep) || !empty($deletep))
                    {
                        if ($query->num_rows() > 0) {
                            // Update existing record
                            $this->db->where('menu_id', $menu_id);
                            $this->db->where('role_id', $role_id);
                            $update = $this->db->update('uac_access_controls', $data);
                            if ($update) {
                                $cnt++;
                            }
                        } else {
                            // Insert new record
                            $create = $this->db->insert('uac_access_controls', $data);
                            if ($create) {
                                $cnt++;
                            }
                        }
                    }
                    else{

                        $this->db->where('menu_id', $menu_id);
                        $this->db->where('role_id', $role_id);
                        $this->db->delete('uac_access_controls');

                    }
                }

            $this->session->set_flashdata('success', 'Permission Successfully Assigned !');
            redirect('Admin_master/fetch_userAccess?role_id='.base64_encode($role_id).'&parent_id='.base64_encode($parent_id));
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }


    public function update_customUserPermission()
    {   
          if ($this->session->userdata('user_login_access') != False) {
        $role_id = $this->input->post('role_id');

        $permissions = (array) $this->input->post('permissions');
        $data = array(
            'custom_permissions' => implode(',', $permissions),
        );
        $this->db->where('id',$role_id);
        $update = $this->db->update('uac_roles',$data);

        if($update == true) 
        {
            $this->session->set_flashdata('success','Permissions Successfully Updated !');
        }
        else 
        {
            $this->session->set_flashdata('error','Error in Updation !');
        }
        redirect('Admin_master/fetch_userAccess?role_id='.base64_encode($role_id));

          } else {
            redirect(base_url(), 'refresh');
        }
    }


}