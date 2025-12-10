<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BomRest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database(); 
        $this->load->helper(['url', 'security']);
    }

    public function save() {
        header('Content-Type: application/json');
        $this->db->trans_begin();

        try {
            // 🔐 Validate API Token
            $CI =& get_instance();
            $valid_token = $CI->config->item('api_token');

            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
                throw new Exception("Unauthorized access. Missing Bearer token.", 401);
            }

          
            $token = trim(str_replace('Bearer ', '', $authHeader));
            if ($token !== $valid_token) {
                throw new Exception("Invalid API token.", 401);
            }

            // 📥 Read JSON input
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!isset($data['BOMHeader']) || !isset($data['BOMItems'])) {
                throw new Exception("Invalid JSON structure", 400);
            }

            $header = $data['BOMHeader'];
            $items  = $data['BOMItems'];

            // 📝 Prepare header data
            $headerData = [
                'ProductCode'             => $header['ProductCode'],
                'ProductName'             => $header['ProductName'],
                'ProductQuantity'         => $header['ProductQuantity'],
                'BOMType'                 => $header['BOMType'],
                'ProductionStdCost'       => $header['ProductionStdCost'],
                'PlannedAvgProductionSize'=> $header['PlannedAvgProductionSize'],
                'ProductWharehouse'       => $header['ProductWharehouse'],
                'ProductProject'          => $header['ProductProject'],
                'ProductPriceListId'      => $header['ProductPriceListId'],
                'ProductPriceListName'    => $header['ProductPriceListName'],
                'DistributionRule'        => $header['DistributionRule'],
                'UpdatedAt'               => date('Y-m-d H:i:s')
            ];


            $headerRow = $this->db->get_where('BOMHeader', [
                                'ProductCode' => $header['ProductCode']
                            ])->row();


            // 🔄 Insert / Update logic
           // if ($header['DocumentMode'] === 'U') {

            if ($headerRow) {
               
                $this->db->where('ProductCode', $header['ProductCode']);
                $this->db->update('BOMHeader', $headerData);
/*
                $headerRow = $this->db->get_where('BOMHeader', [
                    'ProductCode' => $header['ProductCode']
                ])->row();

                if (!$headerRow) {
                    throw new Exception("BOMHeader not found for update", 404);
                }*/

                $headerId = $headerRow->BOMHeaderId;

                // Remove old items
                $this->db->delete('BOMItems', ['BOMHeaderId' => $header['ProductCode']]);

            } else {
                // Insert new header
                $headerData['CreatedAt'] = date('Y-m-d H:i:s');
                $this->db->insert('BOMHeader', $headerData);
                $headerId = $this->db->insert_id();
            }

            // 💾 Insert new items
            foreach ($items as $item) {

                $item['BOMHeaderId'] = $header['ProductCode'];
                $item['CreatedAt']   = date('Y-m-d H:i:s');
                $this->db->insert('BOMItems', $item);
            }

            // ✅ Commit transaction
            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Database error", 500);
            }
            $this->db->trans_commit();

            echo json_encode([
                'status' => 'success',
                'message' => 'BOM saved successfully',
                'BOMHeaderId' => $headerId
            ]);

        } catch (Exception $e) {
            $this->db->trans_rollback();
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
