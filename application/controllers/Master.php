<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('dashboard_model');
        $this->load->model('employee_model');
        $this->load->model('login_model');
        $this->load->model('settings_model');  
        $this->load->model('Access_model');   
        $this->load->model('master_model');   
        $this->load->model('sales_model');
         $this->load->model('bom_model');
    }


    public function product()
    {
        if($this->session->userdata('user_login_access')!=false)
        {
          $data['urldata'] = $this->Access_model->get_menu_data();
          $access=$this->Access_model->getPermissionByAction();
          if($access!='success')
          {
            $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
            $data['title'] = 'Something went wrong';
            $this->load->view('errorPage', $data);
            return;
        }

        

        $data['title'] = $data['urldata']->menu_name;
        $data['product_type']=$this->master_model->getProductData();
        $this->load->view('master/product/product_type',$data);
    }
    else{

       redirect(base_url(), 'refresh');
   }
}

public function save_product()
{

    if($this->session->userdata('user_login_access')!=false)
    {
        $id=$this->input->post('id');


        $data['title'] ='Product Type';

        $pt_code= $this->input->post('pt_code');
        $product_name=$this->input->post('product_name');
        $description=$this->input->post('description');
        $status=$this->input->post('status');

        $sql="select count(*) as count from products  WHERE (product_code = '$pt_code' OR product_name = '$product_name')";

        if($id!='')
        {
            $sql .=" and id!='$id'";
        }
        $checker=$this->db->query($sql)->row()->count;
        if($checker > 0)
        {
         $this->session->set_flashdata('error', 'This Product Code Or Name Alrady Exist');

     }else{

        $data= array(

            'product_code'=>$pt_code,
            'product_name'=>$product_name,
            'description'=>$description,
            'status'=>$status


        );

        if($id!='')
        {
            $data1=array(
                'updated_at'=>date('Y-m-d H:i:s'),
                'updated_by'=>$this->session->userdata('user_login_id')

                );

            $data=array_merge($data,$data1);
            $this->db->where('id',$id);
            $insert=$this->db->update('products',$data);
        }
        else{

             $data1=array(
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$this->session->userdata('user_login_id')

                );
              $data=array_merge($data,$data1);
           $insert=$this->db->insert('products',$data);
       }

       if($insert)
       {
           $this->session->set_flashdata('success',"Successfully Inserted");
       }
       else{
           $this->session->set_flashdata('error',"Somthing Went Wrong");
       }
   }

         //$data['product_type']=$this->master_model->getProductData();

   redirect('Master/product');

}
else{

   redirect(base_url(), 'refresh');
}
}

public function edit_product($id)
{   
    $id=base64_decode($id);

    $data['title'] = 'Product Type';
    $data['editdata'] = $this->master_model->getProductData($id);
    $data['product_type']=$this->master_model->getProductData();
    $this->load->view('master/product/product_type',$data);
}


public function remove_product($id)
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=base64_decode($id);
        $data=array('is_deleted'=>'Y');
        $this->db->where('id',$id);
        $update=$this->db->update('products',$data);

        if($update)
        {
            $this->session->set_flashdata('success',"Successfully Deleted");
        }
        redirect('Master/product');
    }
    else{
    
       redirect(base_url(),'refresh');
    }

}
//////////////////////////////////////////////// Product Layout ////////////////////////

public function product_layout()
{
    if($this->session->userdata('user_login_access')!=false)
    {
     $data['urldata'] = $this->Access_model->get_menu_data();
     $access=$this->Access_model->getPermissionByAction();
     if($access!='success')
     {
        $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
        $data['title'] = 'Something went wrong';
        $this->load->view('errorPage', $data);
        return;
    }


    $data['title'] = $data['urldata']->menu_name;
    $data['product_layout']=$this->master_model->getProductLayoutData();
    $this->load->view('master/product/product_layout',$data);
    }
    else{

       redirect(base_url(), 'refresh');
    }
}


public function save_product_layout()
{

      if($this->session->userdata('user_login_access')!=false)
    {
        $id=$this->input->post('id');


        $data['title'] ='Product Layout';

        $pl_code= $this->input->post('pl_code');
        $layout_name=$this->input->post('name');
        $description=$this->input->post('description');
        $status=$this->input->post('status');
        $sequence=$this->input->post('sequence');
       $having_page=$this->input->post('having_page') ? $this->input->post('having_page') :'N';

        $sql="select count(*) as count from product_layout where pl_code='$pl_code'";
        if($id!='')
        {
            $sql .=" and id!='$id'";
        }
        $checker=$this->db->query($sql)->row()->count;
        if($checker > 0)
        {
         $this->session->set_flashdata('error', 'This Product Layout Code Alrady Exist');

     }else{

            $data= array(

                'pl_code'=>$pl_code,
                'layoutName'=>$layout_name,
                'description'=>$description,
                'status'=>$status,
                'having_page'=>$having_page,
                'sequence'=>$sequence


            );

            if($id!='')
            {
                  $data1=array(
                    'updated_at'=>date('Y-m-d H:i:s'),
                    'updated_by'=>$this->session->userdata('user_login_id')

                    );

                   $data=array_merge($data,$data1);
                $this->db->where('id',$id);
                $insert=$this->db->update('product_layout',$data);
            }
            else{

                 $data1=array(
                    'created_at'=>date('Y-m-d H:i:s'),
                    'created_by'=>$this->session->userdata('user_login_id')

                    );
                  $data=array_merge($data,$data1);
               $insert=$this->db->insert('product_layout',$data);
           }

           if($insert)
           {
               $this->session->set_flashdata('success',"Successfully Inserted");
           }
           else{
               $this->session->set_flashdata('error',"Somthing Went Wrong");
           }
        }

         //$data['product_type']=$this->master_model->getProductData();

        redirect('Master/product_layout');

    }
    else{

       redirect(base_url(), 'refresh');
    }
}


    public function edit_product_layout($id)
    {   
        $id=base64_decode($id);

        $data['title'] = 'Product Layout';
        $data['editdata'] = $this->master_model->getProductLayoutData($id);
        $data['product_layout']=$this->master_model->getProductLayoutData();
        $this->load->view('master/product/product_layout',$data);
    }


    public function product_mapping()
    {
        if($this->session->userdata('user_login_access')!=false)
        {   
           
             $data['urldata'] = $this->Access_model->get_menu_data();
             $access=$this->Access_model->getPermissionByAction();
             if($access!='success')
             {
                $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
                $data['title'] = 'Something went wrong';
                $this->load->view('errorPage', $data);
                return;
            }


            $data['title'] = $data['urldata']->menu_name;
            $data['product_type']=$this->master_model->getProductData(null,'ACTIVE');
            $data['product_layout']=$this->master_model->getProductLayoutData();
            $this->load->view('master/product/product_mapping',$data);
        }
        else{
             redirect(base_url(),'refresh');
        }

    }

public function remove_productLayout($id)
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=base64_decode($id);
        $data=array('is_deleted'=>'Y');
        $this->db->where('id',$id);
        $update=$this->db->update('product_layout',$data);

        if($update)
        {
            $this->session->set_flashdata('success',"Successfully Deleted");
        }
        redirect('Master/product_layout');
    }
    else{
    
       redirect(base_url(),'refresh');
    }

}
public function getproductTypeById()
{
    $id = $this->input->post('id');

    $product_type = $this->master_model->getProductData($id);
       $layoutIds = explode(',', $product_type->mapping_layouts); 

    
        $layouts = [];
        foreach($layoutIds as $id){
            $layout = $this->db->get_where('product_layout', ['id' => $id])->row();
            if($layout){
                $layouts[] = ['id' => $layout->id, 'layout_name' => $layout->layoutName];
            }
        }

    $data=array(

        'product_type'=>$product_type->product_name,
        'csrfHash' => $this->security->get_csrf_hash(),
        'layoutIds'=>$product_type->mapping_layouts,
        'layouts'=>$layouts

    );

   echo  json_encode($data);
}

public function updateProductMapping()
{
    $id=$this->input->post('id');
    $layout_ids=$this->input->post('layoutIds');

    $layoutids=implode(",",$layout_ids);

    $data=array(

        'mapping_layouts'=>$layoutids,
    );

    $this->db->where('id',$id);
   $update= $this->db->update('products',$data);

   if($update)
   {
    $this->session->set_flashdata('success',"Successfully Updated");

   }
   else{
      $this->session->set_flashdata('error',"Somthing Went Wrong");
   }

   redirect('Master/product_mapping');
}


////////////////////////////////////////////// PROCESS //////////////////////////

public function process_group()
{
    if($this->session->userdata('user_login_id'))
    {

       $data['urldata'] = $this->Access_model->get_menu_data();
       $access=$this->Access_model->getPermissionByAction();
       if($access!='success')
       {
        $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
        $data['title'] = 'Something went wrong';
        $this->load->view('errorPage', $data);
        return;
    }
        $data['title'] = $data['urldata']->menu_name;
        $data['process_group']=$this->master_model->getProcessGroup();
        $this->load->view('master/process/process_group',$data);
    }
    else{
        redirect(base_url(),'refresh');

    }
}


public function save_processGroup()
{

    if($this->session->userdata('user_login_access')!=false)
    {
        $id=$this->input->post('id');


        $data['title'] ='Process Group';

        $code= $this->input->post('code');
        $process_name=$this->input->post('name');
        $description=$this->input->post('description');
        $sequence=$this->input->post('sequence');
        $status=$this->input->post('status');

        $sql="select count(*) as count from process_group where code='$code'";
        if($id!='')
        {
            $sql .=" and id!='$id'";
        }
        $checker=$this->db->query($sql)->row()->count;
        if($checker > 0)
        {
         $this->session->set_flashdata('error', 'This Process Code Alrady Exist');

     }else{

        $data= array(

            'code'=>$code,
            'name'=>$process_name,
            'description'=>$description,
            'status'=>$status,
            'sequence'=>$sequence


        );

        if($id!='')
        {

              $data1=array(
                'updated_at'=>date('Y-m-d H:i:s'),
                'updated_by'=>$this->session->userdata('user_login_id')

                );
            $data=array_merge($data,$data1);
            $this->db->where('id',$id);
            $insert=$this->db->update('process_group',$data);
        }
        else{

             $data1=array(
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$this->session->userdata('user_login_id')

                );
              $data=array_merge($data,$data1);
           $insert=$this->db->insert('process_group',$data);
       }

       if($insert)
       {
           $this->session->set_flashdata('success',"Successfully Inserted");
       }
       else{
           $this->session->set_flashdata('error',"Somthing Went Wrong");
       }
   }

         //$data['product_type']=$this->master_model->getProductData();

   redirect('Master/process_group');

    }
    else{

       redirect(base_url(), 'refresh');
    }
}

public function edit_process_group($id)
{
     $id=base64_decode($id);

        $data['title'] = 'Product Layout';
        $data['editdata'] = $this->master_model->getProcessGroup($id);
        $data['process_group']=$this->master_model->getProcessGroup();
        $this->load->view('master/process/process_group',$data);
    
}

public function process_subGroup()
{

     if($this->session->userdata('user_login_id'))
    {

       $data['urldata'] = $this->Access_model->get_menu_data();
       $access=$this->Access_model->getPermissionByAction();
       if($access!='success')
       {
        $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
        $data['title'] = 'Something went wrong';
        $this->load->view('errorPage', $data);
        return;
    }
        $data['title'] = $data['urldata']->menu_name;
         $data['process_group']=$this->master_model->getProcessGroup(null,'ACTIVE');
        $data['process_subgroup']=$this->master_model->getProcessSubgroup();

        $this->load->view('master/process/process_subgroup',$data);
    }
    else{
        redirect(base_url(),'refresh');

    }
}

public function remove_processgroup($id)
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=base64_decode($id);
        $data=array('is_deleted'=>'Y');
        $this->db->where('id',$id);
        $update=$this->db->update('process_group',$data);

        if($update)
        {
            $this->session->set_flashdata('success',"Successfully Deleted");
        }
        redirect('Master/process_group');
    }
    else{
    
       redirect(base_url(),'refresh');
    }

}

public function save_processSubGroup()
{

    if($this->session->userdata('user_login_access')!=false)
    {
        $id=$this->input->post('id');


        $data['title'] ='Process SubGroup';

        $code= $this->input->post('code');
        $process_name=$this->input->post('name');
        $description=$this->input->post('description');
        $order=$this->input->post('orders');
        $group_name=$this->input->post('group');
        $status=$this->input->post('status');

        $sql="select count(*) as count from process_subgroup where subgroup_code='$code' and is_deleted='N'";

        if($id!='')
        {
            $sql .=" and id!='$id'";
        }
        $checker=$this->db->query($sql)->row()->count;
        if($checker > 0)
        {
         $this->session->set_flashdata('error', 'This Process Subgroup Code Alrady Exist');

     }else{

        $data= array(

            'subgroup_code'=>$code,
            'subgroup_name'=>$process_name,
            'description'=>$description,
            'group_name'=>$group_name,
            'orders'=>$order,
            'status'=>$status

        );

        if($id!='')
        {

              $data1=array(
                'updated_at'=>date('Y-m-d H:i:s'),
                'updated_by'=>$this->session->userdata('user_login_id')

                );
            $data=array_merge($data,$data1);
            $this->db->where('id',$id);
            $insert=$this->db->update('process_subgroup',$data);
        }
        else{

             $data1=array(
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$this->session->userdata('user_login_id')

                );
              $data=array_merge($data,$data1);
           $insert=$this->db->insert('process_subgroup',$data);
       }

       if($insert)
       {
           $this->session->set_flashdata('success',"Successfully Added");
       }
       else{
           $this->session->set_flashdata('error',"Somthing Went Wrong");
       }
   }

         //$data['product_type']=$this->master_model->getProductData();

   redirect('Master/process_subGroup');

    }
    else{

       redirect(base_url(), 'refresh');
    }
}

public function edit_process_subgroup($id)
{
        $id=base64_decode($id);

        $data['title'] = 'Process Subgroup';
        $data['editdata'] = $this->master_model->getProcessSubgroup($id);
        $data['process_group']=$this->master_model->getProcessGroup(null,'ACTIVE');
        $data['process_subgroup']=$this->master_model->getProcessSubgroup();
        $this->load->view('master/process/process_subgroup',$data);


}

public function remove_processSubgroup($id)
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=base64_decode($id);
        $data=array('is_deleted'=>'Y');
        $this->db->where('id',$id);
        $update=$this->db->update('process_subgroup',$data);

        if($update)
        {
            $this->session->set_flashdata('success',"Successfully Deleted");
        }
        redirect('Master/process_subgroup');
    }
    else{
    
       redirect(base_url(),'refresh');
    }

}


public function process()
{
    if($this->session->userdata('user_login_access')!=false)
    {

       $data['urldata'] = $this->Access_model->get_menu_data();
       $access=$this->Access_model->getPermissionByAction();
       if($access!='success')
       {
        $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
        $data['title'] = 'Something went wrong';
        $this->load->view('errorPage', $data);
        return;
       }
        $data['title'] = $data['urldata']->menu_name;
        $data['process_group']=$this->master_model->getProcessGroup(null,'ACTIVE');
        $data['process']=$this->master_model->getParameterByProcessId();
        $data['parameters']=$this->master_model->getParameter(null,'ACTIVE');

        $this->load->view('master/process/manage_process',$data);
    }
    else{

        redirect(base_url(),'refresh');
    }
}

public function getProcessSubgroupByGroup()
{
    $group=$this->input->post('group');
    $subggroupProcess=$this->master_model->getProcessSubgroup('',$group,'ACTIVE');

     $process = '';

 
    if(empty($subggroupProcess)){

        $processList = $this->db->query("select * from process where is_deleted='N' and status='ACTIVE' and group_id='$group'")->result();
        
        foreach ($processList as $value) {
            $process .= '<option value="' . $value->id . '">' .$value->process_name . '</option>';
        }

    }

        $subgroup='';
        foreach($subggroupProcess as $value)
        {
           $subgroup.= '<option value="'.$value->id.'">'.$value->subgroup_name .'</option>';
        }



     $data=array(

        'subgroup'=>$subgroup,
        'process'=>$process,
        'csrfHash' => $this->security->get_csrf_hash(),
     

    );
     echo json_encode($data);

}

public function save_Addprocess()
{
    if($this->session->userdata('user_login_access')!=false)
    {
        
        $group_id=$this->input->post('process_group');
        $subgroup_id=$this->input->post('process_subgroup');
        $code =$this->input->post('code');
        $name=$this->input->post('name');
        $description=$this->input->post('description');
        $status=$this->input->post('status');
        $parameterids=$this->input->post('parametersIds');
        if(!empty($parameterids)){
              $parameter=implode(',', $parameterids);
        }
        else{
            $parameter='';
        }
      

        $data=array(

            'group_id'=>$group_id,
            'subgroup_id'=>$subgroup_id,
            'process_code'=>$code,
            'process_name'=>$name,
            'description'=>$description,
            'status'=>$status,
            'parameter_ids'=>$parameter,
            'created_at'=>date('Y-m-d H:i:s'),
            'created_by'=>$this->session->userdata('user_login_id')


        );

        $sql="select count(*) as count from process where process_code='$code' and is_deleted='N'";

        $checker=$this->db->query($sql)->row()->count;
        if($checker > 0)
        {
           $this->session->set_flashdata('error', 'This Process  Code Alrady Exist');
       }
       else{

       
            $insert= $this->db->insert('process',$data);
        


        if($insert)
        {
            $this->session->set_flashdata("success","Successfully Inseted");
        }
    }


    redirect('Master/process');

    }
    else{
        redirect(base_url(),'refresh');
    }
}


public function save_Editprocess()
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=$this->input->post('id');
        $group_id=$this->input->post('process_group');
        $subgroup_id=$this->input->post('process_subgroup');
        $code =$this->input->post('code');
        $name=$this->input->post('name');
        $description=$this->input->post('description');
        $status=$this->input->post('status');
          $parameterids=$this->input->post('parametersIds');
       // $parameter=implode(',', $parameterids);
        if(!empty($parameterids)){
              $parameter=implode(',', $parameterids);
        }
        else{
            $parameter='';
        }
        $data=array(

            'group_id'=>$group_id,
            'subgroup_id'=>$subgroup_id,
            'process_code'=>$code,
            'process_name'=>$name,
            'description'=>$description,
            'status'=>$status,
             'parameter_ids'=>$parameter,
             'updated_at'=>date('Y-m-d H:i:s'),
            'updated_by'=>$this->session->userdata('user_login_id')


        );

        $sql="select count(*) as count from process where process_code='$code' and is_deleted='N' and id!='$id'";

        $checker=$this->db->query($sql)->row()->count;
        if($checker > 0)
        {
           $this->session->set_flashdata('error', 'This Process  Code Alrady Exist');
       }
       else{

            $this->db->where('id',$id);
            $update= $this->db->update('process',$data);
        


        if($update)
        {
            $this->session->set_flashdata("success","Successfully Updated");
        }
        else{
            $this->session->set_flashdata("error","Something Wrong");
        }
    }


    redirect('Master/process');

    }
    else{
        redirect(base_url(),'refresh');
    }
}

public function remove_process($id)
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=base64_decode($id);
        $data=array('is_deleted'=>'Y');
        $this->db->where('id',$id);
        $update=$this->db->update('process',$data);

        if($update)
        {
            $this->session->set_flashdata('success',"Successfully Deleted");
        }
        else{
            $this->session->set_flashdata('error',"Not Deleted");
        }
        redirect('Master/process');
    }
    else{
    
       redirect(base_url(),'refresh');
    }

}



public function getProcessById()
{
    $id = $this->input->post('id');

    $process = $this->master_model->getProcess($id);

    
    $data=array(

        'process'=>$process,
        'csrfHash' => $this->security->get_csrf_hash(),
        'process_parameter'=>$process->parameter_ids

    );

   echo  json_encode($data);
}


 public function process_mapping()
    {
        if($this->session->userdata('user_login_access')!=false)
        {   
           
             $data['urldata'] = $this->Access_model->get_menu_data();
             $access=$this->Access_model->getPermissionByAction();
             if($access!='success')
             {
                $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
                $data['title'] = 'Something went wrong';
                $this->load->view('errorPage', $data);
                return;
            }


            $data['title'] = $data['urldata']->menu_name;

            $data['product_type']=$this->master_model->getProductData(null,'ACTIVE');
            $data['formulas']=$this->master_model->getFormula('','ACTIVE');

          //  $data['product_layout']=$this->master_model->getProductLayoutData();
            $this->load->view('master/process/mapping_processLayout',$data);
        }
        else{
             redirect(base_url(),'refresh');
        }

    }


public function editprocessMapping($id,$processMap_id=null)
{
  if($this->session->userdata('user_login_access')!=false)
    {
        $id=base64_decode($id);
        if($processMap_id)
        {
            $processMap_id=base64_decode($processMap_id);
            $data['editdata']=$this->db->query("select * from mapping_processLayout where id='".$processMap_id."'")->row();
            $data['parameters']=$this->db->query("select a.*,b.parameter_name from process_mapping_parameters as a,parameters as b where a.parameter_id=b.id and a.mapping_id='".$processMap_id."'")->result();
        }

        $data['productdata']=$this->master_model->getProductData($id);
        $data['process_group']=$this->master_model->getProcessGroup(null,'ACTIVE');
        // $data['process_group']=$this->master_model->getProcessGroup();
       $data['processMapping']=$this->master_model->getProcessMapping($id);
        $data['formulas']=$this->master_model->getFormula('','ACTIVE');


        if(!empty($data['productdata']->mapping_layouts))
        {
            $layoutIds = explode(',', $data['productdata']->mapping_layouts);
            $layoutIds = array_map('intval', $layoutIds); 

            $this->db->where_in('id', $layoutIds);
            $this->db->where('status', 'ACTIVE');
            $query = $this->db->get('product_layout');

            $data['layoutDetails'] = $query->result();
        } else {
            $data['layoutDetails'] = [];
        }
        

        $data['title']='Edit Product Type Layout Process';

        $this->load->view('master/process/add_mappingProcess',$data);
    }else{

        redirect(base_url(),'refresh');
    }

}

public function fetchProcess()
{
    $group = $this->input->post('group');
    $subgroup = $this->input->post('subgroup');

    $sql ="select * from process where is_deleted='N' and status='ACTIVE' and group_id='$group' ";

    if($subgroup!='')
    {
        $sql .=" and subgroup_id='$subgroup'";
    }
    $process=$this->db->query($sql)->result();

    $process_array='';

    foreach($process as $value)
    {
        $process_array.= '<option value="'.$value->id.'">'.$value->process_name .'</option>';
    }

    $data=array(

        'process'=>$process_array,
        'csrfHash' => $this->security->get_csrf_hash(),
      

    );

   echo  json_encode($data);
}


public function save_processMapping(){

    if($this->session->userdata('user_login_access')!=false)
    {

        $product_id=$this->input->post('id');
        $process_group=$this->input->post('process_group');
        $process_subgroup=$this->input->post('process_subgroup');
        $process=$this->input->post('process');
        $product_layout=$this->input->post('product_layout');
       $is_default = ($this->input->post('is_default') == 'Y') ? 'Y' : 'N';
       $formula=$this->input->post('formula');

        $sequence=$this->input->post('sequence');
      

        $data=array(

            'product_type_id'=>$product_id,
            'process_group_id'=>$process_group,
            'process_subgroup_id'=>$process_subgroup,
            'process_id'=>$process,
            'product_layout_id'=>$product_layout,
            'is_default'=>$is_default,
            'formula_id'=>$formula,
            'sequence'=>$sequence,
            'created_at'=>date('Y-m-d H:i:s'),
            'created_by'=>$this->session->userdata('user_login_id')

        );


        $check=$this->db->query("select count(*) as count from mapping_processLayout where product_layout_id='".$product_layout."' and process_id='".$process."' and product_type_id='".$product_id."' and is_deleted='N'")->row()->count;

        if($check > 0)
        {

            $this->session->set_flashdata('error',"This process already exist for the layout");
            redirect('Master/editprocessMapping/'.base64_encode($product_id));

        }
        else{

            if($is_default=='Y'){
                $checkdefault=$this->db->query("select count(*) as count from mapping_processLayout where product_type_id='".$product_id."' and product_layout_id='".$product_layout."' and process_group_id='".$process_group."' and is_deleted='N' and is_default='Y'")->row()->count;

                    if($checkdefault > 0)
                    {
                         $this->session->set_flashdata('error',"This Group Alrady Set Default Value");
                        redirect('Master/editprocessMapping/'.base64_encode($product_id));
                     
                    }
                    else{

                        $insert=$this->db->insert('mapping_processLayout',$data);
                        $insert_id=$this->db->insert_id();
                    }
            }
           
            else{

                 $insert=$this->db->insert('mapping_processLayout',$data);
                  $insert_id=$this->db->insert_id();
            }
        


        if($insert)
        {
             $parameterId=$this->input->post('parameter_id');
           
        if($parameterId){
            $count=count($this->input->post('parameter_id'));

                if($count > 0)
                {
                    for($i=0;$i< $count ;$i++)
                    {
                        $paramId=$this->input->post('parameter_id')[$i];
                        $mapping_id=$insert_id;
                        $field_type=$this->input->post('fieldtype')[$i];
                        $options=$this->input->post('options')[$i];

                        $defaultvalue=$this->input->post('defaultValue')[$i];
                        $dropdown_type=$this->input->post('dropdown_type')[$i];

                        $parameterdata=array(

                            'mapping_id'=>$mapping_id,
                            'parameter_id'=>$paramId,
                            'field_type'=>$field_type,
                            'options'=>$options,
                            'default_value'=>$defaultvalue,
                            'dropdown_type'=>$dropdown_type

                        );

                        $this->db->insert('process_mapping_parameters',$parameterdata);

                    }
                }
            }
            $this->session->set_flashdata('success',"Successfully Inserted");
        }
     }

        redirect('Master/editprocessMapping/'.base64_encode($product_id));
    }
    else{
        redirect(base_url(),'refresh');
    }
}


public function save_editprocessMapping(){

    if($this->session->userdata('user_login_access')!=false)
    {

        $product_id=$this->input->post('id');
        $process_group=$this->input->post('process_group');
        $process_subgroup=$this->input->post('process_subgroup');
        $process=$this->input->post('process');
        $product_layout=$this->input->post('product_layout');
       $is_default = ($this->input->post('is_default') == 'Y') ? 'Y' : 'N';
        $formula=$this->input->post('formula');
        $sequence=$this->input->post('sequence');
      
      $edit_id=$this->input->post('edit_id');
      

        $data=array(

            'product_type_id'=>$product_id,
            'process_group_id'=>$process_group,
            'process_subgroup_id'=>$process_subgroup,
            'process_id'=>$process,
            'product_layout_id'=>$product_layout,
            'is_default'=>$is_default,
             'formula_id'=>$formula,
              'sequence'=>$sequence,
            'updated_at'=>date('Y-m-d H:i:s'),
            'updated_by'=>$this->session->userdata('user_login_id')

        );


        $check=$this->db->query("select count(*) as count from mapping_processLayout where product_layout_id='".$product_layout."' and process_id='".$process."' and product_type_id='".$product_id."' and is_deleted='N' and id!='".$edit_id."'")->row()->count;


        if($check > 0)
        {

            $this->session->set_flashdata('error',"This process already exist for the layout");
            redirect('Master/editprocessMapping/'.base64_encode($product_id));

        }
        else{

            if($is_default=='Y'){
                $checkdefault=$this->db->query("select count(*) as count from mapping_processLayout where product_type_id='".$product_id."' and product_layout_id='".$product_layout."' and process_group_id='".$process_group."' and is_deleted='N' and is_default='Y' and id!='".$edit_id."'")->row()->count;

                    if($checkdefault > 0)
                    {
                         $this->session->set_flashdata('error',"This Group Alrady Set Default Value");
                        redirect('Master/editprocessMapping/'.base64_encode($product_id));
                     
                    }
                    else{
                        $this->db->where('id',$edit_id);
                        $update=$this->db->update('mapping_processLayout',$data);
                    }
            }
           
            else{
                 $this->db->where('id',$edit_id);
                 $update=$this->db->update('mapping_processLayout',$data);
            }
        
      
 

        if($update)
        {

        $this->db->where('mapping_id',$edit_id);
        $this->db->delete('process_mapping_parameters');
        $parameterId=$this->input->post('parameter_id');
       // print_r($parameterId);
           
        if($parameterId){
            $count=count($this->input->post('parameter_id'));
        //print_r( $this->input->post('defaultValue'));
          
                if($count > 0)
                {
                    for($i=0;$i< $count ;$i++)
                    {
                        $paramId=$this->input->post('parameter_id')[$i];
                        $mapping_id=$edit_id;
                        $field_type=$this->input->post('fieldtype')[$i];
                        $options=$this->input->post('options')[$i];
                        //echo $options; die;
                        $defaultvalue=$this->input->post('defaultValue')[$i];
                       // print_r($defaultvalue); die;
                          $dropdown_type=$this->input->post('dropdown_type')[$i];

                         if (!$options) {
                            $options = null;
                        }

                        if (!$dropdown_type) {
                            $dropdown_type = null;
                        }

                        $parameterdata=array(

                            'mapping_id'=>$mapping_id,
                            'parameter_id'=>$paramId,
                            'field_type'=>$field_type,
                            'options'=>$options,
                            'default_value'=>$defaultvalue,
                             'dropdown_type'=>$dropdown_type

                        );

                       // print_r($parameterdata);

                        $this->db->insert('process_mapping_parameters',$parameterdata);

                    }
                }
            }
            $this->session->set_flashdata('success',"Successfully Updated");
        }
     }

        redirect('Master/editprocessMapping/'.base64_encode($product_id));
    }
    else{
        redirect(base_url(),'refresh');
    }
}
public function remove_processMapping($id,$produt_type_id)
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=base64_decode($id);
        $data=array('is_deleted'=>'Y');
        $this->db->where('id',$id);
        $update=$this->db->update('mapping_processLayout',$data);

        if($update)
        {
            $this->db->where('mapping_id',$id);
            $delete=$this->db->delete('process_mapping_parameters');
            $this->session->set_flashdata('success',"Successfully Deleted");
        }
        else{
            $this->session->set_flashdata('error',"Not Deleted");
        }
        redirect('Master/editprocessMapping/'.$produt_type_id);
    }
    else{
    
       redirect(base_url(),'refresh');
    }

}

    public function getAssignProcessDetails()
    {
        if($this->session->userdata('user_login_access')!=false)
        {
            $product_type = $this->input->post('product_type');
           
            $id=$this->db->query("select id from products where product_name='$product_type'")->row()->id;

        $layouts=$this->db->query("select distinct(a.product_layout_id) ,b.layoutName from mapping_processLayout as a,product_layout as b where a.product_layout_id=b.id and a.product_type_id='$id' group by a.product_layout_id,b.layoutName ")->row();


           $process_mapping=$this->db->query("
                            SELECT
                            Distinct(pl.layoutName )AS layout_name,
                                pt.product_name AS product_type,
                                pg.name AS process_group,
                                p.process_name AS process_name,
                                ps.subgroup_name As subgroup_name,
                                pp.product_layout_id,pp.process_group_id,pp.process_subgroup_id,pp.process_id 
                            FROM
                                mapping_processLayout pp
                            JOIN products pt ON pp.product_type_id = pt.id
                            JOIN product_layout pl ON pp.product_layout_id = pl.id
                            JOIN process_group pg ON pp.process_group_id = pg.id
                            JOIN process p ON pp.process_id = p.id
                            LEFT JOIN process_subgroup ps ON pp.process_subgroup_id=ps.id
                            WHERE
                                pp.is_deleted = 'N' and pp.product_type_id='$id'
                            ORDER BY
                                pt.product_name
                                
                            ")->result();

                    echo json_encode([
                        'csrfHash' => $this->security->get_csrf_hash(),
                        'process_mapping' => $process_mapping,
                        'product_id'=>$id
                ]);

        }
        else{
            redirect(base_url(),'refresh');
        }
    }



public function showAssignProcessForm($doc_entry=null, $line_num = null,$id=null){
    if ($this->session->userdata('user_login_access') != false) {

     
        $data['sales_itemdata']=$this->sales_model->getSalesitems($doc_entry,$line_num);
        
      


        if($id)
        {
            $data['editdata']=$this->sales_model->getAssignprocess($id);
            $data['editprocessheader']=$this->sales_model->getSalesProcessheader($id);
          
        }
        else{
              $data['routbycode']=$this->db->query("select * from rout_header where rout_code='".$data['sales_itemdata']->RoutingProcessDHT."'")->row();

        }

       

        $product_type=$data['sales_itemdata']->u_product_type;
       

    
        $product_id=$this->db->query("select id from products where product_name='$product_type'")->row()->id;

        if (!$product_id) {
                 
          $this->session->set_flashdata('error',"Product Not Found");
          redirect('Transaction/getitems/'.base64_encode($doc_entry));
         }

        $data['product_id'] = $product_id;
        $data['doc_entry']=$doc_entry;
  
                     $data['process_mapping']=$this->db->query("
                            SELECT DISTINCT
                        pl.layoutName AS layout_name,pl.sequence,
                        pt.product_name AS product_type,
                        pg.name AS process_group,pg.sequence,
                        p.process_name AS process_name,
                        p.process_code,ps.orders,
                        ps.subgroup_name AS subgroup_name,
                        ps.status AS subgroup_status,
                        ps.is_deleted AS subgroup_deleted,
                        pp.product_layout_id,pp.is_default,
                        pp.process_group_id,
                        pp.process_subgroup_id,
                        pp.process_id,
                        pp.formula_id,pp.sequence,
                        pp.id as processmappingLayoutId
                    FROM mapping_processLayout pp
                    JOIN products pt 
                        ON pp.product_type_id = pt.id
                        AND pt.is_deleted = 'N' 
                        AND pt.status = 'ACTIVE'
                    JOIN product_layout pl 
                        ON pp.product_layout_id = pl.id
                        AND pl.is_deleted = 'N' 
                        AND pl.status = 'ACTIVE'
                    JOIN process_group pg 
                        ON pp.process_group_id = pg.id
                        AND pg.is_deleted = 'N' 
                        AND pg.status = 'ACTIVE'
                    JOIN process p 
                        ON pp.process_id = p.id
                         AND p.is_deleted = 'N' 
                        AND p.status = 'ACTIVE'
                    LEFT JOIN process_subgroup ps 
                        ON pp.process_subgroup_id = ps.id
                    WHERE pp.is_deleted = 'N' 
                        AND pp.product_type_id = '$product_id' 
                        AND (ps.is_deleted = 'N' AND ps.status = 'ACTIVE' OR ps.id IS NULL) 
                    ORDER BY pt.product_name,pl.sequence,pg.sequence,ps.orders, pp.sequence")->result();


        $data['routingheders']=$this->master_model->getRoutingHeaderData('',$product_id);
        $this->load->view('transaction/assignProcess1', $data); // Replace with your actual view
    } else {
        redirect(base_url(), 'refresh');
    }
}

/////////////////////////////// Parameter Master ///////////////////////////////////////
   
    public function parameterMaster()
    {
        if($this->session->userdata('user_login_access')!=false)
        {
             $data['urldata'] = $this->Access_model->get_menu_data();
             $access=$this->Access_model->getPermissionByAction();
             if($access!='success')
             {
                $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
                $data['title'] = 'Something went wrong';
                $this->load->view('errorPage', $data);
                return;
            }

            $data['title']="Parameters";
            $data['parameters']=$this->master_model->getParameter();
            $this->load->view('master/parameterMaster',$data);
        }
        else{

            redirect(base_url(),'refresh');
        }
    }


    public function saveAddParameter()
    {
        $code=$this->input->post('code');
        $name=$this->input->post('name');
        $field_type=$this->input->post('field_type');
        $dropdown_value=$this->input->post('dropdownvalue');
        // $dropdown_options=$this->input->post('dropdownOptions'));
        // $dropdown_option=implode(',',$dropdown_options);
        $status=$this->input->post('status');


        $dropdown_option = '';
        if ($field_type === 'dropdown' && !empty($dropdown_value)) {
            $dropdown_options = $this->input->post('dropdownOptions');
            if (!empty($dropdown_options) && is_array($dropdown_options)) {
                $dropdown_option = implode(',', array_map('trim', $dropdown_options));
            }
        }

        $data=array(

                'code'=>$code,
                'parameter_name'=>$name,
                'field_type'=>$field_type,
                'dropdown_value'=>$dropdown_value,
               'dropdown_type'=>$this->input->post('dropdown_type'),
                'options'=>$dropdown_option,
                'status'=>$status,
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$this->session->userdata('user_login_id')
        );

//print_r($data); die;
        $sql="select count(*) as count from parameters where (code='$code' OR parameter_name = '$name') and is_deleted='N' ";

        $checker=$this->db->query($sql)->row()->count;
        if($checker > 0)
        {
           $this->session->set_flashdata('error', 'This Code or Name Alrady Exist');
        }
        else{

          
           $insert =$this->db->insert('parameters',$data);

           if($insert)
           {
              $this->session->set_flashdata('success', 'Successfully Created');
           }
           else{

            $this->session->set_flashdata('error',"Somthing Wrong");
           }
        }

        redirect('Master/parameterMaster');
      //  print_r($dropdown_options);
    }


    public function getParameterById()
    {

        $id=$this->input->post('id');
         $parameter = $this->master_model->getParameter($id);

        $data=array(

            'parameter'=>$parameter,
            'csrfHash' => $this->security->get_csrf_hash(),
          
        );

        echo  json_encode($data);
    }


    public function remove_parameter($id)
    {

        if($this->session->userdata('user_login_access')!=false)
        {
            $id=base64_decode($id);
           
            $data=array('is_deleted'=>'Y');
            $this->db->where('id',$id);
            $update=$this->db->update('parameters',$data);

            if($update)
            {
                $this->session->set_flashdata('success',"Successfully Deleted");
            }
            else{

                $this->session->set_flashdata('error',"Not Deleted");
            }
            redirect('Master/parameterMaster');
        }
        else{
        
           redirect(base_url(),'refresh');
        }

    }


      public function saveEditParameter()
    {
        $code=$this->input->post('code');
        $name=$this->input->post('name');
        $field_type=$this->input->post('field_type');
        $dropdown_value=$this->input->post('dropdownvalue');
        $id=$this->input->post('id');
        $status=$this->input->post('status');

        $dropdown_option = '';
        if ($field_type === 'dropdown' && !empty($dropdown_value)) {
            $dropdown_options = $this->input->post('dropdownOptions');
            if (!empty($dropdown_options) && is_array($dropdown_options)) {
                $dropdown_option = implode(',', array_map('trim', $dropdown_options));
            }
        }


        $data=array(

                'code'=>$code,
                'parameter_name'=>$name,
                'field_type'=>$field_type,
                'dropdown_value'=>$dropdown_value,
                'dropdown_type'=>$this->input->post('dropdown_type'),
                'options'=>$dropdown_option,
                'status'=>$status,
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$this->session->userdata('user_login_id')
        );


        $sql="select count(*) as count from parameters where (code='$code' OR parameter_name = '$name') and is_deleted='N' and id!='$id' ";

        $checker=$this->db->query($sql)->row()->count;

        if($checker > 0)
        {
           $this->session->set_flashdata('error', 'This Code or Name Alrady Exist');
        }
        else{

                    $this->db->where('id',$id);
           $update =$this->db->update('parameters',$data);

           if($update)
           {
              $this->session->set_flashdata('success', 'Successfully Updated');
           }
           else{
            $this->session->set_flashdata('error',"Somthing Wrong");
           }
        }

        redirect('Master/parameterMaster');
      //  print_r($dropdown_options);
    }


    public function fetchParametersByProcess()
    {
        $processid=$this->input->post('processid');


       $parametersdetails = $this->master_model->getParameterByProcessId($processid);


         $data=array(

            'parametersdetails'=>$parametersdetails,
            'csrfHash' => $this->security->get_csrf_hash(),
            // 'parameter_ids'=>$parametersdetails->parameter_ids
          
        );

        echo  json_encode($data);

    }


    public function fetchparametersBymapping()
    {
        $processid=$this->input->post('processid');
        $mapping_id=$this->input->post('processmappingLayoutId');
        
      $sql="select a.*,b.parameter_name,c.process_id,c.formula_id,d.process_name from process_mapping_parameters as a,parameters as b ,mapping_processLayout as c,process as d where a.mapping_id=c.id and c.process_id=d.id and a.parameter_id=b.id and a.mapping_id='".$mapping_id."'";

        $parametersdetails=$this->db->query($sql)->result();


         $data=array(

            'parametersdetails'=>$parametersdetails,
            'csrfHash' => $this->security->get_csrf_hash(),
           
        );

        echo  json_encode($data);
 
    }


//////////////////////////////////// Formula master //////////////////////////////

    public function formulaMaster()
    {
        if($this->session->userdata('user_login_access')!=false)
        {
             $data['urldata'] = $this->Access_model->get_menu_data();
             $access=$this->Access_model->getPermissionByAction();
             if($access!='success')
             {
                $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
                $data['title'] = 'Something went wrong';
                $this->load->view('errorPage', $data);
                return;
            }
            $data['title']=$data['urldata']->menu_name;
            $data['parameters']=$this->master_model->getParameter('','ACTIVE');
            $data['formulas']=$this->master_model->getFormula('','ACTIVE');
            $this->load->view('master/formula',$data);
        }
        else{

            redirect(base_url(),'refresh');
        }
    }


public function save_Formula()
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=$this->input->post('id');
        $code=$this->input->post('code');
        $firstoprand=$this->input->post('firstoprand');
        $operator = $this->input->post('operator');
        $parameter = $this->input->post('parameter');
        $secondoprand = $this->input->post('secondoprand');
        $is_number = ($this->input->post('is_number')=='Y')?'Y':'N';
        $status = $this->input->post('status');
        $formulashow = $this->input->post('formulashow');

       
     $sql="select count(*) as count from m_formula where is_deleted='N' and code='".$code."'";

        if($id!='')
        {
            $sql .=" and id!='$id'";
        }
         $check=$this->db->query($sql)->row()->count;
        if($check > 0)
        {
            $this->session->set_flashdata('error', 'This Code  Alrady Exist');
        }
        else{

            $data=array(

                'code'=>$code,
                'operator'=>$operator,
                'first_operand'=>$firstoprand,
                'parameter'=>$parameter,
                'number'=>$secondoprand,
                'is_number'=>$is_number,
                'status'=>$status,
                'formula'=>$formulashow

            );
                if($id!='')
                {

                      $data1=array(
                        'updated_at'=>date('Y-m-d H:i:s'),
                        'updated_by'=>$this->session->userdata('user_login_id')

                        );
                    $data=array_merge($data,$data1);
                    //print_r($data); die;
                    $this->db->where('id',$id);
                    $insert=$this->db->update('m_formula',$data);
                }
                else{

                     $data1=array(
                        'created_at'=>date('Y-m-d H:i:s'),
                        'created_by'=>$this->session->userdata('user_login_id')

                        );
                      $data=array_merge($data,$data1);
                   $insert=$this->db->insert('m_formula',$data);
               }

         // $insert = $this->db->insert('m_formula',$data);
          if($insert)
          {
            $this->session->set_flashdata('success',"Successfully Inseted");
          }

        }
        redirect('Master/formulaMaster');
    }
    else{
        redirect(base_url(),'refresh');
    }
}

public function editFormulaMaster($id)
{
     $id=base64_decode($id);
    $data['title'] = 'Formula';
    $data['editdata']=$this->master_model->getFormula($id,'ACTIVE');
    $data['parameters']=$this->master_model->getParameter('','ACTIVE');
    $data['formulas']=$this->master_model->getFormula('','ACTIVE');
    $this->load->view('master/formula',$data);
}
public function removeformula($id)
{
    if($this->session->userdata('user_login_access')!=false)
        {
            $id=base64_decode($id);
           
            $data=array('is_deleted'=>'Y');
            $this->db->where('id',$id);
            $update=$this->db->update('m_formula',$data);

            if($update)
            {
                $this->session->set_flashdata('success',"Successfully Deleted");
            }
            else{

                $this->session->set_flashdata('error',"Not Deleted");
            }
            redirect('Master/formulaMaster');
        }
        else{
        
           redirect(base_url(),'refresh');
        }


}

public function fetchFormulaBymappingId()
{
    $processmappingLayoutId=$this->input->post('processmappingLayoutId');
    $sql="select a.* from m_formula as a,mapping_processLayout as b where a.id=b.formula_id and b.id='$processmappingLayoutId'";
        $formula=$this->db->query($sql)->row();
        echo json_encode($formula);
}

//////////////////////////////////////// End //////////////////////////////////////

# Machine Master

  public function machines()
    { 
        if($this->session->userdata('user_login_access')!=false)
        {
             $data['urldata'] = $this->Access_model->get_menu_data();
             $access=$this->Access_model->getPermissionByAction();
             if($access!='success')
             {
                $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
                $data['title'] = 'Something went wrong';

                $this->load->view('errorPage', $data);
                return;
            }

            $data['title']=$data['urldata']->menu_name;
            $data['operators']=$this->employee_model->getuserByuserType('Operator');
             $data['helpers']=$this->employee_model->getuserByuserType('Helper');
            $data['machines']=$this->master_model->getmachines();
         
            $this->load->view('master/machine/machines',$data);
        }
        else{

            redirect(base_url(),'refresh');
        }
    }
public function addMachine()
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $data['operators']=$this->employee_model->getuserByuserType('Operator');
         $data['gauges']=$this->master_model->getgauges();
       $data['helpers']=$this->employee_model->getuserByuserType('Helper');
        $this->load->view('master/machine/createmachine',$data);
    }
    else{
        redirect(base_url(),'refresh');
    }
}



public function edit_machine($id)
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=base64_decode($id);

        $data['editdata']=$this->master_model->getmachines($id);
       $data['gauges']=$this->master_model->getgauges();
         $data['operators']=$this->employee_model->getuserByuserType('Operator');
          $data['machines']=$this->master_model->getmachines();
         $data['title']="Machines";
         $data['helpers']=$this->employee_model->getuserByuserType('Helper');
        $this->load->view('master/machine/createmachine',$data);

    }
    else{
        redirect(base_url(),'refresh');
    }
}

public function save_Machine()
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $id=$this->input->post('id');
        $code=$this->input->post('machine_code');
        $name=$this->input->post('machine_name');
        $printing_machine=$this->input->post('printing_machine');
        $description=$this->input->post('description');
        $main_operators=$this->input->post('operators');
        $helpers=$this->input->post('helpers');
        $shift_pattern=$this->input->post('shift_pattern');
        $shift_time=$this->input->post('shift_time');
        $setup_time=$this->input->post('setup_time');
        $changeover_time=$this->input->post('changeover_time');
        $jobsetting_time=$this->input->post('jobsetting_time');
        $availablity=$this->input->post('availablity');
        $max_operational_hour=$this->input->post('max_operational_hour');
        $job_type=$this->input->post('job_type');
        $min_width          = $this->input->post('min_width');
        $max_width          = $this->input->post('max_width');
        $min_height         = $this->input->post('min_height');
        $max_height         = $this->input->post('max_height');
        $changeover_freq   = $this->input->post('changeover_frequency');
        $production_capacity= $this->input->post('production_capacity');
        $guage              = $this->input->post('guage');

        $main_operator=($main_operators) ? implode(",", $main_operators) :'';
        $helper=($helpers)?implode(",", $helpers):'';
         if(!empty($guage))
            {
                $guageArray=implode(",",$guage);
            }

 

        $data=array(

                'code'=>$code,
                'machine_name'=>$name,
                'description'=>$description,
                'main_operator'=>$main_operator,
                'helper'=>$helper,
                'shift_pattern'=>$shift_pattern,
                'printing_machine'=>$printing_machine,
                'shift_time'=>$shift_time,
                'setup_time'=>$setup_time,
                'changeover_time'=>$changeover_time,
                'jobsetting_time'=>$jobsetting_time,
                'availablity'=>$availablity,
                'max_operational_hour'=>$max_operational_hour,
                'job_type'=>$job_type,
                'min_width' =>$min_width,
                'max_width' =>$max_width,
                'min_length'=>$min_height,
                'max_length'=>$max_height,
                'changeover_frequency'=> $changeover_freq,
                'production_capacity' => $production_capacity,
                'gauge'=>$guageArray

        );

        if($id && $id!=''){

             $sql="select count(*) as count from machines where code='$code' and id!=$id and is_deleted='N'";
        }
        else{

        $sql="select count(*) as count from machines where code='$code'  and is_deleted='N'";
        }
        
         $checker=$this->db->query($sql)->row()->count;

         if($checker > 0)
         {
            $this->session->set_flashdata('error',"This Code Alrady Exists");
            redirect('Master/machines');
         }
         else{

                if($id!='')
                {
                    $data1=array(

                        'updated_at'=>date('Y-m-d H:i:s'),
                        'updated_by'=>$this->session->userdata('user_login_id')

                    );
                    $data=array_merge($data,$data1);

                       $this->db->where('id',$id);
                    $update = $this->db->update('machines',$data);
                }
                else{

                      $data1=array(

                        'created_at'=>date('Y-m-d H:i:s'),
                        'created_by'=>$this->session->userdata('user_login_id')

                    );
                    $data=array_merge($data,$data1);

                     
                    $update = $this->db->insert('machines',$data);
                }
             

              if($update)
              {
                $this->session->set_flashdata('success',"Successfully Inserted");
              }
              else{
                $this->session->set_flashdata('error',"Something Wrong");
              }
         }

     

      redirect('Master/machines');
    }
    else{
        redirect(base_url(),'refresh');
    }
}

public function remove_machine($id)
{
    if($this->session->userdata('user_login_access')!=false)
    {

          $id=base64_decode($id);
           
            $data=array('is_deleted'=>'Y');
            $this->db->where('id',$id);
            $update=$this->db->update('machines',$data);

            if($update)
            {
                $this->session->set_flashdata('success',"Successfully Deleted");
            }
            else{

                $this->session->set_flashdata('error',"Not Deleted");
            }
        redirect('Master/machines');
    }
    else{
        redirect(base_url(),'refresh');
    }
}


public function mapping_machine()
{
    if($this->session->userdata('user_login_access')!=false)
    {
         $data['urldata'] = $this->Access_model->get_menu_data();
             $access=$this->Access_model->getPermissionByAction();
             if($access!='success')
             {
                $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
                $data['title'] = 'Something went wrong';
                $this->load->view('errorPage', $data);
                return;
            }


        $data['title'] = $data['urldata']->menu_name;
        $data['product_type']=$this->master_model->getProductData(null,'ACTIVE');
        $this->load->view('master/machine/machineProductMapp',$data);
    }
    else{

        redirect(base_url(),'refresh');
    }

}


public function mappingMachine($id)
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $product_type=base64_decode($id);

        $data['machines']=$this->master_model->getmachines(null,'ACTIVE');
        $data['product_type_id']=$product_type;
         $data['productdata']=$this->master_model->getProductData($product_type);
       //  $data['products']=$this->master_model->getProductsBytype($data['productdata']->product_name);
        $data['groupByProductType']=$this->master_model->getGroupByProduct($product_type);
        $data['mappingMachineData']=$this->master_model->getMachineMappingList($product_type);
   
        $data['gauges']=$this->master_model->getgauges();

        $this->load->view('master/machine/add_machineMapping',$data);
    }
    else{

        redirect(base_url(),'refresh');
    }
}

public function processByProductType()
{
    $group = $this->input->post('group');
    $product_type_id = $this->input->post('product_type_id'); // corrected spelling

    // Fetch processes using correct variable name
    $process = $this->master_model->getProcessByproduct_type($product_type_id, $group);


    $process_array = '';

    if (!empty($process)) {
        foreach ($process as $value) {
            $process_array .= '<option value="'.$value->process_id.'">'.$value->process_name.'</option>';
        }
    } else {
        $process_array .= '<option value="">No process found</option>';
    }

    $data = array(
        'process'   => $process_array,
        'csrfHash'  => $this->security->get_csrf_hash()
    );

    echo json_encode($data);
   
}



public function fetchProductForMapping()
{
    $machine_id = $this->input->post('machine_id');
    $process_id = $this->input->post('process_id');
    $product_type_id = $this->input->post('product_type_id');
    $selectedProducts = $this->input->post('selectedProducts') ?? [];

    $draw = intval($this->input->post("draw"));
    $start = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));
    $search = $this->input->post("search")['value'] ?? '';

    // Base SQL
    $sql = "
        SELECT 
            a.item_code,
            a.item_name,
            CASE 
                WHEN b.item_code IS NOT NULL 
                     AND (b.machine_id != '".$machine_id."' 
                          OR b.process_id != '".$process_id."' 
                          OR b.product_type_id != '".$product_type_id."')
                THEN 1
                ELSE 0
            END AS assignable
        FROM item_master a
        LEFT JOIN productMachine_mapping b 
            ON a.item_code = b.item_code 
            AND b.product_type_id = '".$product_type_id."'
        WHERE a.deleted = 'N'
          AND a.u_product_type = (SELECT product_name FROM products WHERE id = '".$product_type_id."') ";

    // CREATE mode: only show unassigned products (except ones already selected in edit mode)
    if (empty($selectedProducts)) {
        $sql .= " AND b.item_code IS NULL";
    }

    // Search filter
    if (!empty($search)) {
        $sql .= " AND (a.item_code LIKE '%".$search."%' 
                       OR a.item_name LIKE '%".$search."%')";
    }

    // Count total records without filtering
    $count_total_sql = "
        SELECT COUNT(*) as total 
        FROM item_master a
        WHERE a.deleted = 'N'
          AND a.u_product_type = (SELECT product_name FROM products WHERE id = '".$product_type_id."')
    ";
    $recordsTotal = $this->db->query($count_total_sql)->row()->total;

    // Count filtered records
    $count_sql = "SELECT COUNT(*) as total FROM (" . $sql . ") as temp";
    $recordsFiltered = $this->db->query($count_sql)->row()->total;

    
    $sql .= " ORDER BY a.item_code OFFSET ".$start." ROWS FETCH NEXT ".$length." ROWS ONLY";
    $query = $this->db->query($sql);
    $result = $query->result();

    
    $data = [];
    foreach ($result as $row) {
        $checked = in_array($row->item_code, $selectedProducts) ? 'checked' : '';
        $disabled = ($row->assignable && !$checked) ? 'disabled' : '';

        $checkbox = "<input type='checkbox' name='item[]' class='form-check item' value='".$row->item_code."' $checked $disabled>";

        $data[] = [
            $checkbox,
            $row->item_code,
            $row->item_name
        ];
    }

    // Send JSON response
    $output = [
        "draw" => $draw,
        "recordsTotal" => intval($recordsTotal),
        "recordsFiltered" => intval($recordsFiltered),
        "data" => $data
    ];

    echo json_encode($output);
}

/*public function fetchProductForMapping()
{
    $machine_id       = $this->input->post('machine_id');
    $process_id       = $this->input->post('process_id');
    $product_type_id  = $this->input->post('product_type_id');
    $selectedProducts = $this->input->post('selectedProducts') ?? [];
//print_r ($selectedProducts); die;
    $search           = $this->input->post("search") ?? '';

    // Base SQL
    $sql = "
        SELECT 
            a.item_code,
            a.item_name,
            CASE 
                WHEN b.item_code IS NOT NULL 
                     AND (b.machine_id != '".$machine_id."' 
                          OR b.process_id != '".$process_id."' 
                          OR b.product_type_id != '".$product_type_id."')
                THEN 1
                ELSE 0
            END AS assignable
        FROM item_master a
        LEFT JOIN productMachine_mapping b 
            ON a.item_code = b.item_code 
            AND b.product_type_id = '".$product_type_id."'
        WHERE a.deleted = 'N'
          AND a.u_product_type = (SELECT product_name FROM products WHERE id = '".$product_type_id."') 
    ";

    // CREATE mode: only show unassigned products (except ones already selected in edit mode)
    if (empty($selectedProducts)) {
        $sql .= " AND b.item_code IS NULL";
    }

    // Search filter
    if (!empty($search)) {
        $sql .= " AND (a.item_code LIKE '%".$search."%' 
                       OR a.item_name LIKE '%".$search."%')";
    }

    $sql .= " ORDER BY a.item_code";
    $query  = $this->db->query($sql);
    $result = $query->result();

    // Build HTML rows directly
    $output = '';
    foreach ($result as $row) {
        $checked  = in_array($row->item_code, $selectedProducts) ? 'checked' : '';
        $disabled = ($row->assignable && !$checked) ? 'disabled' : '';

        $checkbox = "<input type='checkbox' name='item[]' class='form-check item' value='".$row->item_code."' $checked $disabled>";

        $output .= "<tr>
                        <td>".$checkbox."</td>
                        <td>".$row->item_code."</td>
                        <td>".$row->item_name."</td>
                    </tr>";
    }

    echo $output; 
}
*/




public function save_addMachineMapping()
{
    $product_type_id    = $this->input->post('product_type_id');
    $machine            = $this->input->post('machine');
    $process_group      = $this->input->post('process_group');
    $process            = $this->input->post('process');
    $hourly_impression  = $this->input->post('hourly_impression');
    $changeover_time    = $this->input->post('changeover_frequency');
    $production_capacity= $this->input->post('production_capacity');
    $min_qty            = $this->input->post('min_qty');
    $max_qty            = $this->input->post('max_qty');
    $min_width          = $this->input->post('min_width');
    $max_width          = $this->input->post('max_width');
    $min_height         = $this->input->post('min_height');
    $max_height         = $this->input->post('max_height');
    $size               = $this->input->post('size');
    $guage              = $this->input->post('guage');
    $status             = $this->input->post('status');
    $products           = $this->input->post('products');
    $productArray = explode(',', $products);
    $machineArray = implode(',',$machine);
      $processArray = implode(',',$process);
    $guageArray='';
     if(!empty($guage))
            {
                $guageArray=implode(",",$guage);
            }


 
    $mainData = array(
        'product_type_id'     => $product_type_id,
        'machine_id'          => $machineArray,
        'process_group_id'    => $process_group,
        'process_id'          => $processArray,
        'hourly_impression'   => $hourly_impression,
        'changeover_frequency'=> $changeover_time,
        'production_capacity' => $production_capacity,
        'min_qty'             => $min_qty,
        'max_qty'             => $max_qty,
        'min_width'           =>$min_width,
        'max_width'           =>$max_width,
        'min_height'          =>$min_height,
        'max_height'          =>$max_height,
        'size'                => $size,
        'guage'               => $guageArray,
       // 'status'              => $status,
        'created_at'          => date('Y-m-d H:i:s'),
        'created_by'          => $this->session->userdata('user_login_id')
    );



   // $checker=$this->db->query("SELECT count(*) as count FROM mapping_productTypeMachine AS a CROSS APPLY STRING_SPLIT(a.machine_id, ',') AS m WHERE a.is_deleted = 'N'AND a.product_type_id = '".$product_type_id."'AND a.process_id = '".$process."'AND m.value IN (".$machineArray.")")->row()->count;

$sql = "SELECT COUNT(*) AS count FROM mapping_productTypeMachine AS a CROSS APPLY STRING_SPLIT(a.machine_id, ',') AS m CROSS APPLY STRING_SPLIT(a.process_id, ',') AS p WHERE a.is_deleted = 'N'AND a.product_type_id = '".$product_type_id."'AND m.value IN (".$machineArray.") AND p.value IN (".$processArray.") ";


$checker = $this->db->query($sql)->row()->count;

    if($checker > 0)
    {
        $this->session->set_flashdata('error',"Alrady Exists");
        redirect('Master/mappingMachine/'.base64_encode($product_type_id));
    }
    else{


      $insert=  $this->db->insert('mapping_productTypeMachine',$mainData);
        $mapping_id=$this->db->insert_id();

        /* if (!empty($productArray) && $mapping_id) {
            foreach ($productArray as $product_id) {
                $data=array(
                    'productTypeMachine_id'  => $mapping_id,
                    'item_code'  => $product_id,
                    'process_id'=>$process,
                    'product_type_id'     => $product_type_id,
                     'machine_id'          => $machine
                    
                );

                $this->db->insert('productMachine_mapping',$data);

            }
        }*/
        if($insert){
             $this->session->set_flashdata("success","Successfully Inserted");
        }
 
    }

        redirect('Master/mappingMachine/'.base64_encode($product_type_id));
 }

 ////////////////////////////////////////////////////////////

public function editmappingMachine($product_type_id,$id)
{
    if($this->session->userdata('user_login_access')!=false)
    {
        $product_type=base64_decode($product_type_id);
        $id=base64_decode($id);

        $data['machines']=$this->master_model->getmachines(null,'ACTIVE');
        $data['product_type_id']=$product_type;
         $data['productdata']=$this->master_model->getProductData($product_type);
     //    $data['products']=$this->master_model->getProductsBytype($data['productdata']->product_name);
        $data['groupByProductType']=$this->master_model->getGroupByProduct($product_type);
        $data['mappingMachineData']=$this->master_model->getMachineMappingList($product_type);
 
        $data['editdata']=$this->master_model->getMachineMappingList($product_type,$id);

      //  $data['editproductmapping']=$this->master_model->getMappingProductsMachine($id);
         $data['gauges']=$this->master_model->getgauges();

        $this->load->view('master/machine/add_machineMapping',$data);
    }
    else{

        redirect(base_url(),'refresh');
    }
}


    public function save_editMachineMapping()
    {
        if($this->session->userdata('user_login_access')!=false)
        {

            $id                  =$this->input->post('id');
            $product_type_id    = $this->input->post('product_type_id');
            $machine            = $this->input->post('machine');
            $process_group      = $this->input->post('process_group');
            $process            = $this->input->post('process');
            $hourly_impression  = $this->input->post('hourly_impression');
            $changeover_time    = $this->input->post('changeover_frequency');
            $production_capacity= $this->input->post('production_capacity');
            $min_qty            = $this->input->post('min_qty');
            $max_qty            = $this->input->post('max_qty');
            $size               = $this->input->post('size');
            $guage              = $this->input->post('guage');
            $status             = $this->input->post('status');
            $min_width          = $this->input->post('min_width');
            $max_width          = $this->input->post('max_width');
            $min_height         = $this->input->post('min_height');
            $max_height         = $this->input->post('max_height');
            $products           = $this->input->post('products');
            $productArray       = explode(',', $products);
             $machineArray = implode(',',$machine);
               $processArray = implode(',',$process);
            if(!empty($guage))
            {
                $guageArray=implode(",",$guage);
            }


            // print_r($productArray); die;
         
            $mainData = array(
                'product_type_id'     => $product_type_id,
                'machine_id'          => $machineArray,
                'process_group_id'    => $process_group,
                'process_id'          => $processArray,
                'hourly_impression'   => $hourly_impression,
                'changeover_frequency'=> $changeover_time,
                'production_capacity' => $production_capacity,
                'min_qty'             => $min_qty,
                'max_qty'             => $max_qty,
                'min_width'           => $min_width,
                'max_width'           => $max_width,
                'min_height'          => $min_height,
                'max_height'          => $max_height,
                'size'                => $size,
                'guage'               => $guageArray,
               // 'status'              => $status,
                'updated_at'          => date('Y-m-d H:i:s'),
                'updated_by'          => $this->session->userdata('user_login_id')
            );


           // $checker=$this->db->query("select count(*) as count from mapping_productTypeMachine where is_deleted='N' and product_type_id='".$product_type_id."' and machine_id='".$machine."' and process_id='".$process."' and id!='".$id."'")->row()->count;

             // $checker=$this->db->query("SELECT count(*) as count
             //            FROM mapping_productTypeMachine AS a
             //            CROSS APPLY STRING_SPLIT(a.machine_id, ',') AS m
             //            WHERE a.is_deleted = 'N'
             //              AND a.product_type_id = '".$product_type_id."'
             //              AND a.process_id = '".$process."'
             //              AND m.value IN (".$machineArray.") and id!='".$id."'")->row()->count;

            $sql = "SELECT COUNT(*) AS count FROM mapping_productTypeMachine AS a CROSS APPLY STRING_SPLIT(a.machine_id, ',') AS m CROSS APPLY STRING_SPLIT(a.process_id, ',') AS p WHERE a.is_deleted = 'N'AND a.product_type_id = '".$product_type_id."'AND m.value IN (".$machineArray.") AND p.value IN (".$processArray.") and id!='".$id."'";


            $checker = $this->db->query($sql)->row()->count;

            if($checker > 0)
            {
                $this->session->set_flashdata('error',"Alrady Exists");
                redirect('Master/mappingMachine/'.base64_encode($product_type_id));
            }
            else{

                $this->db->where('id',$id);
              $update = $this->db->update('mapping_productTypeMachine',$mainData);
                $mapping_id=$id;

                if($update)
                {
                    $this->db->where('productTypeMachine_id',$id);
                    $this->db->delete('productMachine_mapping');
                    $this->session->set_flashdata("success","Successfully Updated");
                }

               // print_r($products); die;
               /*  if (!empty($productArray) && $mapping_id) {
                    foreach ($productArray as $product_id) {
                        $data=array(
                            'productTypeMachine_id'  => $mapping_id,
                            'item_code'  => $product_id,
                            'process_id'=>$process,
                            'product_type_id'     => $product_type_id,
                             'machine_id'          => $machine
                            
                        );

                        $this->db->insert('productMachine_mapping',$data);

                    }
                }*/

            }

                redirect('Master/mappingMachine/'.base64_encode($product_type_id));
        }
        else{
              redirect(base_url(),'refresh');
         }
        
    }



    public function remove_machineMapping($id,$product_type_id)
    {
        if($this->session->userdata('user_login_access')!=false)
        {
             $id =base64_decode($id);
             //$product_type=base64_decode($product_type_id);

            $data=array('is_deleted'=>'Y');
            $this->db->where('id',$id);
              $update=$this->db->update('mapping_productTypeMachine',$data);

          if($update)
          {
            $this->db->where('productTypeMachine_id',$id);
            $this->db->delete('productMachine_mapping');

            $this->session->set_flashdata("success","Successfully Deleted");
          }

          redirect('Master/mappingMachine/'.$product_type_id);
        }
        else{
            redirect(base_url(),'refresh');
        }
       
    }


////////////////////////////// Routing Master //////////////////////

    public function routing_master()
    {
        if($this->session->userdata('user_login_access')!=false)
        {
            $data['urldata'] = $this->Access_model->get_menu_data();
          $access=$this->Access_model->getPermissionByAction();
          if($access!='success')
          {
            $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
            $data['title'] = 'Something went wrong';
            $this->load->view('errorPage', $data);
            return;
        }

        

        $data['title'] = $data['urldata']->menu_name;
        
            $data['routingheders']=$this->master_model->getRoutingHeaderData();

            $this->load->view('Master/routing_master',$data);
        }
        else{

            redirect(base_url(),'refresh');
        }
    }

    public function add_routingMaster()
    {
        if($this->session->userdata('user_login_access')!=false)
        {

            $data['groups']=$this->master_model->getProcessGroup();
             $data['product_types']=$this->master_model->getProductData('','ACTIVE');
            $this->load->view('Master/add_routingMaster',$data);
        }
        else{
            redirect(base_url(),'refresh');
        }
    }

    public function edit_routing($id,$assign=0)
    {
        if($this->session->userdata('user_login_access')!=false)
        {
            $id=base64_decode($id);
           // $data['groups']=$this->master_model->getProcessGroup();
            $data['editdata']=$this->master_model->getRoutingHeaderData($id);
            $data['editroutdetail']=$this->master_model->getRoutingDetail($id);

           $data['product_types']=$this->master_model->getProductData('','ACTIVE');
            $data['layouts']=$this->master_model->getProductData($data['editdata']->product_type,'ACTIVE');
            $data['groups']=$this->master_model->getGroupByProduct($data['editdata']->product_type);

               $data['assign'] = $assign;
         
            $this->load->view('Master/add_routingMaster',$data);
        }
        else{
            redirect(base_url(),'refresh');
        }
    }


    public function save_routing()
    {
        if($this->session->userdata('user_login_access')!=false)
        {
            $assign = $this->input->post('assign'); 
            $sequence=$this->input->post('sequence');
            $name     = $this->input->post('name');
            $description=$this->input->post('description');
            $groups      = $this->input->post('group');
            $sub_groups  = $this->input->post('sub_group');
            $processes   = $this->input->post('process');
            $layouts=$this->input->post('layouts');
            //$processTime = $this->input->post('process_time');
            $product_type=$this->input->post('product_type');

           /* $newSequence = [];
            foreach ($groups as $i => $groupId) {
                $newSequence[] = $groupId . '-' . $sub_groups[$i] . '-' . $processes[$i];
            }
            $newSeqString = implode('|', $newSequence);*/


            $newSequence = [];
             $rows = [];
            foreach ($groups as $i => $groupId) {
                $rows[] = [
                    'seq'       => $sequence[$i], 
                    'layout_id'  => $layouts[$i],     // sequence number
                    'group_id'  => $groupId,
                    'sub_group' => $sub_groups[$i],
                    'process'   => $processes[$i]
                ];
            }

            // Step 2: Sort the rows array by sequence ascending
            usort($rows, function($a, $b) {
                return $a['seq'] - $b['seq']; // ascending
            });

            // Step 3: Build the string after sorting
            foreach ($rows as $row) {
                $newSequence[] = $row['seq'] . '-' .$row['layout_id'] . '-' . $row['group_id'] . '-' . $row['sub_group'] . '-' . $row['process'];
            }
            $newSeqString = implode('|', $newSequence);


            $this->db->where('product_type',$product_type);
            $routHeaders = $this->db->get('rout_header')->result();

            foreach ($routHeaders as $rout) {
                $details = $this->db->where('rout_id', $rout->id)
                ->order_by('seq', 'ASC')
                ->get('rout_detail')
                ->result();

                $seqArr = [];
                foreach ($details as $d) {
                    $seqArr[] = $d->seq . '-' .$d->layout_id . '-' . $d->group_id . '-' . $d->sub_group_id . '-' . $d->process_id;
                }
                $existingSeqString = implode('|', $seqArr);

                if ($newSeqString === $existingSeqString) {
                    $this->session->set_flashdata('error', 'This routing already exists in Master: ' . $bom->name);
                    redirect('Master/routing_master');
                    return;
                }
            }

            $rout_code=$this->db->query("SELECT 'ROUT' +  RIGHT('000' + CAST(ISNULL(MAX(CAST(RIGHT(rout_code, 3) AS INT)) + 1, 1) AS VARCHAR(3)), 3) AS rout_code
               FROM rout_header ")->row()->rout_code;

            $headerData=array(
                        'rout_code'=>$rout_code,
                        'product_type'=>$product_type,
                        'name'=>$name,
                         'description'=>$description,
                        'created_by'=>$this->session->userdata('user_login_id'),
                        'created_at'=>date('Y-m-d H:i:s')
            );

            $checker=$this->db->query("select count(*) as count from rout_header where name='$name' and is_deleted='N'")->row()->count;

            if($checker > 0)
            {
                $this->session->set_flashdata('error', 'This routing  Name already exists in Master');
                redirect('Master/routing_master');
            }
            else{



            $this->db->insert('rout_header', $headerData);
            $bomId = $this->db->insert_id();


            foreach ($groups as $i => $groupId) {
                $data = [
                    'rout_id'        => $bomId,
                    'seq'           => $sequence[$i],
                    'layout_id'     =>$layouts[$i],
                    'group_id'      => $groupId,
                    'sub_group_id'  => $sub_groups[$i],
                    'process_id'    => $processes[$i],
                    //'process_time'  => $processTime[$i],
                ];
                $this->db->insert('rout_detail', $data);
            }
        }

            $this->session->set_flashdata('success', 'BOM saved successfully.');

            if($assign && $assign == 1) {
              
                echo "<script>
                        if(window.opener){
                            window.opener.location.reload(); // refresh parent
                        }
                        window.close(); // close popup
                      </script>";
                exit;
            } else {
                // Normal edit page → redirect normally
                redirect(base_url('Master/routing_master'));
            }
           // redirect('Master/routing_master');


        }
        else{
            redirect(base_url(),'refresh');
        }
    }

    public function save_editRouting()
    {
        if($this->session->userdata('user_login_access')!=false)
        {
            $id=$this->input->post('id');
            $rout_code=$this->input->post('rout_code');
            $sequence=$this->input->post('sequence');
            $name     = $this->input->post('name');
            $description=$this->input->post('description');
            $groups      = $this->input->post('group');
            $sub_groups  = $this->input->post('sub_group');
            $processes   = $this->input->post('process');
              $product_type=$this->input->post('product_type');
                $layouts=$this->input->post('layouts');
           // $processTime = $this->input->post('process_time');

           /* $newSequence = [];
            foreach ($groups as $i => $groupId) {
                $newSequence[] = $groupId . '-' . $sub_groups[$i] . '-' . $processes[$i];
            }
            $newSeqString = implode('|', $newSequence);
*/
            $newSequence = [];
           $rows = [];
            foreach ($groups as $i => $groupId) {
                $rows[] = [
                    'seq'       => $sequence[$i], 
                    'layout_id'  => $layouts[$i],        // sequence number
                    'group_id'  => $groupId,
                    'sub_group' => $sub_groups[$i],
                    'process'   => $processes[$i]
                ];
            }

            // Step 2: Sort the rows array by sequence ascending
            usort($rows, function($a, $b) {
                return $a['seq'] - $b['seq']; // ascending
            });

            // Step 3: Build the string after sorting
            foreach ($rows as $row) {
                $newSequence[] = $row['seq'] . '-' .$row['layout_id'] . '-' . $row['group_id'] . '-' . $row['sub_group'] . '-' . $row['process'];
            }
            $newSeqString = implode('|', $newSequence);

                    $this->db->where('product_type',$product_type);
           $routHeaders = $this->db->where('id !=', $id)->get('rout_header')->result();

           //print_r($routHeaders); die;
            foreach ($routHeaders as $rout) {
                // $details = $this->db->where('rout_id', $rout->id)
                // ->order_by('seq', 'ASC')
                // ->get('rout_detail')
                // ->result();

                $query="SELECT * From rout_detail where rout_id='".$rout->id."' order by seq ASC";
                $details=$this->db->query($query)->result();
              
                $seqArr = [];
                foreach ($details as $d) {
                    $seqArr[] = $d->seq . '-' .$d->layout_id . '-' .$d->group_id . '-' . $d->sub_group_id . '-' . $d->process_id;
                }
                $existingSeqString = implode('|', $seqArr);
              
                
                if ($newSeqString === $existingSeqString) {
                    $this->session->set_flashdata('error', 'This routing already exists in Master: ' . $bom->name);
                    redirect('Master/routing_master');
                    return;
                }
            }

          

            $headerData=array(
                
                        'rout_code'=>$rout_code,
                        'product_type'=>$product_type,
                        'name'=>$name,
                        'description'=>$description,
                        'updated_by'=>$this->session->userdata('user_login_id'),
                        'updated_at'=>date('Y-m-d H:i:s')
                  );

            $checker=$this->db->query("select count(*) as count from rout_header where name='$name' and is_deleted='N' and id!='".$id."'")->row()->count;

            if($checker > 0)
            {
                $this->session->set_flashdata('error', 'This routing  Name already exists in Master');
                redirect('Master/routing_master');
            }
            else{


            $this->db->where('id',$id);
           $update  = $this->db->update('rout_header', $headerData);
            $bomId = $id;

            if($update)
            {   
                $this->db->where('rout_id',$id);
                $this->db->delete('rout_detail');
            }
            foreach ($groups as $i => $groupId) {
                $data = [
                    'rout_id'        => $bomId,
                    'seq'           => $sequence[$i],
                    'layout_id'     =>$layouts[$i],
                    'group_id'      => $groupId,
                    'sub_group_id'  => $sub_groups[$i],
                    'process_id'    => $processes[$i],
                  //  'process_time'  => $processTime[$i],
                ];
                $this->db->insert('rout_detail', $data);
            }
        }

            $this->session->set_flashdata('success', 'Rout saved successfully.');
            redirect('Master/routing_master');


        }
        else{
            redirect(base_url(),'refresh');
        }

    }


public function getRoutingDetails()
{
    $id = $this->input->post('id');

    $sql="SELECT a.*, b.name AS group_name, c.subgroup_name ,p.process_name,l.layoutName
        FROM rout_detail AS a INNER JOIN process_group AS b ON a.group_id = b.id LEFT JOIN process_subgroup AS c ON a.sub_group_id = c.id JOIN Process as p ON p.id=a.process_id JOIN product_layout as l ON l.id=a.layout_id  where rout_id='$id' order by a.seq";

    $result=$this->db->query($sql)->result();
  //echo $sql; die;

    $html = '

            <table class="table table-sm table-striped table-hover table-bordered align-middle mb-0">
            <thead class="table-secondary text-center">
             <tr><th>Step</th><th>Layout</th><th>Group</th><th>Sub Group</th><th>Process</th></tr>
             </thead><tbody>';

    if (!empty($result)) {
        foreach ($result as $d) {
            $html .= "<tr>
                        <td class='text-center'>{$d->seq}</td>
                         <td>{$d->layoutName}</td>
                        <td>{$d->group_name}</td>
                        <td>".($d->subgroup_name ?? '-')."</td>
                        <td>{$d->process_name}</td>
                       
                       
                      </tr>";
        }
    } else {
        $html .= '<tr><td colspan="4" class="text-center text-muted">No details found</td></tr>';
    }
    $html .= '</tbody></table>';

    echo $html; // Directly return HTML
}


    public function removeRouting($id)
    {
        if($this->session->userdata('user_login_access')!=false)
        {
            $id=base64_decode($id);

            $data=array(
                'is_deleted'=>'Y',
                'updated_by'=>$this->session->userdata('user_login_id'),
                'updated_at'=>date('Y-m-d H:i:s')
            );
            $this->db->where('id',$id);
            $update= $this->db->update('rout_header',$data);

           if($update)
            {   
                $this->db->where('rout_id',$id);
                $this->db->delete('rout_detail');

                $this->session->set_flashdata("success","Successfully Deleted");
            }
            else{
                 $this->session->set_flashdata("error","Something Went Wrong");
            }
         redirect('Master/routing_master');

        }
        else{
            redirect(base_url(),'refresh');
        }
    }


public function fetchProcessGroupByProductType()
{
    $product_type = $this->input->post('product_type');
    $layout_id=$this->input->post('layout_id');
  
    // $sql ="select distinct(a.process_group_id),b.name from mapping_processLayout as a,process_group as b where a.product_type_id='".$product_type."' and b.id=a.process_group_id and b.is_deleted='N' ";

  
    // $group=$this->db->query($sql)->result();


    $group=$this->master_model->getGroupByProduct($product_type,$layout_id);



    $group_array='';

    foreach($group as $value)
    {
        $group_array.= '<option value="'.$value->process_group_id.'">'.$value->name .'</option>';
    }

    $data=array(

        'group'=>$group_array,
        'csrfHash' => $this->security->get_csrf_hash(),
      

    );

   echo  json_encode($data);
}


public function fetchProcessSubGroupByProductType()
{
    $product_type = $this->input->post('product_type');
    $group=$this->input->post('group');

  
    // $sql ="select distinct(a.process_subgroup_id),b.subgroup_name from mapping_processLayout as a,process_subgroup as b where a.product_type_id='".$product_type."' and b.id=a.process_subgroup_id and b.is_deleted='N' and b.status='ACTIVE' and process_group_id='".$group."'";

  
    //$sub_groups=$this->db->query($sql)->result();

    $sub_groups=$this->master_model->fetchProcessSubGroupByProductType($product_type,$group );


    $subgroupArray='';



    foreach($sub_groups as $value)
    {
        $subgroupArray.= '<option value="'.$value->process_subgroup_id.'">'.$value->subgroup_name .'</option>';
    }

    $process='';
    if(empty($sub_groups)){

        // $processList = $this->db->query("select distinct(a.process_id),b.process_name from mapping_processLayout as a,process as b where a.product_type_id='".$product_type."' and b.id=a.process_id and b.is_deleted='N' and b.status='ACTIVE' and process_group_id='".$group."'")->result();


        $processList=$this->master_model->getProcessByproduct_type($product_type,$group);
        
        foreach ($processList as $value) {
            $process .= '<option value="' . $value->process_id . '">' .$value->process_name . '</option>';
        }

    }

   

    $data=array(

        'subgroup'=>$subgroupArray,
        'process'=>$process,
        'csrfHash' => $this->security->get_csrf_hash(),
      

    );

   echo  json_encode($data);
}


public function fetchProcessByProductType()
{
     $product_type = $this->input->post('product_type');
     $group=$this->input->post('group');
     $subgroup=$this->input->post('subgroup');

     // $processList = $this->db->query("select distinct(a.process_id),b.process_name from mapping_processLayout as a,process as b where a.product_type_id='".$product_type."' and b.id=a.process_id and b.is_deleted='N' and b.status='ACTIVE' and a.process_group_id='".$group."' and a.process_subgroup_id='".$subgroup."'")->result();

    $processList=$this->master_model->getProcessByproduct_type($product_type,$group, $subgroup);

     $process='';

       foreach ($processList as $value) {
            $process .= '<option value="' . $value->process_id . '">' .$value->process_name . '</option>';
        }

    $data=array(

       
        'process'=>$process,
        'csrfHash' => $this->security->get_csrf_hash(),
      

    );

   echo  json_encode($data);
}

#Raw Material Master

    public function rowMaterial()
    {
        $data['urldata'] = $this->Access_model->get_menu_data();
          $access=$this->Access_model->getPermissionByAction();
          if($access!='success')
          {
            $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
            $data['title'] = 'Something went wrong';
            $this->load->view('errorPage', $data);
            return;
        }

        $data['title'] = $data['urldata']->menu_name;

        $data['rawMaterials'] = $this->master_model->getrawMaterials();
        $this->load->view('master/m_rawMaterial',$data);
    }

public function save_rawmaterial()
{

    if($this->session->userdata('user_login_access')!=false)
    {
        $id=$this->input->post('id');


        $data['title'] ='Raw Material';

       // $RM_code=$rm_code;
        $material_name=$this->input->post('material_name');
        $description=$this->input->post('description');
       

        $sql="select count(*) as count from m_rawMaterial  WHERE material_name = '".$material_name."' ";

        if($id!='')
        {
            $sql .=" and id!='$id'";
        }

        $checker=$this->db->query($sql)->row()->count;

        if($checker > 0)
        {
         $this->session->set_flashdata('error', 'This Material Name  Alrady Exist');

     }else{

        $data= array(
           
            'material_name'=>$material_name,
            'description'=>$description,
            'is_deleted'=>'N'
        
        );

        if($id!='')
        {
            $rm_code=$this->input->post('rm_code');
            $data1=array(
                 'material_code'=>$rm_code,
                'updated_at'=>date('Y-m-d H:i:s'),
                'updated_by'=>$this->session->userdata('user_login_id')

                );

            $data=array_merge($data,$data1);
            $this->db->where('id',$id);
            $insert=$this->db->update('m_rawMaterial',$data);
        }
        else{
           $rm_code=$this->db->query("SELECT 'RM' +  RIGHT('000' + CAST(ISNULL(MAX(CAST(RIGHT(material_code, 3) AS INT)) + 1, 1) AS VARCHAR(3)), 3) AS rm_code
               FROM m_rawMaterial ")->row()->rm_code;

             $data1=array(
                'material_code'=>$rm_code,
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$this->session->userdata('user_login_id')

                );
              $data=array_merge($data,$data1);
           $insert=$this->db->insert('m_rawMaterial',$data);
       }

       if($insert)
       {
           $this->session->set_flashdata('success',"Successfully Inserted");
       }
       else{
           $this->session->set_flashdata('error',"Somthing Went Wrong");
       }
   }

         //$data['product_type']=$this->master_model->getProductData();

   redirect('Master/rowMaterial');

}
else{

   redirect(base_url(), 'refresh');
}
}

public function edit_rawMaterial($id)
{   
    $id=base64_decode($id);

    $data['title'] = 'Raw Material';
    $data['editdata'] = $this->master_model->getrawMaterials($id);
    $data['rawMaterials']=$this->master_model->getrawMaterials();
    $this->load->view('master/m_rawMaterial',$data);
}

public function deleteMaterial($id)
{
    $id=base64_decode($id);
    $data=array(

        'is_deleted'=>'Y',
        'deleted_by'=>$this->session->userdata('user_login_id'),
        'deleted_at'=>date('Y-m-d H:i:s')
    );

    $this->db->where('id',$id);
    $update = $this->db->update('m_rawMaterial',$data);

  if($update)
  {
    $this->session->set_flashdata("success","Successfully Deleted");
  }

  redirect('Master/rowMaterial');
}


public function productToRawMaterial($product_id)
{
    if($this->session->userdata('user_login_id'))
    {
    $product_id=base64_decode($product_id);

    $data['product_type_name']= $this->master_model->getProductData($product_id)->product_name;

     $data['product_id']=$product_id;
     //$data['rawMaterials']=$this->master_model->getItems('Raw Material');

   
       $data['products']= $this->db->query("SELECT * FROM item_master where u_product_type=(select product_name from products where id='$product_id') and deleted = 'N'")->result();

        // $data['map'] = $this->db->query("
        //     SELECT 
        //         a.item_code,
        //         a.singleupsMaterial,
        //         rm1.item_name AS singleUPSmaterial_name,
        //         a.doubleupsMaterial,
        //         rm2.item_name AS doubleUPSmaterial_name,
        //         a.fourupsMaterial,
        //         rm4.item_name AS fourUPSmaterial_name
        //     FROM mapping_productWithMaterial a
        //      JOIN item_master rm1 ON rm1.item_code = a.singleUPSmaterial
        //      JOIN item_master rm2 ON rm2.item_code = a.doubleUPSmaterial
        //      JOIN item_master rm4 ON rm4.item_code = a.fourUPSmaterial
        // ")->result();

    
        $this->load->view('master/product/productTorawMaterial1',$data);
    }
    else{

        redirect(base_url(),'refresh');
    }
}

public function getRawMaterial()
{
    $bomHeader=$this->input->post('item_code');
    $rawmaterial = $this->bom_model->getBomItems($bomHeader);

    
    foreach ($rawmaterial as $row) {
        $result[] = [
            'code' => $row->ItemNo,
            'name' => $row->ItemName
        ];
    }

    echo json_encode($result);
}

    public function saveProductWithRawMaterial()
    {
        if($this->session->userdata('user_login_access')!=false)
        {

           $item_code   = $this->input->post('item_code');
           $ups1        = $this->input->post('singleUPSmaterial');
           $ups2        = $this->input->post('doubleUPSmaterial');
           $ups4        = $this->input->post('fourUPSmaterial');

           $data=array(

                'item_code'=>$item_code,
                'singleupsMaterial'=>$ups1,
                'doubleupsMaterial'=>$ups2,
                'fourupsMaterial'=>$ups4,
                'created_by'=>$this->session->userdata('user_login_id'),
                'created_at'=>date('Y-m-d H:i:s')
           );

            $insert=  $this->db->insert('mapping_productWithMaterial',$data);
           if ($insert) {

                echo json_encode(['status' => 'success']);
            } else {

                echo json_encode(['status' => 'error', 'message' => 'Database error']);
            }
        }
        else{

            redirect(base_url(),'refresh');
        }
}


public function productToMaterial()
{
    if ($this->session->userdata('user_login_access') != false) {

        $item_code    = $this->input->post('item_code_modal');
        $product_type = $this->input->post('product_type');
        $rawmaterial  = $this->input->post('rawMaterialName');
        $ups_input    = $this->input->post('ups_input');


        $countraws = count($rawmaterial);
     
       
        $this->db->where('main_product_code', $item_code);
        $this->db->where('product_type', $product_type);
        $this->db->delete('mapping_productWithMaterial');

        $inserted = false;

        for ($i = 0; $i < $countraws; $i++) {
            $data = array(
                'main_product_code'     => $item_code,
                'material_code'   => $rawmaterial[$i],
                'ups'           => $ups_input[$i],
                'product_type'  => $product_type,
                'created_by'    => $this->session->userdata('user_login_id'),
                'created_at'    => date('Y-m-d H:i:s')
            );

            $inserted = $this->db->insert('mapping_productWithMaterial', $data);
        }

        if ($inserted) {
          $this->session->set_flashdata("success","Successfully Inserted");

        } else {
             $this->session->set_flashdata("error","Somthing wrong");
        }
        redirect('Master/productToRawMaterial/'.base64_encode($product_type));
    } else {
        redirect(base_url(), 'refresh');
    }
}


public function getSavedRawMaterials()
{
     $item_code = $this->input->post('item_code');

     $sql="SELECT a.*, b.ItemName
            FROM mapping_productWithMaterial AS a
            JOIN (
                SELECT ItemNo, MAX(ItemName) AS ItemName
                FROM bomItems
                GROUP BY ItemNo
            ) AS b
            ON a.material_code = b.ItemNo
            WHERE a.main_product_code = '".$item_code."'";
     $query=$this->db->query($sql);

    echo json_encode($query->result());
}




# guage master


  public function gaugeMaster()
    {
        $data['urldata'] = $this->Access_model->get_menu_data();
          $access=$this->Access_model->getPermissionByAction();
          if($access!='success')
          {
            $expld=explode('/',$access); $data['heading']=$expld[0]; $data['description']=$expld[1];
            $data['title'] = 'Something went wrong';
            $this->load->view('errorPage', $data);
            return;
        }

        $data['title'] = $data['urldata']->menu_name;

        $data['gauges'] = $this->master_model->getgauges();
        $this->load->view('master/gauge_master',$data);
    }

public function save_gauge()
{

    if($this->session->userdata('user_login_access')!=false)
    {
        $id=$this->input->post('id');

        $data['title'] ='gauges';

        $gauge_name=$this->input->post('gauge_name');
        //$unit=$this->input->post('gauge_unit');

       // $description=$this->input->post('description');
       

        $sql="select count(*) as count from m_gauge  WHERE gauge_name = '".$gauge_name."' and is_deleted='N' ";

        if($id!='')
        {
            $sql .=" and id!='$id'";
        }
        $checker=$this->db->query($sql)->row()->count;
        if($checker > 0)
        {
         $this->session->set_flashdata('error', 'This gauge Name  Alrady Exist');

     }else{

        $data= array(
           
            'gauge_name'=>$gauge_name,
          //  'gauge_unit'=>$unit,
            'is_deleted'=>'N'
        
        );

        if($id!='')
        {
            $gauge_code=$this->input->post('gauge_code');
            $data1=array(
                'gauge_code'=>$gauge_code,
                'updated_at'=>date('Y-m-d H:i:s'),
                'updated_by'=>$this->session->userdata('user_login_id')

                );

            $data=array_merge($data,$data1);
            $this->db->where('id',$id);
            $insert=$this->db->update('m_gauge',$data);
        }
        else{
           $gauge_code=$this->db->query("SELECT 'GA' +  RIGHT('000' + CAST(ISNULL(MAX(CAST(RIGHT(gauge_code, 3) AS INT)) + 1, 1) AS VARCHAR(3)), 3) AS gauge_code
               FROM m_gauge ")->row()->gauge_code;

             $data1=array(
                'gauge_code'=>$gauge_code,
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>$this->session->userdata('user_login_id')

                );
            $data=array_merge($data,$data1);
           $insert=$this->db->insert('m_gauge',$data);
       }

       if($insert)
       {
           $this->session->set_flashdata('success',"Successfully Inserted");
       }
       else{
           $this->session->set_flashdata('error',"Somthing Went Wrong");
       }
   }

         //$data['product_type']=$this->master_model->getProductData();

     redirect('Master/gaugeMaster');

    }
    else{

       redirect(base_url(), 'refresh');
    }
}

public function edit_gaugeMaster($id)
{   
    $id=base64_decode($id);

    $data['title'] = 'Gauge Master';
    $data['editdata'] = $this->master_model->getGauges($id);
    $data['gauges']=$this->master_model->getGauges();
    $this->load->view('master/gauge_master',$data);
}

public function deleteGauge($id)
{
    $id=base64_decode($id);
    $data=array(

        'is_deleted'=>'Y',
        'deleted_by'=>$this->session->userdata('user_login_id'),
        'deleted_at'=>date('Y-m-d H:i:s')
    );

        $this->db->where('id',$id);
        $update = $this->db->update('m_gauge',$data);

      if($update)
      {
        $this->session->set_flashdata("success","Successfully Deleted");
      }

    redirect('Master/gaugeMaster');
}



public function importGaugeExcel()
{
    if (!empty($_FILES['excel_file']['name'])) {
        $file = fopen($_FILES['excel_file']['tmp_name'], 'r');

     
        $header = fgetcsv($file);

        try {
            $inserted = 0;
            $skipped = [];


            while (($row = fgetcsv($file)) !== FALSE) {

                $gauge_name = trim($row[0]); 
               // echo $gauge_name ."Adsfs"; 
                if (!empty($gauge_name)) {
                   
                    $sql = "SELECT COUNT(*) as count FROM m_gauge WHERE gauge_name ='".$gauge_name."' and is_deleted='N'";
                    $checker = $this->db->query($sql)->row()->count;

                    if ($checker == 0) {
                     
                        $query = $this->db->query("
                            SELECT 'GA' +  RIGHT('000' + CAST(ISNULL(MAX(CAST(RIGHT(gauge_code, 3) AS INT)) + 1, 1) AS VARCHAR(3)), 3) AS gauge_code
                         FROM m_gauge
                        ");
                        $gauge_code = $query->row()->gauge_code;

                    
                        $data = [
                            'gauge_code' => $gauge_code,
                            'gauge_name' => $gauge_name
                        ];

                      //  print_r($data); die;
                        $this->db->insert('m_gauge', $data);

                        $inserted++;
                    } else {
                        // keep track of skipped names
                        $skipped[] = $gauge_name;
                    }
                }
            }

            fclose($file);

            // ✅ Show appropriate flash messages
            if ($inserted > 0) {
                $this->session->set_flashdata('success', "$inserted gauges imported successfully!");
            }
            if (!empty($skipped)) {
                $this->session->set_flashdata('error', "These gauges already exist: " . implode(', ', $skipped));
            }

            } catch (Exception $e) {
                $this->session->set_flashdata('error', 'Error loading file: ' . $e->getMessage());
            }
        } else {
            $this->session->set_flashdata('error', 'Please upload a valid Excel/CSV file.');
        }

    redirect('Master/gaugeMaster'); 
}


public function importMachineMapping()
{
    if($this->session->userdata('user_login_access') == false){
        redirect(base_url(),'refresh');
    }

    if (empty($_FILES['csv_file']['name'])) {
        $this->session->set_flashdata("error","No file uploaded");
        // echo "<br>No file uploaded";
        return;
    }

    $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
    fgetcsv($file); // skip header

    $insertData = [];
    $skippedRows = [];
    $rowCount = 0;

    while (($row = fgetcsv($file)) !== false) {
        $rowCount++;
      //  echo "<br>Processing row #{$rowCount}: " . implode(' | ', $row);

        // Get product ID
        $product_type_id = $this->db->select('id')
                                    ->from('products')
                                    ->where('product_name', trim($row[0]))
                                    ->get()
                                    ->row('id');

        // Get process group ID
        $process_group_id = $this->db->select('id')
                                     ->from('process_group')
                                     ->where('name', trim($row[1]))
                                     ->where('is_deleted', 'N')
                                     ->where('status', 'ACTIVE')
                                     ->get()
                                     ->row('id');

         //echo "<br>Product ID: " . ($product_type_id ?? 'Not found');
        // echo "<br>Process Group ID: " . ($process_group_id ?? 'Not found');

        if (!$product_type_id || !$process_group_id) {
            $skippedRows[] = "Row {$rowCount}: Missing product or process group";
            continue;
        }

        // Get process IDs
        $processNames = array_map('trim', explode(',', $row[2]));
        $processIds = [];
        foreach ($processNames as $processName) {
            $id = $this->db->select('id')
                           ->from('process')
                           ->where('process_name', $processName)
                           ->get()
                           ->row('id');
            if ($id) {
                $processIds[] = $id;
            }
        }
        $processArray = implode(',', $processIds);

        // Get machine IDs
        $machineNames = array_map('trim', explode(',', $row[3]));
        $machineIds = [];
        foreach ($machineNames as $machineName) {
            $id = $this->db->select('id')
                           ->from('machines')
                           ->where('machine_name', $machineName)
                           ->get()
                           ->row('id');
            if ($id) {
                $machineIds[] = $id;
            }
        }
        $machineArray = implode(',', $machineIds);

        if (empty($processArray) || empty($machineArray)) {
            $skippedRows[] = "Row {$rowCount}: Process or Machine IDs missing";
            continue;
        }

        // Check if mapping already exists
        $sql = "SELECT COUNT(*) AS count 
                FROM mapping_productTypeMachine AS a
                CROSS APPLY STRING_SPLIT(a.machine_id, ',') AS m
                CROSS APPLY STRING_SPLIT(a.process_id, ',') AS p
                WHERE a.is_deleted = 'N'
                  AND a.product_type_id = '".$product_type_id."'
                  AND m.value IN (".$machineArray.")
                  AND p.value IN (".$processArray.")";
                  //echo $sql ; die;

        $checker = $this->db->query($sql)->row()->count ?? 0;
       

        if ($checker > 0) {
            $skippedRows[] = "Row {$rowCount}: Already exists";
            continue;
        }

        // Prepare insert data
        $insertData[] = [
            'product_type_id'   => $product_type_id,
            'process_group_id'  => $process_group_id,
            'process_id'        => $processArray,
            'machine_id'        => $machineArray,
            'hourly_impression' => $row[4],
            'min_qty'           => $row[5],
            'max_qty'           => $row[6],
            'created_at'        => date('Y-m-d H:i:s'),
            'created_by'        => $this->session->userdata('user_login_id'),
            'is_deleted'        => 'N'
        ];

        //echo "<br>Prepared insert data for row #{$rowCount}: " . json_encode(end($insertData));
    }

    fclose($file);

  
    if (!empty($insertData)) {
        $this->db->insert_batch('mapping_productTypeMachine', $insertData);

        $this->session->set_flashdata("success","Data imported successfully!");
        //echo "<br>Data imported successfully! Total inserted rows: " . count($insertData);
    } else {
         $this->session->set_flashdata("error","No valid rows to insert");
        //echo "<br>No valid rows to insert";
    }
     if (!empty($skippedRows)) {
        $this->session->set_flashdata("error", implode("<br>", $skippedRows));
    }

    redirect('Master/mapping_machine');
  
 }


 public function exportExcelMachine()
 {
        if($this->session->userdata('user_login_access')!=false)
        {
            //echo base64_decode($id);

          header("Content-Type: text/csv");
          header("Content-Disposition: attachment;filename=machines_export.csv");

          $output = fopen("php://output", "w");

    
          fputcsv($output, [
            'Code', 'Name', 'Description', 'Shift Pattern', 'Shift Time', 'Setup Time',
            'Changeover Time', 'Job Setting Time', 'Availability', 'Max Operational Hours',
            'Operators', 'Helpers', 'Type', 'Gauge', 'Changeover Frequency', 
            'Production Capacity', 'Min Width', 'Max Width', 'Min Length', 'Max Length', 'Status'
        ]);

          //$machines = $this->db->get('machines')->result();
          $machines=$this->master_model->getmachines();


          //print_r($machines); die;

          foreach ($machines as $machine) {
    
            $operators = !empty($machine->main_operator)
            ? implode(', ', array_map(
                fn($o) => $o->first_name.' '.$o->last_name,
                $this->db->where_in('em_id', explode(',', $machine->main_operator))->get('employee')->result()
            )) : '';

          // Get helper names
            $helpers = !empty($machine->helper)
            ? implode(', ', array_map(
                fn($h) => $h->first_name.' '.$h->last_name,
                $this->db->where_in('em_id', explode(',', $machine->helper))->get('employee')->result()
            )) : '';

          // Get gauge names
            $gauges = !empty($machine->gauge)
            ? implode(', ', array_map(
                fn($g) => $g->gauge_name,
                $this->db->where_in('id', explode(',', $machine->gauge))->get('m_gauge')->result()
            )) : '';

            fputcsv($output, [
                $machine->code,
                $machine->machine_name,
                $machine->description,
                $machine->shift_pattern,
                $machine->shift_time,
                $machine->setup_time,
                $machine->changeover_time,
                $machine->jobsetting_time,
                $machine->availablity,
                $machine->max_operational_hour,
                $operators,
                $helpers,
                $machine->job_type,
                $gauges,
                $machine->changeover_frequency,
                $machine->production_capacity,
                $machine->min_width,
                $machine->max_width,
                $machine->min_length,
                $machine->max_length,
                $machine->status
            ]);
        }

        fclose($output);
        exit;
    }
    else{
        redirect(base_url(),'refresh');
    }
}



public function exportExcelProcess()
{
    if ($this->session->userdata('user_login_access') != false) {

      
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment;filename=process_export.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        // CSV header
        fputcsv($output, [
            'Product Name',
            'Group Name',
            'Subgroup Name',
            'Process Name',
            'Layout Name',
            'Is Default',
          
        ]);

        $records = $this->master_model->getProcessMapping();

        foreach ($records as $row) {
            fputcsv($output, [
                $row->product_name,
                $row->groupName,
                $row->subgroup_name,
                $row->process_name,
                $row->layoutName,
                $row->is_default,
             
            ]);
        }

        fclose($output);
        exit;

    } else {
        redirect(base_url(), 'refresh');
    }
}



}
?>