<?php

class Sales_model extends CI_Model
{
    function __consturct()
    {
        parent::__construct();
    }

  public function getSalesitems($head_id,$id=null)
  {
    if($id)
    {
        $sql="select a.*,a.id as salesitem_id,b.*,c.* from salesorderitems as a ,salesorderheader as b,item_master as c where a.header_id='$head_id' and a.header_id=b.id and a.ItemCode=c.item_code and a.id='$id'";
        //echo $sql; die;
       
          $result = $this->db->query($sql);
         return $result->row();
    }

    $sql="select a.*,a.id as salesitem_id,b.*,c.* from salesorderitems as a ,salesorderheader as b,item_master as c where a.header_id='$head_id' and a.header_id=b.id and a.ItemCode=c.item_code";
   
      $result = $this->db->query($sql);
      return $result->result();


  } 



    public function getAssignprocess($process_header_id)
    {
        

        $sql="SELECT spd.id AS detail_id ,spd.group_impression,spl.layoutName, pg.name AS group_name, spd.layout_id, spd.group_id, spd.process_id, p.id AS process_id, p.process_name, p.process_code,spi.process_impression,spi.sequence_no, spp.parameter_id, prm.parameter_name AS parameter_name, spp.parameter_value 
            FROM sales_process_detail spd  
            LEFT JOIN product_layout spl ON spl.id = spd.layout_id 
            LEFT JOIN process_group pg ON pg.id = spd.group_id CROSS APPLY STRING_SPLIT(spd.process_id, ',') AS pid 
            LEFT JOIN process p ON p.id = TRY_CAST(LTRIM(RTRIM(pid.value)) AS INT) 
            LEFT JOIN sales_process_parameters spp ON spp.sales_detail_id = spd.id AND spp.process_id = p.id
             LEFT JOIN parameters prm ON prm.id = spp.parameter_id
            LEFT JOIN sales_process_impression as spi ON spd.id=spi.sales_process_detail_id and spi.process_id=p.id 
             WHERE spd.header_id=" . intval($process_header_id) . " 
             ORDER BY spl.sequence,pg.sequence, p.id";

          $result= $this->db->query($sql);
          return $result->result();
    }


    public function getSalesProcessheader($id=null)
    {
         if ($id) {
        return $this->db
            ->where('id', $id)
            ->get('sales_process_header')
            ->row();
    }


    }


}
?>