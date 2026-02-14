<?php

class SalesOrderApI extends CI_Controller {
  public function __construct() {
    parent::__construct();
     $this->load->database(); 
        $this->load->helper(['url', 'security']);
  }




public function sales_order_receive() {
    try {
        // Only allow POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new Exception('Invalid request method.', 405); // 405 Method Not Allowed
        }

        $CI =& get_instance();
        $valid_token = $CI->config->item('api_token');

        // $authHeader = $this->input->get_request_header('Authorization', TRUE);
        // if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
        //     throw new Exception("Unauthorized access. Missing Bearer token.", 401);
        // }

        // $token = trim(str_replace('Bearer ', '', $authHeader));
        // if ($token !== $valid_token) {
        //     throw new Exception("Invalid API token.", 401);
        // }


        $reqjson=file_get_contents('php://input');

       // log_message("error",$reqjson);
        // Decode JSON
        $mainArr = json_decode($reqjson);
        if ($mainArr === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON decoding error: " . json_last_error_msg(), 400);
        }

        $this->db->trans_begin();

        $sohead = $mainArr->SalesOrderHeader;
        $soitem = $mainArr->SalesOrderItems;

        // Prepare header data
        $soheaddata = [
            'DocumentMode' => $sohead->DocumentMode,
            'DocEntry' => $sohead->DocEntry,
            'DocNum' => $sohead->DocNum,
            'DocType' => $sohead->DocType,
            'CANCELED' => $sohead->CANCELED,
            'DocStatus' => $sohead->DocStatus,
            'ObjType' => $sohead->ObjType,
            'DocDate' => $sohead->DocDate,
            'DocDueDate' => $sohead->DocDueDate,
            'CardCode' => $sohead->CardCode,
            'CardName' => $sohead->CardName,
            'Address' => $sohead->Address,
            'NumAtCard' => $sohead->NumAtCard,
            'VatPercent' => $sohead->VatPercent,
            'VatSum' => $sohead->VatSum,
            'DiscPrcnt' => $sohead->DiscPrcnt,
            'DiscSum' => $sohead->DiscSum,
            'DocCur' => $sohead->DocCur,
            'DocTotal' => $sohead->DocTotal,
            'GrosProfit' => $sohead->GrosProfit,
            'Comments' => $sohead->Comments,
            'JrnlMemo' => $sohead->JrnlMemo,
            'DocTime' => $sohead->DocTime,
            'SlpCode' => $sohead->SlpCode,
            'Series' => $sohead->Series,
            'TaxDate' => $sohead->TaxDate,
            'RoundDif' => $sohead->RoundDif,
            'ReqDate' => $sohead->ReqDate,
            'CancelDate' => $sohead->CancelDate,
            'Project' => $sohead->Project,
            'VatDate' => $sohead->VatDate,
            'BaseAmnt' => $sohead->BaseAmnt,
            'PaidSum' => $sohead->PaidSum,
            'Branch' => $sohead->Branch,
            'totalexpns' => $sohead->totalexpns,
            'groupnum' => $sohead->groupnum,
            'PayToCode' => $sohead->PayToCode,
            'ShipToCode' => $sohead->ShipToCode,
            'BillToAddress' => $sohead->BillToAddress,
            'ShipToToAddress' => $sohead->ShipToToAddress,
            'TrnspName' => $sohead->TrnspName,
            'SlpName' => $sohead->SlpName,
            'TrnspCode' => $sohead->TrnspCode,
            'OwnerCode' => $sohead->OwnerCode,
            'OwnerName' => $sohead->OwnerName,
            'U_TechRemarks' => $sohead->U_TechRemarks,
            'U_TransportPayment' => $sohead->U_TransportPayment,
            'U_OrdrNo' => $sohead->U_OrdrNo,
            'U_OrderDate' => $sohead->U_OrderDate,
            'FreightAmount' => $sohead->FreightAmount,
            'Advance' => $sohead->Advance,
            'AttachmentPath' => $sohead->AttachmentPath,
        ];

        $headerRow = $this->db->get_where('salesorderheader', [
                                'DocEntry' => $sohead->DocEntry
                            ])->row();

       // $socheck=$this->db->query("")

       // if ($sohead->DocumentMode === 'A') {
        if (!$headerRow) {
            // Insert new header
            $this->db->insert('salesorderheader', $soheaddata);
            $head_id = $this->db->insert_id();
        }

        // elseif ($sohead->DocumentMode === 'U') {

         else {
            // Update existing header
            $this->db->where('DocEntry', $sohead->DocEntry)->update('salesorderheader', $soheaddata);
            $head = $this->db->get_where('salesorderheader', ['DocEntry' => $sohead->DocEntry])->row();

            if (!$head) {
                throw new Exception('Header not found for update', 404);
            }
            $head_id = $head->id;

            // Delete old items
            $this->db->where('header_id', $head_id)->delete('salesorderitems');
        } 

        // Insert items
        foreach ($soitem as $item) {
            $soitemdata = [
                'header_id' => $head_id,
                'LineNum' => $item->LineNum,
                'DocEntry'=>$item->DocEntry,
                'TargetType' => $item->TargetType,
                'TrgetEntry' => $item->TrgetEntry,
                'BaseRef' => $item->BaseRef,
                'BaseType' => $item->BaseType,
                'BaseEntry' => $item->BaseEntry,
                'BaseLine' => $item->BaseLine,
                'LineStatus' => $item->LineStatus,
                'ItemCode' => $item->ItemCode,
                'Dscription' => $item->Dscription,
                'Quantity' => $item->Quantity,
                'OpenQty' => $item->OpenQty,
                'Price' => $item->Price,
                'Currency' => $item->Currency,
                'Rate' => $item->Rate,
                'Line_DiscPrcnt' => $item->Line_DiscPrcnt,
                'LineTotal' => $item->LineTotal,
                'WhsCode' => $item->WhsCode,
                'Line_SlpCode' => $item->Line_SlpCode,
                'AcctCode' => $item->AcctCode,
                'TaxStatus' => $item->TaxStatus,
                'PriceBefDi' => $item->PriceBefDi,
                'Line_DocDate' => $item->Line_DocDate,
                'OcrCode' => $item->OcrCode,
                'Line_Project' => $item->Line_Project,
                'VatPrcnt' => $item->VatPrcnt,
                'VatGroup' => $item->VatGroup,
                'PriceAfVAT' => $item->PriceAfVAT,
                'Line_VatSum' => $item->Line_VatSum,
                'GrssProfit' => $item->GrssProfit,
                'VisOrder' => $item->VisOrder,
                'TaxCode' => $item->TaxCode,
                'TaxType' => $item->TaxType,
                'unitMsr' => $item->unitMsr,
                'GTotal' => $item->GTotal,
                'U_QtyinPack' => $item->U_QtyinPack,
                'U_DescPackNo' => $item->U_DescPackNo,
                'SO_KenaCode' => $item->SO_KenaCode,
                'Custome_POName' => $item->Custome_POName,
                'Zone' => $item->Zone,
                'U_REMARK' => $item->U_REMARK,
                'TracingDate' => $item->TracingDate,
                'OpprID' => $item->OpprID,
                'U_NosOfSample' => $item->U_NosOfSample,
                'AdditionalRemarks' => $item->AdditionalRemarks,
                'U_Ait_WhseOpt' => $item->U_Ait_WhseOpt,
                'MAdate' => $item->MAdate,
                'PrintingType' => $item->PrintingType,
                'Priority' => $item->Priority,
                'PG1Col1' => $item->PG1Col1,
                'PG1Col2' => $item->PG1Col2,
                'PG1Col3' => $item->PG1Col3,
                'PG1Col4' => $item->PG1Col4,
                'PocketOption' => $item->PocketOption,
                'NosOfSample' => $item->NosOfSample,
                'ClipOption' => $item->ClipOption,
                'VCoption' => $item->VCoption,
                'PrintedStock' => $item->PrintedStock,
                'U_TtlImpression' => $item->U_TtlImpression,
                'WhseOption' => $item->WhseOption,
                'ProductTypeDHT' => $item->ProductTypeDHT,
                'RivetingClipsDHT' => $item->RivetingClipsDHT,
                'RoutingProcessDHT' => $item->RoutingProcessDHT,
                'UdfDHTOne' => $item->UdfDHTOne,
                'UdfDHTTwo' => $item->UdfDHTTwo,
                'UdfDHThree' => $item->UdfDHThree,
                'DHTMulti' => $item->DHTMulti,
                'DHTUv' => $item->DHTUv,
                'DHTInsidePrinting' => $item->DHTInsidePrinting
            ];
            $this->db->insert('salesorderitems', $soitemdata);
        }

        // Commit transaction
        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Error in creation/updation', 500);
        }
        $this->db->trans_commit();

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true, 'message' => 'Success']));

    } catch (Exception $e) {
        // Rollback in case of any error
        $this->db->trans_rollback();
        $status = $e->getCode() ?: 500;
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => false, 'message' => $e->getMessage()]));
    }
}






}
?>
