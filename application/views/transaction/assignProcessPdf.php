
<?php

$grouped_data = [];
foreach ($process as $row) {
    $layout_id = $row->layout_id;
    $group_id = $row->group_id;
    $process_id = $row->process_id;
    $parameter_id = $row->parameter_id;

    $grouped_data[$layout_id]['layout_name'] = $row->layoutName;

    $grouped_data[$layout_id]['groups'][$group_id]['group_name'] = $row->group_name;


  if (!isset($grouped_data[$layout_id]['groups'][$group_id]['group_impression']) && isset($row->group_impression)) {

        $grouped_data[$layout_id]['groups'][$group_id]['group_impression'] = $row->group_impression;
    }

    $grouped_data[$layout_id]['groups'][$group_id]['processes'][$process_id]['process_name'] = $row->process_name;
    $grouped_data[$layout_id]['groups'][$group_id]['processes'][$process_id]['process_code'] = $row->process_code;

 // Store process impression if available
    if (isset($row->process_impression)) {
        $grouped_data[$layout_id]['groups'][$group_id]['processes'][$process_id]['process_impression'] = $row->process_impression;
    }


    if (!empty($parameter_id)) {
        $grouped_data[$layout_id]['groups'][$group_id]['processes'][$process_id]['parameters'][] = [
            'name' => $row->parameter_name,
            'value' => $row->parameter_value
        ];
    }
}



$this->pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$this->pdf->SetCreator(PDF_CREATOR);
$this->pdf->SetAuthor('Nicola Asuni');
$this->pdf->SetTitle($sales_item_id);
$this->pdf->SetSubject('TCPDF Tutorial');
$this->pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$this->pdf->SetHeaderData('', 0, '', '');

// set header and footer fonts
$this->pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
$this->pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

// set default monospaced font
$this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$this->pdf->SetMargins(5, 5, 5);
$this->pdf->SetHeaderMargin(0);
$this->pdf->SetFooterMargin(0);

// set auto page breaks
$this->pdf->SetAutoPageBreak(TRUE, 1);

// set image scale factor
$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// add a page
$this->pdf->AddPage('L');

// set font
$this->pdf->SetFont('helvetica', '', 8);




// Start HTML
$html = <<<EOF
<style>
    .detailsTable {
        width: 100%;
        border: 0.7px solid #2f2f2f;
        border-collapse: collapse; 
    }
    .detailsTable td {
        padding: 3px;
        border-collapse: collapse;
    }
    .detailsTable th,
    .detailsTable td {
        padding: 3px;
        border-top: 0.7px solid #2f2f2f;
        border-bottom: 0.7px solid #2f2f2f;
        border-left: none;
        border-right: none;
    }
</style>

<table class="detailsTable" cellpadding="6">
    <tr>
        <td><b>Date:</b> {$salesdata->DocDate}</td>
        <td></td>
        <td><b>So No:</b> {$salesdata->DocNum} / <b>Seq.No:</b> {$salesdata->header_id}</td>
    </tr>
</table>

<table class="detailsTable" cellpadding="5">
    <tr>
        <td><b>TERITORY:</b> {$salesdata->DocDate}</td>
        <td><b>CITY(GROUP): </b></td>
        <td><b>OPPR ID :</b></td>
    </tr>
    <tr>
        <td style="width:60%"><b>PARTY NAME:</b> {$salesdata->CardName}</td>
        <td style="width:40%"><b>QUANTITY: </b>{$salesdata->Quantity}</td>
    </tr>
    <tr>
        <td style="width:20%"><b>ITEM CODE: </b>{$salesdata->ItemCode}</td>
        <td style="width:40%"><b>ITEM DESCRIPTION:</b> {$salesdata->Dscription}</td>
        <td style="width:40%"><b>Customer PO Name:</b> {$salesdata->Custome_POName}</td>
    </tr>
</table>


<table style="border:1px solid black;" >
<tr><td><b>Additional Options: </b></td></tr>
<tr>
EOF;

$colCount = 0;

foreach ($grouped_data as $layout) {

    $layout_name = htmlspecialchars($layout['layout_name']);

    $html .= <<<EOF
    <td style="width:33.33%; vertical-align:top;">
        <h4 style=" margin-bottom:5px;">Layout: {$layout_name}</h4>
EOF;

    foreach ($layout['groups'] as $group) {
    	 // $group_name = htmlspecialchars($group['group_name']);
        // $html .= "<h5 style='color:#000; margin-left:10px; margin-bottom:4px;'>Group: {$group_name}</h5>";
        $html .= <<<EOF
        <ul style="margin-left:20px;">
EOF;

        foreach ($group['processes'] as $process) {
            $process_name = htmlspecialchars($process['process_name']);
            $process_code = htmlspecialchars($process['process_code']);
            $process_impression= htmlspecialchars($process['process_impression']);

            $html .= <<<EOF
            <li style=" margin-bottom:3px; padding:3px;">
                <strong>{$process_name}</strong> <span style="color:black;">({$process_code})</span>
EOF;

    if (!empty($process_impression)) {
        $html .= <<<EOF
        <span style="color:green; font-weight:bold;"> - Impression: {$process_impression}</span>
EOF;
    }

            if (!empty($process['parameters'])) {
                $html .= <<<EOF
                <ul style="margin-top:3px; margin-left:15px;">
EOF;
                foreach ($process['parameters'] as $param) {
                    $param_name  = htmlspecialchars($param['name']);
                    $param_value = htmlspecialchars($param['value']);

                    $html .= <<<EOF
                    <li><span style="color:black;">{$param_name}:</span> <strong style="color:#000;">{$param_value}</strong></li>
EOF;
                }
                $html .= <<<EOF
                </ul>
EOF;
            }

            $html .= <<<EOF
            </li>
EOF;
        }

        $html .= <<<EOF
        </ul>
EOF;
    }

    $html .= <<<EOF
    </td>
EOF;

    $colCount++;

    if ($colCount % 3 == 0 && $colCount < count($grouped_data)) {
        $html .= <<<EOF
</tr><tr>
EOF;
    }
}

if ($colCount % 3 != 0) {
    $remaining = 3 - ($colCount % 3);
    for ($i = 0; $i < $remaining; $i++) {
        $html .= <<<EOF
<td style="width:33.33%;"></td>
EOF;
    }
}

$html .= <<<EOF
</tr>
</table>
EOF;
$this->pdf->writeHTML($html, true, false, true, false, '');
$this->pdf->Ln(-3);

$new_design = (isset($process_header->new_design) && $process_header->new_design === 'Y') ? '☑ YES' : '☐ NO';
$new_design_text = isset($process_header->new_design_text) ? htmlspecialchars($process_header->new_design_text) : 'N/A';

$repeat_design = (isset($process_header->repeat_design) && $process_header->repeat_design === 'Y') ? '☑ YES' : '☐ NO';
$repeat_design_text = isset($process_header->repeat_design_text) ? htmlspecialchars($process_header->repeat_design_text) : 'N/A';

$correction = (isset($process_header->correction) && $process_header->correction === 'Y') ? '☑ YES' : '☐ NO';
$correction_text = isset($process_header->correction_text) ? htmlspecialchars($process_header->correction_text) : 'N/A';

$html2 = <<<EOF

<style>
    .detailsTable {
        width: 100%;
        border: 0.7px solid #2f2f2f;
        border-collapse: collapse; 
    }
    .detailsTable td {
        padding: 3px;
        border-collapse: collapse;
    }
    .detailsTable th,
    .detailsTable td {
        padding: 3px;
        border-top: 0.7px solid #2f2f2f;
        border-bottom: 0.7px solid #2f2f2f;
        border-left: none;
        border-right: none;
    }
</style>
<table  border="1" cellpadding="6"  width="100%">
    <tr>
        <td >
            <strong>New Design:</strong> {$new_design}<br>&nbsp;&nbsp;{$new_design_text}
        </td>
        <td >
            <strong>Repeat Design:</strong> {$repeat_design}<br>&nbsp;&nbsp;{$repeat_design_text}
        </td>
        <td >
            <strong>Correction:</strong> {$correction}<br>&nbsp;&nbsp;{$correction_text}
        </td>
    </tr>
</table>
<table class="detailsTable" border="1" cellpadding="6"  width="100%">
    <tr>
       <td style="width:60%"><strong>Remarks:</strong><br><br>&nbsp;&nbsp;</td>
       <td style="width:20%"><strong>Sign of Marketing Executive:</strong></td>
       <td style="width:20%"><strong>Checked by:</strong></td>
    </tr>
</table>
EOF;


$this->pdf->SetFont('dejavusans', '',8); 



$this->pdf->writeHTML($html2, true, false, true, false, '');

// Close and output PDF
$this->pdf->lastPage();
?>
