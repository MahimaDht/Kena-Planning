<?php

class Admin_model extends CI_Model
{
    function __consturct()
    {
        parent::__construct();
    }

    public function GetRoledata($id = null)
    {
        if($id)
        {
            $sql = "SELECT * FROM uac_roles WHERE id = '".$id."'";
            $query = $this->db->query($sql);
            return $query->row();
        }

        $sql = "SELECT * FROM uac_roles ORDER BY rolename";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function GetSubMenudata($id = null)
    {
        if($id)
        {
            $sql = "SELECT a.*,b.menu_name  ,(CASE WHEN b.parent_id is null THEN '' else (SELECT z.menu_name FROM uac_menus as z WHERE z.id=b.parent_id) END) as parent_name FROM uac_sub_menu as a,  uac_menus as b WHERE a.menu_id=b.id and a.id = '".$id."' ";
            $query = $this->db->query($sql);
            return $query->row();
        }

        $sql = "SELECT a.*,b.menu_name  ,(CASE WHEN b.parent_id is null THEN '' else (SELECT z.menu_name FROM uac_menus as z WHERE z.id=b.parent_id) END) as parent_name FROM uac_sub_menu as a,  uac_menus as b WHERE a.menu_id=b.id ORDER BY a.sub_menu_name";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function GetMenudata($id = null)
    {
        if($id)
        {
            $sql = "SELECT a.*, (CASE WHEN parent_id is null THEN '' else (SELECT z.menu_name FROM uac_menus as z WHERE z.id=a.parent_id) END) as parent_name FROM uac_menus as a WHERE a.id = '".$id."' ";
            $query = $this->db->query($sql);
            return $query->row();
        }

        $sql = "SELECT a.*, (CASE WHEN parent_id is null THEN '' else (SELECT z.menu_name FROM uac_menus as z WHERE z.id=a.parent_id) END) as parent_name FROM uac_menus as a ORDER BY a.parent_id, a.menu_name";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function GetMenuByParentdata($id = null, $parent_id=null)
    {       
        $cond  ='';
        if($parent_id){
            $cond = " and ( a.parent_id='$parent_id' or a.id='$parent_id') ";
        }

        if($id)
        {
            $sql = "SELECT a.*, (CASE WHEN parent_id is null THEN '' else (SELECT z.menu_name FROM uac_menus as z WHERE z.id=a.parent_id) END) as parent_name FROM uac_menus as a WHERE a.id = '".$id."' $cond ";
            $query = $this->db->query($sql);
            return $query->row();
        }

        $sql = "SELECT a.*, (CASE WHEN parent_id is null THEN '' else (SELECT z.menu_name FROM uac_menus as z WHERE z.id=a.parent_id) END) as parent_name FROM uac_menus as a where a.active='1' $cond ORDER BY a.parent_id, a.menu_name";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function GetParentMenudata($id = null)
    {
        if($id)
        {
            $sql = "SELECT a.*  FROM uac_menus as a WHERE a.id = '".$id."' ";
            $query = $this->db->query($sql);
            return $query->row();
        }

        $sql = "SELECT a.* FROM uac_menus as a where id  IN ( SELECT parent_id FROM uac_menus ) ORDER BY a.parent_id, a.menu_name";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function GetUserdata($user_id = null)
    {
        if($user_id)
        {
            $sql = "SELECT * FROM users WHERE user_id = '".$user_id."'";
            $query = $this->db->query($sql);
            return $query->row();
        }

        $sql = "SELECT * FROM users WHERE status='ACTIVE' ORDER BY first_name, last_name";
        $query = $this->db->query($sql);
        return $query->result();
    }


}