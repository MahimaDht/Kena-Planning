<?php
class ProductionWoApi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url','security']);
    }

    public function production_wo_receive() {
    try {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new Exception('Invalid request method.', 405);
        }

        $reqjson = file_get_contents('php://input');

        if (empty($reqjson)) {
            throw new Exception("Empty JSON received", 400);
        }

        $decoded = json_decode($reqjson, true);


        if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON: " . json_last_error_msg(), 400);
            }

        // if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
        //     throw new Exception("Invalid JSON: " . json_last_error_msg(), 400);
        // }

        // check json key exists
        // if (!isset($decoded['json'])) {
        //     throw new Exception("Missing 'json' key in request body", 400);
        // }

        $this->db->trans_begin();

        // store only inner json
        $this->db->insert('production_wo', [
            'json' => $reqjson,
            'created_at'=>date('Y-m-d H:i:s')
        ]);

        $id = $this->db->insert_id();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Database error', 500);
        }

        $this->db->trans_commit();

        // $this->output
        //     ->set_content_type('application/json')
        //     ->set_output(json_encode([
        //         'success' => true,
        //         'message' => 'Data Saved',
        //         'id' => $id
        //     ]));

         echo "Data Saved";

    } catch (Exception $e) {
        $this->db->trans_rollback();

        // $this->output
        //     ->set_status_header($e->getCode() ?: 500)
        //     ->set_content_type('application/json')
        //     ->set_output(json_encode([
        //         'success' => false,
        //         'message' => $e->getMessage()
        //     ]));


        http_response_code($e->getCode() ?: 500);
        echo $e->getMessage();
    }
}

}
?>
