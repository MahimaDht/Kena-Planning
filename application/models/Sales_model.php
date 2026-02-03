<?php

class Sales_model extends CI_Model
{
    function __consturct()
    {
        parent::__construct();
    }

  public function getSalesitems($DocEntry,$LineNum=null)
  {


    if($LineNum!=null && $LineNum!='')
    {
        $sql="select a.*,a.id as salesitem_id,b.*,c.* from salesorderitems as a ,salesorderheader as b,item_master as c where a.DocEntry='$DocEntry' and a.DocEntry=b.DocEntry and a.ItemCode=c.item_code and a.LineNum='$LineNum'";
   
       
          $result = $this->db->query($sql);
         return $result->row();
    }
    else{
            $sql="select a.*,a.id as salesitem_id,b.*,c.* from salesorderitems as a ,salesorderheader as b,item_master as c where a.DocEntry='$DocEntry' and a.DocEntry=b.DocEntry and a.ItemCode=c.item_code";
       
            $result = $this->db->query($sql);
            return $result->result();
    
    }


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


//     public function getItemsByDocEntries($doc_entries = [])
//   {
//     if (!empty($doc_entries)) {



//        $this->db->select('a.*, b.DocNum AS salesorder, b.CardName');
//         $this->db->from('production_order AS a');
//         $this->db->join(
//             'salesorderheader AS b',
//             'a.origin_doc_entry = b.DocEntry',
//             'left'
//         );
//         $this->db->where_in('a.doc_entry', $doc_entries);

//         $query = $this->db->get();
//         $result = $query->result();

       
//         return $result;
//     }
//     return [];
//   }

public function getItemsByDocEntries($docEntries)
{   
   
    if (is_string($docEntries)) {
    $docEntries = json_decode($docEntries, true);
}
  

    $entries = implode(',', array_map([$this->db, 'escape'], (array) $docEntries));
    // $sql = "
    //     SELECT 
    //         p.doc_entry,
    //         p.doc_num,
    //         so.DocNum as salesorder,
    //         so.CardName,
    //         p.item_code,
    //         p.item_name,
    //         p.product_no,
    //         p.product_name,
    //         p.planned_qty
    //     FROM production_order p
       
    //     OUTER APPLY (
    //         SELECT TOP 1 DocNum, CardName
    //         FROM salesorderheader
    //         WHERE DocEntry = p.origin_doc_entry
    //         ORDER BY DocEntry DESC
    //     ) so
    //     WHERE p.doc_entry IN ($entries)
    // ";


     $sql = "
        SELECT 
            p.doc_entry,
            p.doc_num,
            p.item_code,
            p.item_name,
            p.itemuomname,
            p.product_no,
            p.product_name,
            p.planned_qty,
            p.item_planned_qty,
            p.id
        FROM production_order p
        WHERE  p.itemware_house ='VSEMIFIN' and p.doc_entry IN ($entries)";

    $items = $this->db->query($sql)->result();
    return $items;
}

public function getItemsByIds($ids)
{
    if (empty($ids)) return [];
    
    // Ensure IDs are integers to prevent injection
    $ids = array_map('intval', $ids);
    $idsList = implode(',', $ids);

    $sql = "
        SELECT 
            p.id,
            p.doc_entry,
            p.doc_num,
            p.item_code,
            p.item_name,
            p.itemuomname,
            p.product_no,
            p.product_name,
            p.planned_qty,
            p.item_planned_qty,
            p.itemware_house
        FROM production_order p
        WHERE p.id IN ($idsList)
    ";

    return $this->db->query($sql)->result();
}

// public function getItemsByDocEntryAndItemCode($items)
// {
//     if (empty($items)) return [];
    
//     // Build OR conditions for (doc_entry = X AND item_code = Y)
//     $conditions = [];
//     foreach ($items as $item) {
//         $docEntry = $this->db->escape($item['doc_entry']);
//         $itemCode = $this->db->escape($item['item_code']);
//         $conditions[] = "(p.doc_entry = $docEntry AND p.item_code = $itemCode)";
//     }
    
//     $whereClause = implode(' OR ', $conditions);

//     $sql = "
//         SELECT 
//             p.id,
//             p.doc_entry,
//             p.doc_num,
//             p.item_code,
//             p.item_name,
//             p.itemuomname,
//             p.product_no,
//             p.product_name,
//             p.planned_qty,
//             p.item_planned_qty,
//             p.itemware_house
//         FROM production_order p
//         WHERE ($whereClause)
//         AND p.itemware_house ='VSEMIFIN'
//     ";

//     return $this->db->query($sql)->result();
// }





}
?>