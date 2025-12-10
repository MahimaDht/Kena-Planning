<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Calcutta");
        $this->load->model('dashboard_model');
        $this->load->model('employee_model');
        $this->load->model('login_model');
        $this->load->model('settings_model');
        //$this->load->model('company_model');
    }

    public function index()
    {     if ($this->session->userdata('user_login_access') == 1)
        {
            redirect(base_url() . 'dashboard');
        }

        $this->load->view('user_view');
    }
    public function test_email()
    {
        $this->load->view('email/email_template');
    }

       public function fetch_data() {
        $postData = $this->input->post();
        $data = $this->getUsers($postData);
        echo json_encode($data);
    }
public function getUsers($postData) {
    $draw = $postData['draw'];
    $row = $postData['start']; // Offset
    $rowperpage = $postData['length']; // Rows per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // Sort order
    $searchValue = $postData['search']['value']; // Search value

    // Filtering condition
    $searchQuery = "";
    if ($searchValue != '') {
        $searchQuery = " AND (a.DocNo LIKE '%".$searchValue."%' 
                            OR c.name LIKE '%".$searchValue."%'
                            OR i.name LIKE '%".$searchValue."%'
                            OR a.QtnNo LIKE '%".$searchValue."%')";
    }

    // Get total number of records (without limit)
    $sqlCount = "SELECT COUNT(*) as totalRecords 
                 FROM OPOR a 
                 INNER JOIN POR1 b ON a.DocYear = b.DocYear AND a.Doc = b.Doc 
                     AND a.DocNo = b.DocNo AND a.ChangeLog = b.ChangeLog
                 LEFT JOIN OITM i ON b.ItemCode = i.Code
                 INNER JOIN OVND c ON a.Vendor = c.code  
                 INNER JOIN (SELECT DocYear, Doc, DocNo, MAX(ChangeLog) as MaxChangeLog 
                             FROM OPOR GROUP BY DocYear, Doc, DocNo) as gg  
                     ON a.DocYear = gg.DocYear AND a.Doc = gg.Doc 
                     AND a.DocNo = gg.DocNo AND a.ChangeLog = gg.MaxChangeLog
                 INNER JOIN (SELECT a.code, a.name FROM Unit a 
                             INNER JOIN userunit b ON a.code = b.UnitCode AND b.Usercode = 'edp') u 
                     ON a.Unit = u.code
                 WHERE a.DocYear = '24-25' $searchQuery";

    $queryCount = $this->db->query($sqlCount);
    $totalRecords = $queryCount->row()->totalRecords;

    // Get paginated records
    $sql = "SELECT a.DocNo as PONo, a.DocDt as PODate, a.Vendor, c.name as VendorName,
                   a.Unit, a.Type as DocType, a.Ref as Reference, a.QtnNo, a.QtnDt as QtnDate,
                   b.ItemCode, i.name as ItemName, b.POPQty, b.PUOM, b.POSQty, b.SUOM,
                   a.ChangeLog, a.DocStatus, a.PreparedBy, ISNULL(a.AuthBy, '') as AuthBy
            FROM OPOR a 
            INNER JOIN POR1 b ON a.DocYear = b.DocYear AND a.Doc = b.Doc 
                AND a.DocNo = b.DocNo AND a.ChangeLog = b.ChangeLog
            LEFT JOIN OITM i ON b.ItemCode = i.Code
            INNER JOIN OVND c ON a.Vendor = c.code  
            INNER JOIN (SELECT DocYear, Doc, DocNo, MAX(ChangeLog) as MaxChangeLog 
                        FROM OPOR GROUP BY DocYear, Doc, DocNo) as gg  
                ON a.DocYear = gg.DocYear AND a.Doc = gg.Doc 
                AND a.DocNo = gg.DocNo AND a.ChangeLog = gg.MaxChangeLog
            INNER JOIN (SELECT a.code, a.name FROM Unit a 
                        INNER JOIN userunit b ON a.code = b.UnitCode AND b.Usercode = 'edp') u 
                ON a.Unit = u.code
            WHERE a.DocYear = '24-25' $searchQuery
            ORDER BY $columnName $columnSortOrder
            OFFSET $row ROWS FETCH NEXT $rowperpage ROWS ONLY";

    log_message('error', "Paginated SQL Query: " . $sql); // Debug SQL Query

    $query = $this->db->query($sql);
    $data = $query->result();

    // Prepare response
    $response = array(
        "draw" => intval($draw),
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data" => $data
    );

    return $response;
}



}
?>