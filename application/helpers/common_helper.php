<?php
defined('BASEPATH') or exit('No direct script access allowed');

// In your common_helper.php file
function getUserDetails($user_id = '', $find_type = '')
{
    // Initialize $find_type with an empty string if not provided
    if (empty($find_type)) {
        $find_type = '*';
    }

    // Get the CodeIgniter instance to access the database
    $CI =& get_instance();

    // Build the SQL query using Query Builder to avoid SQL injection
    $CI->db->select($find_type);
    $CI->db->where('id', $user_id);
    $query = $CI->db->get('employee');

    // Check if the query was successful and return the result
    if ($query->num_rows() > 0) {
        return $query->row();
    }

    // If no user is found, return false or handle the error accordingly
    return false;
}
  function getTimeandUsername($datetime, $em_id)
    {
        $CI = get_instance();
        $result = '';
        if ($datetime != '') {
            $result .= date('d/m/y h:i A', strtotime($datetime));
        }
        if ($em_id != '') {
            if ($result != '') {
                $result .= '<br>';
            }
            $query = $CI->db->query("SELECT concat(first_name,' ',last_name) as fullname FROM employee WHERE em_id = '" . $em_id . "'");
            $row = $query->row();
            if ($row) {
                $result .= $row->fullname;
            }
        }

        return $result;
    }

// In your common_helper.php file
function getDhtConfiguration()
{
    // Get the CodeIgniter instance to access the database
    $CI =& get_instance();

    // Build the SQL query using Query Builder to avoid SQL injection
    $CI->db->select('*');
    $query = $CI->db->get('dht_configuration');
    // Check if the query was successful and return the result
    if ($query->num_rows() > 0) {
        return $query->row();
    }

    // If no user is found, return false or handle the error accordingly
    return false;
}


if (!function_exists('get_active_user_by_code_or_all')) {
    function get_solutions_one_active_user_by_code_or_all($code = null) {
        $CI =& get_instance();
        $CI->load->database();

        $CI->db->select('code, name, E_Mail');
        $CI->db->from('UserMst');
        $CI->db->where('DeActivate', 'N');

        if (!empty($code)) {
            $CI->db->where('code', $code);
            $query = $CI->db->get();

            return ($query->num_rows() > 0) ? $query->row_array() : null;
        } else {
            $query = $CI->db->get();

            return ($query->num_rows() > 0) ? $query->result_array() : [];
        }
    }
}
// In your common_helper.php file
function getSupplierHeadDetailsFromJWO($docNum)
{
    // Get the CodeIgniter instance to access the database
    $CI =& get_instance();

    // Build the SQL query using Query Builder to avoid SQL injection
    $CI->db->select('*');
    $CI->db->where('CONCAT( DocYear ,Doc ,CAST(DocNo AS VARCHAR)) =  ', $docNum);
    $query = $CI->db->get('OJWPO');
    // Check if the query was successful and return the result
    if ($query->num_rows() > 0) {
        return $query->row();
    }

    // If no user is found, return false or handle the error accordingly
    return false;
}

// In your common_helper.php file
function getSupplierHeadDetailsFromPO($docNum)
{
    // Get the CodeIgniter instance to access the database
    $CI =& get_instance();

    // Build the SQL query using Query Builder to avoid SQL injection
    $CI->db->select('*');
    $CI->db->where('CONCAT( DocYear ,Doc ,CAST(DocNo AS VARCHAR)) =  ', $docNum);
    $query = $CI->db->get('OPOR');
    // Check if the query was successful and return the result
    if ($query->num_rows() > 0) {
        return $query->row();
    }

    // If no user is found, return false or handle the error accordingly
    return false;
}

// Get basic employee information
function getBasicEmployeeInfo($employee_id='')
{
    // Get the CodeIgniter instance to access the database
    $CI =& get_instance();
    if($employee_id=='')
    $employee_id = $CI->session->userdata('user_login_id');

    // Build the SQL query using Query Builder to avoid SQL injection
    $CI->db->select('*');
    $CI->db->where('em_id', $employee_id);
    $query = $CI->db->get('employee');

    // Check if the query was successful and return the result
    if ($query->num_rows() > 0) {
        return $query->row();
    }

    // If no employee is found, return false
    return false;
}
// Get company information
function getCompanyInfo()
{
    // Get the CodeIgniter instance to access the database
    $CI =& get_instance();

    // Build the SQL query using Query Builder to avoid SQL injection
    $CI->db->select('*');
    $query = $CI->db->get('company');

    // Check if the query was successful and return the result
    if ($query->num_rows() > 0) {
        return $query->row();
    }

    // If no company is found, return false
    return false;
}
// Get company information
function getCompanyUnitInfo()
{
    // Get the CodeIgniter instance to access the database
    $CI =& get_instance();

    // Build the SQL query using Query Builder to avoid SQL injection
    $CI->db->select('*');
    $query = $CI->db->get('Unit');

    // Check if the query was successful and return the result
    if ($query->num_rows() > 0) {
        return $query->result();
    }

    // If no company is found, return false
    return false;
}

if (!function_exists('getFinancialYear')) {
    /**
     * Retrieves financial year periods from the FASPeriod table.
     *
     * @param string $status (Optional) Filter financial years by status.
     * @return array|false An array of financial year objects or false if no data is found.
     */
    function getFinancialYear($status = '') {
        // Get the CodeIgniter instance to access the database
        $CI = &get_instance();

        // Build the SQL query using Query Builder to avoid SQL injection
        $CI->db->select('*');

        if (!empty($status)) {
            $CI->db->where('Status', $status);
        }
        $CI->db->order_by('FinFromDt', 'DESC'); // Add order by

        $query = $CI->db->get('FASPeriod');

        // Check if the query was successful and return the result
        if ($query->num_rows() > 0) {
            return $query->result();
        }

        // If no data is found, return false
        return false;
    }



function IND_money_format($num) {
    $explrestunits = "";
    $decimal = "";
    
    // Check if the number contains a decimal point
    if (strpos($num, ".") !== false) {
        $parts = explode(".", $num); // Split into integer and decimal parts
        $num = $parts[0];
        $decimal = "." . $parts[1];
        if (strlen($decimal) == 2) {
            $decimal .= "0";
        }
    } else {
        // If no decimal point, append .00
        $decimal = ".00";
    }

    if(strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for($i = 0; $i < sizeof($expunit); $i++) {
            if($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ","; // Convert first group into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    return $thecash . $decimal; // Append the decimal part if it exists
}
function generate_vendor_code($vendor_name)
{
    $prefix = strtoupper(substr($vendor_name, 0, 1));
    $CI = &get_instance();

    // Safer MSSQL query using TRY_CAST
    $sql = "
        SELECT TOP 1 code
        FROM OVND
        WHERE code LIKE ? AND TRY_CAST(SUBSTRING(code, 2, LEN(code)) AS INT) IS NOT NULL
        ORDER BY TRY_CAST(SUBSTRING(code, 2, LEN(code)) AS INT) DESC
    ";

    $query = $CI->db->query($sql, array($prefix . '%'));

    if ($query->num_rows() > 0) {
        $last_code = $query->row()->code;
        $number = (int)substr($last_code, 1);
        $new_number = $number + 1;
    } else {
        $new_number = 1;
    }

    // Add leading zeros like Z023, A007, etc.
    $new_vendor_code = $prefix . str_pad($new_number, 3, '0', STR_PAD_LEFT);

    return $new_vendor_code;
}

function formatNumberASIntegra($value) {
    if (!is_numeric($value)) {
        return $value; // Return original if not a valid number
    }

    $num = (float) $value;

    if ($num === 0.0) {
        return '0';
    }

    // If whole number, show 2 decimals
    if (floor($num) == $num) {
        return number_format($num, 2, '.', '');
    }

    // Round to 3 decimals and remove trailing zeros
    $rounded = rtrim(rtrim(number_format($num, 3, '.', ''), '0'), '.');

    // Ensure at least 2 decimal places (e.g., 1.1 -> 1.10)
    if (preg_match('/^\d+\.\d$/', $rounded)) {
        $rounded .= '0';
    }

    return $rounded;
}


function emailtextReplaceVariable(){
 
    return $data = ["{{supplier_name}}"   => 'For Supplier name',
            "{{document_number}}" => 'For Document Number',
            "{{document_date}}" => 'For Document Date',
            "{{contact_name}}" => 'Supplier Contact Person name',
            "{{contact_number}}" => 'Supplier Contact Person number',
            "{{contact_email}}" => 'Supplier Contact Person email',
            "{{reason}}" => 'For any reason',
            "{{user_name}}" => 'User name',
            "{{password}}" => 'Password'];


}

function convertToIndianCurrency($number) {
    if (!is_numeric($number)) {
        return "Error: Amount in number appears to be incorrect. Please check.";
    }

    if ($number > 999999999.99) {
        return "Oops!!! The amount is too big to convert";
    }

    $no = floor($number);
    $point = round(($number - $no) * 100); // Paise

    $words = array(
        0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen',
        17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen',
        20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
        60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
    );

    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');

    $str = [];
    $i = 0;
    while ($no > 0) {
        $divider = ($i == 1) ? 10 : 100;
        $number = $no % $divider;
        $no = (int)($no / $divider);

        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            if ($number < 21) {
                $str[] = $words[$number] . ' ' . $digits[$i];
            } else {
                $str[] = $words[(int)($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$i];
            }
        } else {
            $str[] = null;
        }
        $i++;
    }

    $rupees = implode(' ', array_reverse(array_filter($str)));
    $paise = ($point > 0) ? (($point < 21) ? $words[$point] : $words[10 * floor($point / 10)] . ' ' . $words[$point % 10]) : '';

    // Combine rupees and paise
    if ($rupees == '' && $paise == '') {
        return "Zero only";
    } elseif ($rupees == '') {
        return "Paise " . $paise . " only";
    } elseif ($paise == '') {
        return "Rupees " . $rupees . " only";
    } else {
        return "Rupees " . $rupees . " and Paise " . $paise . " only";
    }
}

    
}