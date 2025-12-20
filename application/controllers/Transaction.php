<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaction extends CI_Controller {

	function __construct() 
	{
		parent::__construct();
		 $this->load->database();
        $this->load->model('dashboard_model');
        $this->load->model('employee_model');
        $this->load->model('login_model');
        $this->load->model('settings_model');  
        $this->load->model('Access_model');   
         $this->load->model('sales_model');   
        $this->load->model('master_model');   
		
	}

	public function salesorder(){
		
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
			$this->load->view('transaction/salesorders',$data);
		}
		else{
			redirect(base_url(),'refresh');
		}
		

	}

	// public function getSalesorderList()
	// {
	// 	$data = $row = array();
 //          $draw   = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;
 //          $start  = isset($_POST['start']) ? (int)$_POST['start'] : 0;
 //         $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
         
 //            $this->column_search = array('DocNum', 'DocDate','CardName');
 //            $sql = "select * from salesorderheader where DocStatus='O'";
            

 //            $sql1 = $sql;
 //            if (!empty($_POST['search']['value'])) {
 //                $sql .= " AND (";
 //            }
 //            $i = 0;
 //            foreach ($this->column_search as $item) {
 //                if (!empty($_POST['search']['value'])) {
 //                    $sql .= "$item Like '%" . $_POST['search']['value'] . "%' OR ";
 //                }
 //                $i++;
 //            }
 //            if (!empty($_POST['search']['value'])) {
 //                $sql = rtrim($sql, ' OR ');
 //                $sql .= ")";
 //            }
 //            $sql.= "  order by id ";
 //            $wolimit = $sql;
 //            if(!empty($_POST['length']) && $_POST['length'] != -1)
 //            {
 //                $sql .=" OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
 //            }


 //            $itemdata = $this->db->query($sql)->result();
 //            $j = $_POST['start'];
 //            foreach ($itemdata as $value) {
 //                 $j++;
 //        	 $actionbutton ='<a href="'.base_url('Transaction/getItems/'.base64_encode($value->id)).'" class="btn btn-sm btn-info"><i class="fa fa-eye fa-sm"></i> Items</a>';
 //            	$data[] = array(
 //                   $value->id,
 //                   $value->DocDate,
 //                   $value->DocNum,
 //                   $value->CardName,
 //                   $actionbutton

 //                );


               
 //            }
 //              $output = array(
 //                "draw" => $_POST['draw'],
 //                "recordsTotal" => $this->db->query($sql1)->num_rows(),
 //                "recordsFiltered" => $this->db->query($wolimit)->num_rows(),
 //                "data" => $data,
 //            );

 //            // Output to JSON format
 //            echo json_encode($output);

	// }

    public function getSalesorderList()
{
    $data = array();

    $draw   = (int)$_POST['draw'];
    $start  = (int)$_POST['start'];
    $length = (int)$_POST['length'];
    $search = $_POST['search']['value'];

    $column_search = array('DocNum', 'DocDate', 'CardName');

    /* =======================
       BASE WHERE CONDITION
    ======================= */
    $where = " WHERE DocStatus = 'O' ";

    if (!empty($search)) {
        $where .= " AND (";
        foreach ($column_search as $col) {
            $where .= "$col LIKE '%".$this->db->escape_like_str($search)."%' OR ";
        }
        $where = rtrim($where, ' OR ');
        $where .= ")";
    }

    /* =======================
       DATA QUERY (LIMITED)
    ======================= */
    $sql = "
        SELECT id, DocDate, DocNum, CardName
        FROM salesorderheader
        $where
        ORDER BY id
        OFFSET $start ROWS FETCH NEXT $length ROWS ONLY
    ";

    $itemdata = $this->db->query($sql, FALSE); // unbuffered

    foreach ($itemdata->result() as $value) {

        $actionbutton =
            '<a href="'.base_url('Transaction/getItems/'.base64_encode($value->id)).'"
             class="btn btn-sm btn-info">
             <i class="fa fa-eye fa-sm"></i> Items</a>';

        $data[] = array(
            $value->id,
            $value->DocDate,
            $value->DocNum,
            $value->CardName,
            $actionbutton
        );
    }

    /* =======================
       COUNT QUERIES (SAFE)
    ======================= */

    // Total records
    $totalRecords = $this->db->query(
        "SELECT COUNT(*) AS total FROM salesorderheader WHERE DocStatus='O'"
    )->row()->total;

    // Filtered records
    $filteredRecords = $this->db->query(
        "SELECT COUNT(*) AS total FROM salesorderheader $where"
    )->row()->total;

    /* =======================
       RESPONSE
    ======================= */
    $output = array(
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $filteredRecords,
        "data" => $data,
    );

    echo json_encode($output);
}


    public function getItems($id){

        if($this->session->userdata('user_login_access')!=false)
        {
            $id=base64_decode($id);

            $data['salesItems']=$this->sales_model->getSalesitems($id);

            $data['sales_id']=$id;
            $this->load->view('transaction/salesItems',$data);

        }
        else{

            redirect(base_url(),'refresh');
        }
    }
   

public function save_salesProcess()
{
    if ($this->session->userdata('user_login_access') != false) {
        $id = $this->input->post('id');
        $sales_id = $this->input->post('sales_id');
        $product_id = $this->input->post('product_id');
        $doc_entry=$this->input->post('doc_entry');
        $new_design = $this->input->post('new_design') === 'Y' ? 'Y' : 'N';
        $new_design_text = $this->input->post('new_designtext');
        $repeat_design = $this->input->post('repeat_design') === 'Y' ? 'Y' : 'N';
        $repeat_design_text = $this->input->post('repeat_designText');
        $correction = $this->input->post('correction') === 'Y' ? 'Y' : 'N';
        $correction_text = $this->input->post('correction_text');
        $routing_id=$this->input->post('routing_id');
        $routing_name=$this->input->post('routing_name');
        $routing_code=$this->input->post('routing_code');

        $this->db->trans_start();

        // Insert into header table
        $headerData = [
            'product_id' => $product_id,
            'sales_item_id' => $id,
            'new_design' => $new_design,
            'new_design_text' => $new_design_text,
            'repeat_design' => $repeat_design,
            'repeat_design_text' => $repeat_design_text,
            'correction' => $correction,
            'doc_entry'=>$doc_entry,
            'routing_id'=>$routing_id,
            'routing_name'=>$routing_name,
            'routing_code'=>$routing_code,
            'correction_text' => $correction_text,
            'created_at' => date('Y-m-d H:i:s'),
            'is_submit'=>'N'
        ];


        $this->db->insert('sales_process_header', $headerData);
        $header_id = $this->db->insert_id();

        // Fetch posted data
        $layout_ids = $this->input->post('layout_id');
        $group_ids = $this->input->post('group_id');
        $process_names = $this->input->post('process_name');
        $param_values = $this->input->post('param_value');
        $groupimpression=$this->input->post('group_impression');
         $processimpression=$this->input->post('process_impression');
         $seq_no=$this->input->post('seq_no');


        $saveData = [];
        $paramData = [];
        $ordersGroupedData = [];

        if (!empty($process_names)) {
            foreach ($layout_ids as $layoutIndex => $layout_id) {
                foreach ($group_ids[$layoutIndex] as $groupIndex => $group_id) {
                    $process_ids_array = $process_names[$layoutIndex][$groupIndex] ?? [];
                     $group_impression=$groupimpression[$layoutIndex][$groupIndex]??0;
                    $process_id_string = implode(',', $process_ids_array);

                    $saveData =array(
                        'header_id' => $header_id,
                        'layout_id' => $layout_id,
                        'group_id' => $group_id,
                        'process_id' => $process_id_string,
                         'group_impression'=>$group_impression,
                        'created_at' => date('Y-m-d H:i:s')
                    );

                    $this->db->insert('sales_process_detail', $saveData);
                     $detail_id = $this->db->insert_id();
                    // Parameters

                    
                    foreach ($process_ids_array as $pid) {


                         if(isset($processimpression[$layout_id][$group_id][$pid]))
                        {
                            $impressions = $processimpression[$layout_id][$group_id][$pid];
                            $sequence_no=$seq_no[$layout_id][$group_id][$pid];

                              $data=array(

                                    'sales_header_id'=>$header_id,
                                    'sales_process_detail_id'=>$detail_id,
                                    'process_id'=>$pid,
                                    'process_impression'=>$impressions,
                                    'sequence_no'=>$sequence_no

                                );
                             
                                $this->db->insert('sales_process_impression',$data);
                              
                        }


                        if (isset($param_values[$layout_id][$group_id][$pid])) {
                            foreach ($param_values[$layout_id][$group_id][$pid] as $param_id => $value) {
                              
                                if (is_array($value)) {
                                     
                                   // foreach ($value as $val) {
                                        $paramData[] = [
                                            'sales_detail_id'=>$detail_id,
                                            'header_id'=>$header_id,
                                            'sales_item_id' => $id,
                                            'product_id' => $product_id,
                                            'process_id' => $pid,
                                            'parameter_id' => $param_id,
                                            'parameter_value' => implode(',', $value),
                                            'created_at' => date('Y-m-d H:i:s'),
                                        ];
                                    //}
                                } else {
                                    $paramData[] = [
                                        'sales_detail_id'=>$detail_id,
                                        'header_id'=>$header_id,
                                        'sales_item_id' => $id,
                                        'product_id' => $product_id,
                                        'process_id' => $pid,
                                        'parameter_id' => $param_id,
                                        'parameter_value' => $value,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        $salesitemdetail=array('is_process_assign'=>'Y','RoutingProcessDHT'=>$routing_code);
        $salesitems=$this->db->where('id',$id);
        $this->db->update('salesorderitems',$salesitemdetail);
      
        if (!empty($paramData)) {
            $this->db->insert_batch('sales_process_parameters', $paramData);
        }

    
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect('Transaction/getItems/' . base64_encode($sales_id));
        } else {
           // $this->session->set_flashdata("success", "Successfully inserted");
           // redirect('Transaction/preview/'.base64_encode($sales_id).'/'.base64_encode($id));
        redirect('Transaction/preview/'.base64_encode($sales_id).'/'.base64_encode($header_id));
        }

       // redirect('Transaction/getItems/' . base64_encode($sales_id));
    } else {
        redirect(base_url(), 'refresh');
    }
}


public function update_salesProcess()
{
        $process_header_id=$this->input->post('process_header_id');
        $sales_id = $this->input->post('sales_id');
        $doc_entry=$this->input->post('doc_entry');
        $product_id = $this->input->post('product_id');
        $id = $this->input->post('id');
        $new_design = $this->input->post('new_design') === 'Y' ? 'Y' : 'N';
        $new_design_text = $this->input->post('new_designtext');
        $repeat_design = $this->input->post('repeat_design') === 'Y' ? 'Y' : 'N';
        $repeat_design_text = $this->input->post('repeat_designText');
        $correction = $this->input->post('correction') === 'Y' ? 'Y' : 'N';
        $correction_text = $this->input->post('correction_text');
        $routing_id=$this->input->post('routing_id');
        $routing_name=$this->input->post('routing_name');
        $routing_code=$this->input->post('routing_code');
        //echo $routing_code; die;

        $this->db->trans_start();

    
        $headerData = [
            'product_id' => $product_id,
            'sales_item_id' => $id,
            'doc_entry'=>$doc_entry,
            'new_design' => $new_design,
            'new_design_text' => $new_design_text,
            'repeat_design' => $repeat_design,
            'repeat_design_text' => $repeat_design_text,
            'correction' => $correction,
            'correction_text' => $correction_text,
            'routing_id'=>$routing_id,
            'routing_name'=>$routing_name,
            'routing_code'=>$routing_code,
            'updated_at' => date('Y-m-d H:i:s'),
            'is_submit'=>'N'
        ];
        $this->db->where('id',$process_header_id);
        $this->db->update('sales_process_header', $headerData);
        $header_id = $process_header_id;

         $this->db->where('header_id',$process_header_id);
        $this->db->delete('sales_process_parameters');

          $this->db->where('sales_header_id',$process_header_id);
        $this->db->delete('sales_process_impression');
       
        $this->db->where('header_id', $process_header_id);
        $this->db->delete('sales_process_detail');

         // Fetch posted data
        $layout_ids = $this->input->post('layout_id');
        $group_ids = $this->input->post('group_id');
        $process_names = $this->input->post('process_name');
        $param_values = $this->input->post('param_value');
        $groupimpression=$this->input->post('group_impression');
        $processimpression=$this->input->post('process_impression');
          $seq_no=$this->input->post('seq_no');
    

        $saveData = [];
        $paramData = [];
        $ordersGroupedData = [];

        if (!empty($process_names)) {
            foreach ($layout_ids as $layoutIndex => $layout_id) {
                foreach ($group_ids[$layoutIndex] as $groupIndex => $group_id) {
                    $process_ids_array = $process_names[$layoutIndex][$groupIndex] ?? [];
                    $process_id_string = implode(',', $process_ids_array);
                    $group_impression=$groupimpression[$layoutIndex][$groupIndex]??0;

                    $saveData =array(
                        'header_id' => $header_id,
                        'layout_id' => $layout_id,
                        'group_id' => $group_id,
                        'process_id' => $process_id_string,
                        'group_impression'=>$group_impression,
                        'created_at' => date('Y-m-d H:i:s')
                    );

                    $this->db->insert('sales_process_detail', $saveData);
                     $detail_id = $this->db->insert_id();
                  
                    foreach ($process_ids_array as $pid) {

                        if(isset($processimpression[$layout_id][$group_id][$pid]))
                        { 
                            $impressions = $processimpression[$layout_id][$group_id][$pid];
                              $sequence_no=$seq_no[$layout_id][$group_id][$pid];

                              $data=array(

                                    'sales_header_id'=>$header_id,
                                    'sales_process_detail_id'=>$detail_id,
                                    'process_id'=>$pid,
                                    'process_impression'=>$impressions,
                                     'sequence_no'=>$sequence_no

                                );
                           //  print_r($data);

                                $this->db->insert('sales_process_impression',$data);
                              
                              
                        }
                        if (isset($param_values[$layout_id][$group_id][$pid])) {
                            foreach ($param_values[$layout_id][$group_id][$pid] as $param_id => $value) {
                              
                                if (is_array($value)) {
                                     
                                   // foreach ($value as $val) {
                                        $paramData[] = [
                                            'sales_detail_id'=>$detail_id,
                                            'header_id'=>$header_id,
                                            'sales_item_id' => $id,
                                            'product_id' => $product_id,
                                            'process_id' => $pid,
                                            'parameter_id' => $param_id,
                                            'parameter_value' => implode(',', $value),
                                            'created_at' => date('Y-m-d H:i:s'),
                                        ];
                                    //}
                                } else {
                                    $paramData[] = [
                                        'sales_detail_id'=>$detail_id,
                                        'header_id'=>$header_id,
                                        'sales_item_id' => $id,
                                        'product_id' => $product_id,
                                        'process_id' => $pid,
                                        'parameter_id' => $param_id,
                                        'parameter_value' => $value,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        $salesitemdetail=array('RoutingProcessDHT'=>$routing_code);
        $salesitems=$this->db->where('id',$id);
        $this->db->update('salesorderitems',$salesitemdetail);

        if (!empty($paramData)) {
            $this->db->insert_batch('sales_process_parameters', $paramData);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata("error", "Something went wrong");
          redirect('Transaction/getItems/' . base64_encode($sales_id));
        } else {
            $this->session->set_flashdata("success", "Successfully inserted");
           // redirect('Transaction/preview/'.base64_encode($sales_id).'/'.base64_encode($id));
        redirect('Transaction/preview/'.base64_encode($sales_id).'/'.base64_encode($header_id));
        }
   }

  
    public function preview($sales_id,$process_id)
    {
        $sales_id=base64_decode($sales_id);

        $process_id = base64_decode($process_id);
       
        $process_header=$this->db->query("select * from sales_process_header where id='$process_id'")->row();
        $data['sales_item_id']=$process_header->sales_item_id;
        $data['process_header']=$process_header;
        $data['process']=$this->sales_model->getAssignprocess($process_id);
        $data['salesdata']=$this->sales_model->getSalesitems($sales_id,$data['sales_item_id']);

        $this->load->view('transaction/preview',$data);

    }

    public function updatePreviewAndPrint()
    {

        $sales_item_id=$this->input->post('sales_item_id');
        $sales_header=$this->input->post('sales_header');


        $id=$this->input->post('id');
        $dataupdate=array('is_submit'=>'Y');
        $this->db->where('id',$id);
        $this->db->update('sales_process_header',$dataupdate);

        $process_header=$this->db->query("select * from sales_process_header where id='$id'")->row();
        $this->data['sales_item_id']=$sales_item_id;
        $this->data['process_header']=$process_header;
        $this->data['process']=$this->sales_model->getAssignprocess($id);
      $this->data['salesdata']=$this->sales_model->getSalesitems($sales_header,$sales_item_id);

         $this->load->library('Pdf');

        $this->load->view('transaction/assignProcessPdf',$this->data);

         ob_clean();
        $this->pdf->Output('test.pdf', 'I');

       // redirect('Transaction/assignProcessPrint');

    }


// public function getProcessesByRouting() {
//     $routing_id = $this->input->post('routing_id');
    
//     $this->db->select('seq,group_id, process_id,layout_id');
//     $this->db->where('rout_id', $routing_id);
//     $query = $this->db->get('rout_detail')->result();

//     $result = [];
//     foreach ($query as $r) {
//         $result[$r->layout_id][$r->group_id]['processes'][] = $r->process_id;

//         // Sequence for each process
//         $result[$r->layout_id][$r->group_id]['seq'][$r->process_id] = $r->seq;
      
//     }

//     echo json_encode($result);
// }


public function getProcessesByRouting() {

    $routing_id = $this->input->post('routing_id');
    $product_type_id=$this->input->post('product_type_id');

    // $this->db->select('rd.seq, rd.group_id, rd.process_id, rd.layout_id, pm.process_name');
    // $this->db->from('rout_detail rd');
    // $this->db->join('process pm', 'pm.id = rd.process_id', 'left');
    // $this->db->where('rd.rout_id', $routing_id);


    $sql="SELECT 
            rd.rout_id,
            rd.layout_id,
            rd.group_id,
            rd.process_id,
            rd.seq,
            p.process_code,

            p.process_name,

            pp.id AS processmappingLayoutId,
            pp.product_layout_id,
        
            pp.process_id AS pp_process_id

        FROM rout_detail rd
         Left JOIN process p 
            ON p.id = rd.process_id

         Left JOIN mapping_processLayout pp
            ON pp.process_id = rd.process_id
            AND pp.process_group_id = rd.group_id
            AND pp.product_layout_id = rd.layout_id

     WHERE rd.rout_id ='".$routing_id."' and pp.product_type_id='".$product_type_id."' and pp.is_deleted='N'";

    $query = $this->db->query($sql)->result();

    $result = [];

    foreach ($query as $r) {

        // Add process options
        $result[$r->layout_id][$r->group_id]['options'][] = [
            "id"   => $r->process_id,
            "name" => $r->process_name,
            "processmappingLayoutId" => $r->processmappingLayoutId,
            "processCode"=>$r->process_code
        ];

        // Selected process list
        $result[$r->layout_id][$r->group_id]['processes'][] = $r->process_id;

        // Sequence list
        $result[$r->layout_id][$r->group_id]['seq'][$r->process_id] = $r->seq;
    }

    echo json_encode($result);
}

public function productionOrder()
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
     
        $this->load->view('transaction/product_orders');
    }
    else{

        redirect(base_url(),'refresh');
    }
}


   public function getProductOrderList1()
{
    $draw   = intval($this->input->post("draw"));
    $start  = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));

    // $sql = "
    //     SELECT id, doc_entry, doc_num, item_code, item_name
    //     FROM production_order
    //     ORDER BY id DESC
    //     OFFSET $start ROWS
    //     FETCH NEXT $length ROWS ONLY
    // ";

    $search = $_POST['search']['value'] ?? '';

/* Columns allowed for search */
$column_search = array(
    'p.doc_entry',
    'p.doc_num',
    'p.product_no',
    'p.product_name',
    'p.planned_qty',
);

/* =======================
   BASE WHERE CONDITION
======================= */
$where = " WHERE p.status != 'C' ";

if (!empty($search)) {
    $search = $this->db->escape_like_str($search);
    $where .= " AND (";
    foreach ($column_search as $col) {
        $where .= "$col LIKE '%$search%' OR ";
    }
    $where = rtrim($where, ' OR ');
    $where .= ")";
}



    // $sql="SELECT p.id, p.doc_entry, p.doc_num, p.product_no, p.product_name,p.planned_qty
    //         FROM production_order p
    //         JOIN (
    //             SELECT product_no, MAX(id) AS max_id
    //             FROM production_order  WHERE status != 'C'
    //             GROUP BY product_no
    //         ) g ON p.product_no = g.product_no AND p.id = g.max_id And p.status!='C'
    //           $where
    //         ORDER BY p.id DESC
    //         OFFSET $start ROWS
    //         FETCH NEXT $length ROWS ONLY";


  $sql = "
        SELECT p.id, p.doc_entry, p.doc_num, p.item_code, p.item_name,p.product_no,p.product_name,p.planned_qty
        FROM production_order as p
        ORDER BY id DESC
        OFFSET $start ROWS
        FETCH NEXT $length ROWS ONLY";


    $data = [];
    $query = $this->db->query($sql)->result();

    foreach ($query as $row) {
        $data[] = [
            '<input type="checkbox" class="row-check" value="'.$row->id.'" >',
            $row->doc_entry,
            $row->doc_num,
            $row->product_no,
            $row->product_name,
            $row->planned_qty
        ];
    }

    // Total records
    //$total = $this->db->query("SELECT COUNT(*) AS cnt FROM production_order")->row()->cnt;



     $recordsFiltered = $this->db
        ->query("
            SELECT COUNT(DISTINCT p.product_no) AS cnt
            FROM production_order  as p $where ")
        ->row()->cnt;
    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $recordsFiltered,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    ]);
}


public function getProductOrderList($product_name=null)
{
    $draw   = intval($this->input->post("draw"));
    $start  = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));

    $search   = $_POST['search']['value'] ?? '';
  

    /* Columns allowed for search */
    $column_search = array(
        'p.doc_entry',
        'p.doc_num',
        'p.product_no',
        'p.product_name',
        'p.planned_qty',
        'p.item_code',
        'p.item_name'
    );

    /* =======================
       BASE WHERE
    ======================= */
    $where = " WHERE p.status != 'C' ";

    if($product_name){ $where .="  and p.product_name='$product_name'";}

    /* 🔍 Global Search */
    if (!empty($search)) {
        $search = $this->db->escape_like_str($search);
        $where .= " AND (";
        foreach ($column_search as $col) {
            $where .= "$col LIKE '%$search%' OR ";
        }
        $where = rtrim($where, ' OR ');
        $where .= ")";
    }

    
    /* =======================
       DATA QUERY (FIXED ✅)
    ======================= */
    $sql = "
        SELECT p.id, p.doc_entry, p.doc_num,
               p.product_no, p.product_name, p.planned_qty,p.item_code,p.item_name
        FROM production_order p 
        INNER JOIN (
        SELECT doc_entry, MAX(id) AS max_id
        FROM production_order where status !='C'
        GROUP BY doc_entry
    ) g ON p.doc_entry = g.doc_entry AND p.id = g.max_id
        $where
        ORDER BY p.id DESC
        OFFSET $start ROWS
        FETCH NEXT $length ROWS ONLY
    ";

    $query = $this->db->query($sql)->result();

    $data = [];
    foreach ($query as $row) {

      $actionbutton =
            '<button type="button" class="btn btn-primary btn-sm" onclick="filteredRecords('.$row->id.')" >Filtered</button>';

        $data[] = [
          '<input type="checkbox" class="row-check" name="check_value[]" value="'.$row->doc_entry.'">',
            $row->doc_entry,
            $row->doc_num,
            $row->item_code,
            $row->item_name,
            $row->product_no,
            $row->product_name,
            $row->planned_qty,
            $actionbutton
        ];
    }

    /* =======================
       COUNT QUERY (MATCHING)
    ======================= */
    // $recordsFiltered = $this->db->query("
    //     SELECT COUNT(*) AS cnt
    //     FROM production_order p
    //     $where  
    // ")->row()->cnt;

        $recordsFiltered = $this->db->query("
            SELECT COUNT(DISTINCT p.doc_entry) AS cnt
            FROM production_order p
            $where
        ")->row()->cnt;

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $recordsFiltered,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    ]);
}


public function filteredProductionList()
{
    $id = $this->input->post('id');

    /* ✅ Correct Query Builder usage */
    $result = $this->db
        ->where('id', $id)
        ->get('production_order')
        ->row();

    if (!$result) {
        echo json_encode([
            "draw" => 0,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => []
        ]);
        return;
    }

    /* 🔁 Reuse DataTable method with product_name filter */
    $this->getProductOrderList($result->product_name);
}


public function Process()
{
    $selected_docs = $this->input->post('check_value'); 

    if (!empty($selected_docs)) {
       
        $data['items'] = $this->sales_model->getItemsByDocEntries($selected_docs);

        // Load the view and pass the items
        $this->load->view('Transaction/itemsForPlanned', $data);
    } else {
       
        $this->session->set_flashdata('error', 'Please select at least one record.');
        redirect('Transaction/productionOrder'); 
    }
}


    
}
?>