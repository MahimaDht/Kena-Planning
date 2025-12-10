<?php

class Bom_model extends CI_Model
{
    function __consturct()
    {
        parent::__construct();
    }

    public function getBomHeader($id=null)
    {
        if($id)
        {
            $sql="SELECT * FROM BOMHeader where id='".$id."'";
              $query = $this->db->query($sql);
            return $query->row();
        }

        $sql="SELECT * FROM BOMHeader";
         $query = $this->db->query($sql);
         return $query->result();
    }

    public function getBomItems($header_item,$id = null)
    {
        if($id)
        {
            $sql = "SELECT * FROM BOMItems WHERE BOMHeaderId='".$header_item."' and id = '".$id."'";
            $query = $this->db->query($sql);
            return $query->row();
        }

        $sql = "SELECT * FROM BOMItems WHERE BOMHeaderId='".$header_item."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
}
?>