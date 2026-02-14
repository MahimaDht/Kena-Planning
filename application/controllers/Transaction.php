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



    public function getSalesorderList()
{
    $data = array();

    $draw   = (int)$_POST['draw'];
    $start  = (int)$_POST['start'];
    $length = (int)$_POST['length'];
    $search = $_POST['search']['value'];

    $column_search = array('DocEntry','DocNum', 'DocDate', 'CardName');

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
    // $sql = "
    //     SELECT id, DocDate, DocNum, CardName
    //     FROM salesorderheader
    //     $where
    //     ORDER BY id
    //     OFFSET $start ROWS FETCH NEXT $length ROWS ONLY
    // ";

    $sql = "
    SELECT id, DocDate, DocNum, CardName, DocEntry From salesorderheader
    $where

    ORDER BY id DESC
    OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";

// echo $sql; die;



    $itemdata = $this->db->query($sql, FALSE); // unbuffered

    foreach ($itemdata->result() as $value) {

        $actionbutton =
            '<a href="'.base_url('Transaction/getItems/'.base64_encode($value->DocEntry)).'"
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
        "SELECT COUNT(*) AS total
            FROM  salesorderheader
            WHERE DocStatus = 'O'"
    )->row()->total;

    // Filtered records
    $filteredRecords = $this->db->query("SELECT COUNT(*) AS total FROM  salesorderheader $where"  
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


    public function getItems($doc_entry){

        if($this->session->userdata('user_login_access')!=false)
        {
            $doc_entry=base64_decode($doc_entry);

            $data['salesItems']=$this->sales_model->getSalesitems($doc_entry);

            $data['sales_id']=$doc_entry;
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
        $sales_line = $this->input->post('sales_line');
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
            'salesitemLine' => $sales_line,
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
        redirect('Transaction/preview/'.base64_encode($sales_line).'/'.base64_encode($doc_entry));
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
         $sales_line = $this->input->post('sales_line');
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
            'salesitemLine' => $sales_line,
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
        redirect('Transaction/preview/'.base64_encode($sales_line).'/'.base64_encode($doc_entry));
        }
   }

  
    public function preview($sales_line,$doc_entry)
    {
        $sales_line=base64_decode($sales_line);

        $doc_entry = base64_decode($doc_entry);
       
        $process_header=$this->db->query("select * from sales_process_header where doc_entry='$doc_entry' and salesitemLine='$sales_line'")->row();
        $data['sales_line']=$process_header->sales_item_id;
        $data['process_header']=$process_header;
        $data['process']=$this->sales_model->getAssignprocess($process_header->id);
        $data['salesdata']=$this->sales_model->getSalesitems($doc_entry,$sales_line);

        $this->load->view('transaction/preview',$data);

    }

    public function updatePreviewAndPrint($sales_line=null,$doc_entry=null)
    {

         $sales_item_id=$this->input->post('sales_item_id');
         $sales_header=$this->input->post('sales_header');
        $sales_line = $sales_line ?? $this->input->post('salesitem_line');
        $doc_entry = $doc_entry ?? $this->input->post('doc_entry');


        // $id=$this->input->post('id');
        // $dataupdate=array('is_submit'=>'Y');
        // $this->db->where('id',$id);
        // $this->db->update('sales_process_header',$dataupdate);

         $process_header=$this->db->query("select * from sales_process_header where doc_entry='$doc_entry' and salesitemLine='$sales_line'")->row();

        
        $this->data['sales_item_id']=$process_header->sales_item_id;
        $this->data['process_header']=$process_header;
        $this->data['process']=$this->sales_model->getAssignprocess($process_header->id);
       // print_r($this->data['process']); die;
      $this->data['salesdata']=$this->sales_model->getSalesitems($doc_entry,$sales_line);

         $this->load->library('Pdf');

        $this->load->view('transaction/assignProcessPdf',$this->data);

         ob_clean();
        $this->pdf->Output('test.pdf', 'I');

       // redirect('Transaction/assignProcessPrint');

    }





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

public function UnPlannedSO()
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
        $data['printing_machines'] = $this->db->query("SELECT * FROM machines WHERE is_deleted='N' and printing_machine='Y'")->result();
     
        $this->load->view('transaction/unPlannedSO',$data);
    }
    else{

        redirect(base_url(),'refresh');
    }
}





public function getUnPlanningList()
{
    $draw   = intval($this->input->post("draw"));
    $start  = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));

    $qty = $this->input->post('qty');
    // $product_type  = $this->input->post('product_type');
    // $product_size  = $this->input->post('product_size');
    // $product_gauge = $this->input->post('product_gauge');

    $search = $_POST['search']['value'] ?? '';

    $where = " WHERE h.DocStatus = 'O' and i.LineStatus!='C' and i.OpenQty>0 and (i.DHTMulti='Yes' ) and i.is_planned='N'";
    if ($qty) {
        $where .= " AND i.OpenQty = " . $this->db->escape($qty);
    }

   

    if (!empty($search)) {
        $search = $this->db->escape_like_str($search);
        $where .= " AND (h.DocNum LIKE '%$search%' OR i.ItemCode LIKE '%$search%' OR i.Dscription LIKE '%$search%' OR h.CardName LIKE '%$search%')";
    }

    // $sql = "SELECT DISTINCT
    //            i.LineNum,
    //            i.ItemCode,
    //            i.OpenQty,
    //            h.DocNum,
    //            tm.u_ait_prod_size,
    //            tm.u_ait_guage,
    //            tm.u_product_type,
    //            i.BaseEntry,
    //            i.Dscription
    //     FROM salesorderitems i
    //     INNER JOIN salesorderheader h ON i.BaseEntry = h.DocEntry
    //     INNER JOIN item_master tm ON i.ItemCode = tm.item_code
    //     $where
    //     ORDER BY tm.u_product_type,tm.u_ait_prod_size,tm.u_ait_guage,i.LineNum";

    $sql="SELECT 
       i.LineNum,
       i.is_process_assign,
       i.ItemCode,
       h.cardName,
       i.OpenQty,
       h.DocNum,
       h.DocDate,
       DATEDIFF(day, i.TracingDate, GETDATE()) AS tracing_days,
       tm.u_ait_prod_size,
       tm.u_ait_guage,
       tm.u_product_type,
       i.DocEntry,
       b.Preference,
	   b.ItemName,
       b.itemNo as rawMaterialNo,
       b.UPS,
       i.DHTInsidePrinting,
       i.DHTMulti,
       i.Dscription
FROM salesorderitems i
INNER JOIN salesorderheader h 
       ON i.DocEntry = h.DocEntry
INNER JOIN item_master tm 
       ON i.ItemCode = tm.item_code
INNER JOIN BOMItems b 
       ON i.ItemCode = b.BOMHeaderId
       AND b.Preference = '1'
       $where
ORDER BY 
       tm.u_product_type,
       tm.u_ait_prod_size,
       tm.u_ait_guage, i.OpenQty DESC,
       i.LineNum";
    

   // echo $sql; die;

    $sql1=$sql;

//     $countSql = "SELECT COUNT(*) AS cnt FROM ($sql1) AS t";

//    // echo $countSql; die;
//     $totalRecords = $this->db->query($countSql)->row()->cnt;

    if ($length != -1) {
        $sql .= " OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
    }

    $query = $this->db->query($sql)->result();
    //echo $this->db->last_query(); die;

    $data = [];

    $x=1;
    foreach ($query as $row) {
        $planseq = "<select class='form-control planseq-select' id='planseq_" . $x . "' name='planseq[]' onchange='checkValidation(this)' 
                    data-size='{$row->u_ait_prod_size}' 
                    data-gauge='{$row->u_ait_guage}' 
                    data-type='{$row->u_product_type}' 
                    data-qty='{$row->OpenQty}' data-ups='{$row->UPS}' data-rawMaterialNo='{$row->rawMaterialNo}'>
                    <option value=''>Select</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                </select>";
    
        $data[] = [
           
            $planseq,
            
            $row->DocEntry . '<input type="hidden" name="doc_entry[]" value="' . $row->DocEntry . '"><input type="hidden" name="line_num[]" value="' . $row->LineNum . '">',
          
            $row->DocNum,
            $row->DocDate,
            $row->cardName . ($row->is_process_assign == 'Y' ? '<br><a href="'.base_url('Transaction/updatePreviewAndPrint/'.$row->LineNum.'/'.$row->DocEntry).'" class="btn btn-sm btn-success">Color Slip</a>' : ''),
            $row->ItemCode,
            $row->Dscription,
             $row->ItemName,
            $row->OpenQty,
            '',
            $row->DHTInsidePrinting,
            '',
            $row->tracing_days . '<input type="hidden" name="tracing_days[]" value="' . $row->tracing_days . '">',
            '<input type="text" class="form-control" name="machine_name[]" id="rowMachineName_'.$x.'" readonly>
            <input type="hidden" name="machineid[]" id="rowMachine_'.$x.'">'
           
           
        ];
        $x++;
    }

    echo json_encode([
        "draw"            => $draw,
        "recordsTotal"    => $this->db->query($sql1)->num_rows(),
        "recordsFiltered" => $this->db->query($sql1)->num_rows(),
        "data"            => $data
    ]);
}

public function saveUnPlannedSO()
{
    if ($this->session->userdata('user_login_access') == false) {
        redirect(base_url(), 'refresh');
    }

    
    $planseq = $this->input->post('planseq');
    $doc_entry = $this->input->post('doc_entry');
    $line_num = $this->input->post('line_num');
    $tracing_days = $this->input->post('tracing_days');
    $machineid = $this->input->post('machineid');
    $usedups = $this->input->post('usedups');
    $unique_no = date('YmdHis') . rand(10, 99);

    if (!empty($planseq)) {
        // First, collect all DocEntry/LineNum pairs that will be inserted
        $entries_to_delete = [];
        foreach ($planseq as $key => $val) {
            if ($val != '' && !empty($machineid[$key])) {
                $entries_to_delete[] = [
                    'DocEntry' => $doc_entry[$key],
                    'LineNum' => $line_num[$key]
                ];
            }
        }

        // Delete existing draft records for these DocEntry/LineNum combinations
        if (!empty($entries_to_delete)) {
            foreach ($entries_to_delete as $entry) {
                $this->db->where('DocEntry', $entry['DocEntry']);
                $this->db->where('LineNum', $entry['LineNum']);
                $this->db->where('is_planned', 'N');
                $this->db->delete('plannedSO');
            }
        }

        // Now insert new records
        $success = false;
        foreach ($planseq as $key => $val) {
            if ($val != '') {
                $data = [
                    'plannedId'   => $unique_no,
                    'planSeq'     => $val,
                    'DocEntry'    => $doc_entry[$key],
                    'LineNum'     => $line_num[$key],
                    'tracingDays' => $tracing_days[$key],
                    'machine'     => $machineid[$key],
                    'no_print'         => isset($usedups[$key]) ? $usedups[$key] : 1, // Store UPS value
                    'is_planned'  => 'N',  // Temporary/draft state
                    'createdBy'   => $this->session->userdata('user_login_id'),
                    'createdAt'   => date('Y-m-d H:i:s')
                ];
                if ($this->db->insert('plannedSO', $data)) {
                    $success = true;
                }
            }
        }

        if ($success) {
            // Redirect directly to plannedSO page (no save message)
            redirect('Transaction/plannedSO/'.base64_encode($unique_no));
        } else {
            $this->session->set_flashdata('error', 'No valid data to save');
            redirect('Transaction/unPlannedSO');
        }
    } else {
        $this->session->set_flashdata('error', 'No data selected');
        redirect('Transaction/unPlannedSO');
    }
}


public function plannedSO($plannedId)
{
    if ($this->session->userdata('user_login_access') == false) {
        redirect(base_url(), 'refresh');
    }

    $plannedId=base64_decode($plannedId);

    $sql="SELECT 
            a.*,
            b.ItemCode,
            b.Dscription,
            b.OpenQty,
            b.DHTMulti,
            c.machine_name,
            d.UPS,
            d.itemName as raw_material,
            d.ItemNo as raw_material_code,
            h.CardName,
            h.DocNum
            
        FROM plannedSO a
        INNER JOIN salesorderitems b 
            ON a.DocEntry = b.DocEntry 
        AND a.LineNum = b.LineNum
        INNER JOIN salesorderheader h
            ON a.DocEntry = h.DocEntry
        INNER JOIN machines c 
            ON a.machine = c.code
        INNER JOIN BOMItems d 
            ON b.ItemCode = d.BOMHeaderId
        AND d.Preference = '1'
        WHERE a.plannedId = '$plannedId'
        AND a.is_planned = 'N' order by a.planSeq ASC";

    $data['plannedSO']=$this->db->query($sql)->result();
    $data['printing_machines'] = $this->db->query("SELECT * FROM machines WHERE is_deleted='N' and printing_machine='Y'")->result();
     
    $this->load->view('transaction/plannedSO',$data);
}
public function savePlanItems()
{
    if ($this->session->userdata('user_login_access') == false) {
        redirect(base_url(), 'refresh');
    }

    $selectedItems = $this->input->post('item');
    $insertedIds = [];

    if (!empty($selectedItems)) {
        foreach ($selectedItems as $item) {
            $parts = explode('|', $item);
            if (count($parts) === 4) {
                $insertData = [
                    'doc_entry'    => $parts[0],
                    'itemcode'     => $parts[1],
                    'productcode'  => $parts[2],
                    'qty'          => $parts[3],
                    'created_by'   => $this->session->userdata('user_login_id'),
                    'created_at'   => date('Y-m-d H:i:s')
                ];
                $this->db->insert('planningProcess', $insertData);
                $insertedIds[] = $this->db->insert_id();
            }
        }
    }

    if (!empty($insertedIds)) {
      
        $this->session->set_flashdata('success', 'Successfully Saved');
        $encodedIds = base64_encode(implode(',', $insertedIds));
        redirect('transaction/createNewPlanning?ids=' . $encodedIds);
    } else {
        $this->session->set_flashdata('error', 'No items selected');
         redirect('transaction/productionOrder');
    }
}

// public function createNewPlanningold()
// {
   
//     if ($this->session->userdata('user_login_access') != false) {
//         $selectedItems = $this->input->post('item');
//         if (empty($selectedItems)) {
//              $this->session->set_flashdata("error", "No items selected");   
//             redirect('transaction/Process');
//              return;
//         }

//         $parsedItems = [];
//         foreach ($selectedItems as $item) {
//             $parts = explode('|', $item);
//             if (count($parts) === 2) {
//                 $parsedItems[] = [
//                     'doc_entry' => $parts[0],
//                     'item_code' => $parts[1]
//                 ];
//             }
//         }

//         if (empty($parsedItems)) {
//              $this->session->set_flashdata("error", "Invalid items selected");   
//             redirect('transaction/Process');
//              return;
//         }

//         $data['selectedItems'] = $this->sales_model->getItemsByDocEntryAndItemCode($parsedItems);
//         $data['machines'] = $this->master_model->getMachines();
//        // $data['urldata'] = $this->Access_model->get_menu_data();
         
//         $this->load->view('Transaction/addnewPlanning', $data);

//     } else {
//         redirect(base_url(), 'refresh');
//     }
// }
// public function createNewPlanning($encodedIds)
// {
//     $decodedIds = base64_decode($encodedIds);
//     $ids = explode(',', $decodedIds);
//     $data['selectedItems'] = $this->sales_model->getplanningProcessItemsByIds($ids);
//     $data['machines'] = $this->master_model->getMachines();
//     $this->load->view('Transaction/addnewPlanning', $data);
// }



public function save_plannedSO()
{
    if ($this->session->userdata('user_login_access') == false) {
        redirect(base_url(), 'refresh');
    }

    $plannedIds = $this->input->post('planned_id');
    $planDates  = $this->input->post('plan_date');   
    $docNums    = $this->input->post('doc_entry');
    $lineNums   = $this->input->post('line_num');
    $planSeqs   = $this->input->post('planSeq');
    $machine_ids = $this->input->post('machine_id');

    if (!empty($plannedIds)) {
        $this->db->trans_begin();

        foreach ($plannedIds as $index => $id) {
            $docNum   = isset($docNums[$index]) ? $docNums[$index] : null;
            $lineNum  = isset($lineNums[$index]) ? $lineNums[$index] : null;
            
            // Get the sequence for this item and lookup the shared date for that group
            $seq      = isset($planSeqs[$index]) ? trim($planSeqs[$index]) : null;
            $machine_id = isset($machine_ids[$seq]) ? $machine_ids[$seq] : null;
            $planDate = ($seq !== null && isset($planDates[$seq])) ? $planDates[$seq] : null;

            if ($planDate && $id && $machine_id) {
                $updateData = [
                    'planning_date' => $planDate,
                    'Is_planned'    => 'Y',
                    'updatedAt'     => date('Y-m-d H:i:s'),
                    'updatedBy'     => $this->session->userdata('user_name'),
                    'machine'       => $machine_id
                ];

                $this->db->where('id', $id);
                $this->db->where('DocEntry', $docNum);
                $this->db->where('LineNum', $lineNum);
                $this->db->update('plannedSO', $updateData);

                // 🔹 Update Sales Order Items
                $this->db->where('DocEntry', $docNum);
                $this->db->where('LineNum', $lineNum);
                $this->db->update('salesorderitems', ['is_planned' => 'Y']);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Planning failed. Database transaction rolled back.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Planning updated successfully.');
        }

    } else {
        $this->session->set_flashdata('error', 'No items found to save.');
    }

    redirect('transaction/unPlannedSO');
}






}
?>