<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

	
    public function getProductData($id=null,$status=null)
    {
      if($id)
      {
        $sql ="select * from products where  is_deleted='N' and id='$id'";
        $result=$this->db->query($sql);
        return $result->row();
      }

        $sql ="select * from products where is_deleted='N'";

        if($status)
        {
          $sql.=" and status='$status'";
        }


         $result=$this->db->query($sql);
        return $result->result();
    }

     public function getProductLayoutData($id=null,$status=null)
    {
      if($id)
      {
        $sql ="select * from product_layout where is_deleted='N' and id='$id'";
        $result=$this->db->query($sql);
        return $result->row();
      }

        $sql ="select * from product_layout where is_deleted='N'";

         if($status)
        {
          $sql.=" and status='$status'";
        }
       

         $result=$this->db->query($sql);
        return $result->result();
    }

    public function getProcessGroup($id=null,$status=null)
    {
      if($id)
      {
         $sql ="select * from process_group where  id='$id' and is_deleted='N'";

         
        $result=$this->db->query($sql);
        return $result->row();
      }

       $sql ="select * from process_group where  is_deleted='N'";

        if($status)
         {
            $sql .=" and status='$status'";
         }
         $result=$this->db->query($sql);
        return $result->result();
    }


   
    public function getProcessSubgroup($id=null,$group=null,$status=null)
    {
      if($id)
      {
         $sql ="select a.*,b.name as main_group from process_subgroup as a,process_group as b  where a.group_name=b.id and a.id='$id' and a.is_deleted='N'";

          if($group)
         {
          $sql .=" and a.group_name='$group'";
         }


        $result=$this->db->query($sql);
        return $result->row();
      }

       $sql ="select a.*,b.name as main_group from process_subgroup as a,process_group as b  where a.group_name=b.id and a.is_deleted='N' ";

         if($group)
         {
          $sql .=" and a.group_name='$group'";
         }
          if($status)
         {
             $sql .=" and a.status='$status'";
         }
       
         $result=$this->db->query($sql);
        return $result->result();
    }


    public function getProcess($id=null)
    {

      if($id)
      {
              $sql= "SELECT a.*, b.name AS group_name, c.subgroup_name FROM process AS a INNER JOIN process_group AS b ON a.group_id = b.id LEFT JOIN process_subgroup AS c ON a.subgroup_id = c.id WHERE a.is_deleted = 'N' and a.id='$id'";
       

          $result=$this->db->query($sql);
          return $result->row();
      }

      $sql= "SELECT a.*, b.name AS group_name, c.subgroup_name FROM process AS a INNER JOIN process_group AS b ON a.group_id = b.id LEFT JOIN process_subgroup AS c ON a.subgroup_id = c.id WHERE a.is_deleted = 'N'";

      $result=$this->db->query($sql);
      return $result->result();


    }

    public function getProcessMapping($id=null)
    {
      if($id){

        $sql="SELECT a.*, b.name AS groupName, c.subgroup_name, d.layoutName, e.process_name, param.parameters , pt.product_name  
          FROM mapping_processLayout a JOIN process_group b ON a.process_group_id = b.id
          LEFT JOIN process_subgroup c ON a.process_subgroup_id = c.id 
          JOIN product_layout d ON a.product_layout_id = d.id 
          JOIN process e ON a.process_id = e.id 
           JOIN products pt ON a.product_type_id = pt.id
          OUTER APPLY (
            SELECT STRING_AGG(p.parameter_name, ', ') AS parameters
            FROM process_mapping_parameters mp
            JOIN parameters p ON p.id = mp.parameter_id
            WHERE mp.mapping_id = a.id
            ) param
        WHERE a.product_type_id = '$id' AND a.is_deleted = 'N' order by d.id";
       $result= $this->db->query($sql);
       return $result->result();
     }

    $sql = "SELECT 
            a.*, 
            b.name AS groupName, 
            c.subgroup_name, 
            d.layoutName, 
            e.process_name, 
            pt.product_name  
        FROM mapping_processLayout a
        JOIN process_group b ON a.process_group_id = b.id
        LEFT JOIN process_subgroup c ON a.process_subgroup_id = c.id
        JOIN product_layout d ON a.product_layout_id = d.id
        JOIN process e ON a.process_id = e.id
        JOIN products pt ON a.product_type_id = pt.id
        WHERE a.is_deleted = 'N'
        ORDER BY d.id";

      $result = $this->db->query($sql);
      return $result->result();

    }



    public function getParameter($id=null,$status=null)
    {

        if($id)
        {
           $sql="select * from parameters where id='$id' and is_deleted='N'";
            $result=$this->db->query($sql);
            return $result->row();

        }

        $sql="select * from parameters where is_deleted='N'";
        if($status)
        {

          $sql .=" and status='$status'";
        }
         $result=$this->db->query($sql);
        return $result->result();

    }

    public function getParameterByProcessId($process_id =null)
    {

      if($process_id){
        $sql="SELECT 
              p.id AS process_id,
              p.process_name,
              p.parameter_ids,
              param.*

          FROM process p
          OUTER APPLY (
              SELECT TRY_CAST(value AS INT) AS param_id
              FROM STRING_SPLIT(CAST(p.parameter_ids AS VARCHAR(MAX)), ',')
          ) AS param_split
          Left JOIN parameters param ON param.id = param_split.param_id where p.id='$process_id'";

         $parameters=$this->db->query($sql)->result();
         return $parameters;
      }

      $sql="SELECT 
              a.*,
              b.name AS group_name,
              c.subgroup_name,
              STUFF((
                SELECT ', ' + p.parameter_name
                FROM STRING_SPLIT(CAST(a.parameter_ids AS VARCHAR(MAX)), ',') AS s
                JOIN parameters p ON p.id = CAST(s.value AS INT)
                FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')
              , 1, 2, '') AS parameter_names
              FROM 
              process AS a
              INNER JOIN 
              process_group AS b ON a.group_id = b.id
              LEFT JOIN 
              process_subgroup AS c ON a.subgroup_id = c.id
              WHERE 
              a.is_deleted = 'N'";

      $parameters=$this->db->query($sql)->result();
      return $parameters;

    }


public function getFormula($id=null,$status=null)
{
  if($id)
  {
    $sql="select * from m_formula where id='$id' and is_deleted='N'";
    $result= $this->db->query($sql)->row();
    return $result;
  }

  $sql="select * from m_formula where is_deleted='N'";
  if($status)
  {
    $sql .=" and status='".$status."'";
  }

  $result=$this->db->query($sql)->result();
  return $result;

}

public function getmachines($id=null,$status=null)
{
  if($id)
  {
    $sql="SELECT * FROM machines where is_deleted='N' and id='".$id."'";
    $result= $this->db->query($sql)->row();
    return $result;
  }

  $sql="SELECT * FROM machines where is_deleted='N'";

  if($status)
  {
    $sql .=" and status='".$status."'";
  }

  $result=$this->db->query($sql)->result();
  return $result;

}

public function getProcessByproduct_type($product_type_id,$group,$subgroup=null)
{
  $sql = "SELECT distinct(a.process_id),b.process_name   from mapping_processLayout as a ,process as b where a.process_id=b.id and a.product_type_id='$product_type_id' and process_group_id='$group' and b.status='ACTIVE'and a.is_deleted='N'";
  //echo $sql; die;
  if($subgroup)
  {
    $sql .=" and process_subgroup_id='".$subgroup."'" ;
  }

  $result = $this->db->query($sql)->result();
  return $result;
}

public function getGroupByProduct($product_type_id,$layout_id=null)
{
  $sql = "SELECT distinct(a.process_group_id),b.name from mapping_processLayout as a ,process_group as b where a.process_group_id=b.id and a.product_type_id='$product_type_id' and a.is_deleted='N'";

  if($layout_id)
  {
    $sql .=" and a.product_layout_id='".$layout_id."'";
  }
  //echo $sql; die;
  $result = $this->db->query($sql)->result();
  return $result;
}

public function getProductsBytype($product_type_id)
{
  $sql ="SELECT item_code,item_name from item_master where deleted='N' and u_product_type='".$product_type_id."'";

  $result=$this->db->query($sql)->result();
  return $result;
}

public function getMachineMappingList($product_type_id,$id=null)
{

  if($id)
  {
     // $sql ="SELECT a.*, b.machine_name, c.process_name, d.name
     //    FROM mapping_productTypeMachine AS a
     //    CROSS APPLY STRING_SPLIT(a.machine_id, ',') AS m
     //    JOIN machines AS b ON b.id = CAST(m.value AS INT)
     //    JOIN process AS c ON a.process_id = c.id
     //    JOIN process_group AS d ON a.process_group_id = d.id where a.product_type_id='".$product_type_id."' and a.id='".$id."' and a.is_deleted='N'";


    $sql="SELECT 
                a.*,
                (
                    SELECT STRING_AGG(pp.process_name, ', ')
                    FROM STRING_SPLIT(a.process_id, ',') AS p
                    JOIN process AS pp ON pp.id = CAST(p.value AS INT)
                ) AS process_name,
                d.name AS process_group_name,
                (
                    SELECT STRING_AGG(b.machine_name, ', ')
                    FROM STRING_SPLIT(a.machine_id, ',') AS m
                    JOIN machines AS b ON b.id = CAST(m.value AS INT)
                ) AS machine_name,d.name
            FROM mapping_productTypeMachine AS a
            JOIN process_group AS d ON a.process_group_id = d.id
            WHERE a.product_type_id = '".$product_type_id."'
              AND a.is_deleted = 'N' and a.id='".$id."'";

     $result= $this->db->query($sql)->row();

      return $result;
  }

  // $sql ="SELECT a.*, b.machine_name, c.process_name, d.name
  //       FROM mapping_productTypeMachine AS a
  //       CROSS APPLY STRING_SPLIT(a.machine_id, ',') AS m
  //       JOIN machines AS b ON b.id = CAST(m.value AS INT)
  //       JOIN process AS c ON a.process_id = c.id
  //       JOIN process_group AS d ON a.process_group_id = d.id where  a.product_type_id='".$product_type_id."' and a.is_deleted='N'";


        $sql="SELECT 
                a.*,
                (
                    SELECT STRING_AGG(pp.process_name, ', ')
                    FROM STRING_SPLIT(a.process_id, ',') AS p
                    JOIN process AS pp ON pp.id = CAST(p.value AS INT)
                ) AS process_name,
                d.name AS process_group_name,
                (
                    SELECT STRING_AGG(b.machine_name, ', ')
                    FROM STRING_SPLIT(a.machine_id, ',') AS m
                    JOIN machines AS b ON b.id = CAST(m.value AS INT)
                ) AS machine_name,d.name
            FROM mapping_productTypeMachine AS a
            JOIN process_group AS d ON a.process_group_id = d.id
            WHERE a.product_type_id = '".$product_type_id."'
              AND a.is_deleted = 'N'";


      //echo $sql; die;
   $result= $this->db->query($sql)->result();

    return $result;
}

public function getMappingProductsMachine($id)
{
  $sql = "SELECT * From productMachine_mapping where productTypeMachine_id='$id'";
  $result=$this->db->query($sql)->result();

  return $result; 

}


public function getRoutingHeaderData($id=null,$product_type=null)
{

  if($id)
  {
    $sql = "select * from rout_header where  id='$id'";
    $result= $this->db->query($sql)->row();
    return $result;
  }
    $sql="select a.*,b.product_name from rout_header as a ,products as b where a.is_deleted='N' and a.product_type=b.id ";

    if($product_type)
    {
       $sql .=" and a.product_type='".$product_type."'";
    }

    $sql .=" Order BY id,product_name";
    $result= $this->db->query($sql)->result();
    return $result;
}

public function getRoutingDetail($rout_id,$id=null)
{

  if($id)
  {
    $sql = "select * from rout_detail where rout_id='$rout_id' and id='$id'";
    $result= $this->db->query($sql)->row();
    return $result;
  }
    $sql="select * from rout_detail where  rout_id='$rout_id'";
    $result= $this->db->query($sql)->result();
    return $result;
}

public function getrawMaterials($id=null)
{
  if($id)
  {
    $sql="select * from m_rawMaterial where is_deleted='N' and id='".$id."'";
    $result= $this->db->query($sql);
    return $result->row();

  }
 
    $sql="select * from m_rawMaterial where is_deleted='N' ";
    $result= $this->db->query($sql);
    return $result->result();

 }

 public function getItems($material_type=null)
 {
    $sql="SELECT item_code,item_name From item_master WHERE deleted = 'N'";

      if($material_type)
      {
        $sql .=" and material_type_name='".$material_type."'";
      }

      $result= $this->db->query($sql)->result();

      return $result;

 }

 public function getgauges($id=null)
{
  if($id)
  {
    $sql="select * from m_gauge where is_deleted='N' and id='".$id."'";
    $result= $this->db->query($sql);
    return $result->row();

  }
 
    $sql="select * from m_gauge where is_deleted='N' ";
    $result= $this->db->query($sql);
    return $result->result();

 }


 public function fetchProcessSubGroupByProductType($product_type,$group)
 {
   $sql ="select distinct(a.process_subgroup_id),b.subgroup_name from mapping_processLayout as a,process_subgroup as b where a.product_type_id='".$product_type."' and b.id=a.process_subgroup_id and b.is_deleted='N' and b.status='ACTIVE' and process_group_id='".$group."'";
      $result= $this->db->query($sql);
     return $result->result();
 }





}