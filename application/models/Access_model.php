<?php

class Access_model extends CI_Model
{   
     function __construct()
     {
         parent::__construct();
         date_default_timezone_set("Asia/Kolkata");
     }

    // public function get_user_permissions($role_id)
    // {
    //     $this->db->select('permission_id');
    //     $this->db->from('user_permissions');
    //     $this->db->where('role_id', $role_id);
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }

    public function getPermissionByAction($action=null)
    {
        $role_id = $this->session->userdata('user_type_id');
        $url = $this->uri->segment(1).'/'.$this->uri->segment(2);
        $url_id = $this->Access_model->get_menu_using_urls($url);
        return $access=$this->Access_model->check_permission_new($url_id, $action);
    } 

    public function get_menu_using_urls($url)
    {
        $this->db->select('id');
        $this->db->from('uac_menus');
        $this->db->where('url', $url);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['id'] ?? false;
    }    

    public function get_menu_data($url=null)
    {   
        if($url==null)
        {
                $url = $this->uri->segment(1).'/'.$this->uri->segment(2);
        }
        $url_id = $this->Access_model->get_menu_using_urls($url);
        $role_id = $this->session->userdata('user_type_id');

        return $this->db->query("SELECT a.*, b.parent_id, b.menu_name FROM uac_access_controls as a, uac_menus as b WHERE a.menu_id=b.id AND a.menu_id='$url_id' AND a.role_id='$role_id'")->row();
    }  



    // public function check_user_permission($role_id, $menu_id, $action)
    // {   

    //     $currentTime = date('H:i');
       
    //     $sql = " SELECT id FROM uac_access_controls WHERE role_id = '$role_id' AND menu_id = '$menu_id' 
    //             AND (start_time IS NULL OR start_time <= '".$currentTime."')
    //             AND (end_time IS NULL OR end_time >= '".$currentTime."')  ";
    //     if($action!='' || $action!=null)
    //         $sql .= " AND $action = 1 ";
    //     // echo $sql;
    //     // die;
    //     $result = $this->db->query($sql)->row_array();
    //     return !empty($result);
    // }
public function check_user_permission($role_id, $menu_id, $action = null)
{   
    $currentTime = date('H:i');

    // Build query using CodeIgniter's Query Builder
    $this->db->select('id')
             ->from('uac_access_controls')
             ->where('role_id', $role_id)
             ->where('menu_id', $menu_id)
             ->group_start()
                ->where('start_time IS NULL', null, false)
                ->or_where('start_time <=', $currentTime)
             ->group_end()
             ->group_start()
                ->where('end_time IS NULL', null, false)
                ->or_where('end_time >=', $currentTime)
             ->group_end();

    // If action is provided, check for its permission (column should be 1)
    if (!empty($action)) {
        $this->db->where($action, 1);
    }

    $query = $this->db->get();
    
    return $query->num_rows() > 0; // Returns true if permission exists, false otherwise
}

    // public function check_user_permission($role_id, $menu_id)
    // {
    //     $this->db->select('id');
    //     $this->db->from('uac_access_controls');
    //     $this->db->where('role_id', $role_id);
    //     $this->db->where('menu_id', $menu_id);
    //     $query = $this->db->get();
    //     $result = $query->row_array();
    //     return !empty($result);
    // }
    // $sql = "SELECT m.id as menu_id,m.*, ac.can_view, ac.can_edit, ac.can_delete
    //     FROM uac_menus m
    //     INNER JOIN uac_access_controls ac ON m.id = ac.menu_id
    //     WHERE ac.role_id = '".$this->session->userdata('user_type_id')."'
    //     AND (ac.start_time IS NULL OR ac.start_time <= NOW())
    //     AND (ac.end_time IS NULL OR ac.end_time >= NOW())
    //     ORDER BY m.menu_sequence ASC ";
// $result = $this->db->query($sql)->result_array();

    public function get_sidebar_menu() 
    {     
        $role_id = $this->session->userdata('user_type_id');
        $currentTime = date('H:i');
        $result = $this->db->query("SELECT a.menu_name, a.url, a.icon, a.parent_id, b.* FROM uac_menus as a, uac_access_controls as b WHERE a.id=b.menu_id AND a.active=1 AND b.role_id='$role_id' AND (b.start_time IS NULL OR b.start_time <= '".$currentTime."' )
            AND (b.end_time IS NULL OR b.end_time >= '".$currentTime."' )
            ORDER BY a.menu_sequence ASC ")->result_array();
// echo $this->db->last_query();
        // die;
        function buildMenuTree($elements, $parentId = null)
        {
            $branch = [];

            foreach ($elements as $element) 
            {
                if ($element['parent_id'] == $parentId) 
                {
                    $children = buildMenuTree($elements, $element['menu_id']);

                    if (!empty($children)) 
                    {
                        $element['children'] = $children;
                    }

                    $branch[$element['menu_id']] = $element;
                }
            }

            return $branch;
        }

        return buildMenuTree($result);
    }

    public function check_permission()
    {
        $url = $this->uri->segment(1).'/'.$this->uri->segment(2);
        $url_id = $this->get_menu_using_urls($url);

        if ($url_id)
        {
            $role_id = $this->session->userdata('user_type_id');
            $has_permission = $this->check_user_permission($role_id, $url_id, 'readp');

            if (!$has_permission)
            {
                return "/Oops, You don't have permission to access this page. So please contact your administrator to have this permission.";
            }
            else
            {
                return 'success';
            }
        }
        else
        {
            return "404 - PAGE NOT FOUND/Oops, The page you are looking for might have been removed, <br> had its name changed or is temporarily unavailable.";
        }
    }

    public function check_permission_new($url_id=null, $action=null)
    {
        if ($url_id)
        {
            $role_id = $this->session->userdata('user_type_id');
            $has_permission = $this->check_user_permission($role_id, $url_id, $action);

            if (!$has_permission)
            {
                return "/Oops, You don't have permission to access this page. So please contact your administrator to have this permission.";
            }
            else
            {
                return 'success';
            }
        }
        else
        {
            return "404 - PAGE NOT FOUND/Oops, The page you are looking for might have been removed, <br> had its name changed or is temporarily unavailable.";
        }
    }

    public function getPermissionByActionSub($action=null)
    {
        $url = $this->uri->segment(1).'/'.$this->uri->segment(2);
        $url_id = $this->get_main_menu_using_suburls($url);

        return $access=$this->Access_model->check_permission_new($url_id, $action);
    } 

    public function get_main_menu_using_suburls($url)
    {
        $this->db->select('menu_id');
        $this->db->from('uac_sub_menu');
        $this->db->where('sub_url', $url);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['menu_id'] ?? false;
    }


}