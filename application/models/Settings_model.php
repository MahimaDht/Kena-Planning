<?php

	class Settings_model extends CI_Model{


	function __consturct(){
	parent::__construct();
	
	}
    public function GetSettingsValue(){
		$settings = $this->db->dbprefix('settings');
        $sql = "SELECT * FROM $settings";
		$query=$this->db->query($sql);
		$result = $query->row();
		return $result;	        
    }
       public function Log_select()
       {
         $sql = "SELECT a.*,b.first_name,b.last_name FROM login_log as a,employee as b where a.emp_id= b.em_id and current_date()=date(login_date) order by a.id DESC ";
	 	 $query=$this->db->query($sql);
		 $result = $query->result();
		return $result;
        }
    public function SettingsUpdate($id,$data){
		$this->db->where('id', $id);
		$this->db->update('settings',$data);		
	}        
    }