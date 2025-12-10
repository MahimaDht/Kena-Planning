<?php

	class Employee_model extends CI_Model{


	function __consturct(){
	parent::__construct();
	
	}

	public function getdesignation(){
	$query = $this->db->get('designation');
	$result = $query->result();
	return $result;
	}
   public function Does_empsalary_exists($employeeid) {
        $sql = "SELECT emp_id FROM m_emp_salary
    WHERE emp_id='$employeeid'";
    $result=$this->db->query($sql);
        if ($result->row()) {
            return $result->row();
        } else {
            return false;
        }
    }
    public function GetEmployeeId($id){
        $sql = "SELECT em_password FROM employee WHERE em_id='$id'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result; 
    }
   public function getallusers($em_id= null)
   {
    if ($em_id)
    {
      $sql = "SELECT a.*, c.rolename FROM employee as a, uac_roles as c  WHERE a.role_id=c.id and a.em_id ='$em_id'  ";
      $query=$this->db->query($sql);
      $result = $query->row();
      return $result;
    }

    $sql = "SELECT a.*, c.rolename
            FROM employee as a
            LEFT JOIN uac_roles as c ON a.role_id = c.id
            WHERE a.status = 'ACTIVE' and em_role != 'SUPER ADMIN' ";
                $query=$this->db->query($sql);
                $result = $query->result();
                return $result;
  }
  
    public function Add($data){
        $this->db->insert('employee',$data);
       }
    public function GetBasic($id)
    {
      $sql = "SELECT * FROM employee WHERE em_id='$id'";
        $query=$this->db->query($sql);
		    $result = $query->row();
		  return $result;          
    }

    public function Update($data,$id){
		$this->db->where('em_id', $id);
		return $this->db->update('employee',$data);        
    }
    public function updateEmployeeData($id,$data)
    {
      $this->db->where('em_id', $id);
      return $this->db->update('employee',$data);  

    }


 public function Reset_Password($id,$data)
    {
        // print_r($data);die;
      $this->db->where('em_id', $id);
      $this->db->update('employee',$data);        
    }


    public function getuserByuserType($type = null)
    {
        $sql = "SELECT e.*
                FROM employee e
                CROSS APPLY STRING_SPLIT(e.user_type, ',') s
                WHERE e.status = 'ACTIVE'";

        if ($type) {
            $sql .= " AND LTRIM(RTRIM(s.value)) = " . $this->db->escape($type);
        }

        $result = $this->db->query($sql)->result();
        return $result;
    }



    }
?>