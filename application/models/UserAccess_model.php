<?php 

class UserAccess_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getRoles($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM uac_roles   WHERE id = '".$id."'";
			$query = $this->db->query($sql);
			return $query->row();
		}

          $sql = "SELECT * FROM uac_roles  WHERE status=1  ";
		
		
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function getuserGroups($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM uac_user_groups   WHERE id = '".$id."'";
			$query = $this->db->query($sql);
			return $query->row();
		}

          $sql = "SELECT * FROM uac_user_groups  WHERE status=1 ";
		
		
		$query = $this->db->query($sql);
		return $query->result();
	}


}