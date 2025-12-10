<?php

	class General_model extends CI_Model{


	function __consturct(){
	parent::__construct();
	
	}


    public function getCompanyData($id= null)
    {
        if ($id)
        {
          $sql = "SELECT * FROM company WHERE code  ='$id'  ";
          $query=$this->db->query($sql);
          $result = $query->row();
          return $result;
        }

        $sql = "SELECT * FROM company";
        $query=$this->db->query($sql);
        $result = $query->result();
        return $result;
    }
    public function getUnitData($id= null)
    {
        if ($id)
        {
          $sql = "SELECT * FROM Unit WHERE code  ='$id'  ";
          $query=$this->db->query($sql);
          $result = $query->row();
          return $result;
        }

        $sql = "SELECT * FROM Unit";
        $query=$this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function email_template_editor_function($template_id = null , $email_input_attributes = null) 
    {

        $email_sql = "SELECT email_subject, email_body,email_account_integra FROM dht_email_template WHERE action_id = ?";
        $email_query = $this->db->query($email_sql, array($template_id));
        $email_result = $email_query->row_array();

       $data = $this->emailContentMaker($email_result['email_body'], json_decode($email_input_attributes));
       $dataSubject = $this->emailContentMaker($email_result['email_subject'], json_decode($email_input_attributes));
       
       $data = json_decode($data);
       $dataSubject = json_decode($dataSubject);
       
       $emailSubject = $dataSubject->toContent;  
       $emailContent = $data->toContent;  
       return array('emailContent' =>$emailContent,'toSubject' =>$emailSubject,'to_emails' =>$email_result['email_account_integra']);

    }

public function emailContentMaker($master_body, $content_body) 
{
    $replacements = [];
    
    foreach (emailtextReplaceVariable() as $key => $description) {
        $property = trim($key, '{}');
        $replacements[$key] = isset($content_body->$property) ? $content_body->$property : '';
    }

    return json_encode(['toContent' => strtr($master_body, $replacements)]);
}


    public function insert_into_email_queue($to_emails,$cc_emails ,$toSubject ,$message_body )
    {
            
        $data = array(
                        'recipient'     => $to_emails,
                        'cc_email'      => $cc_emails, // Add CC email if needed
                        'email_subject' => $toSubject,
                        'email_message' => $message_body,
                        'email_status'  => 'pending',
                        'created_at'    => date('Y-m-d H:i:s') // Ensure correct timestamp format for MSSQL
                    );
           return     $this->db->insert('dht_email_queue', $data);

    }



    }
?>