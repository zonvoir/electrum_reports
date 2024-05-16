<?php
require('./vendor/autoload.php');
require_once('database.php'); // Include the TCPDF library
require_once('./vendor/tecnickcom/tcpdf/tcpdf.php');
$database = new Database();
$conn = $database->getConnection();
if ($conn) {
    // Create a new TCPDF instance
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Mr. Jeewantha Amarasinghe');
    $pdf->SetTitle('Electruments');
    $pdf->SetSubject('Electruments System Report');

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(20, 15, 20);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('times', '', 11);

    // add a page
    $pdf->AddPage();

    $query = "SELECT layout_name FROM layouts where id = :layoutId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':layoutId', $_POST['layout'], PDO::PARAM_INT);

    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // set some text to print
       // $html = '<h1 style="text-align:center">' . $result['layout_name'] . '</h1>';
    }


    $queryC = "SELECT certificate_name FROM certificate_types where id = :certificateId";
    $stmtC = $conn->prepare($queryC);
    $stmtC->bindParam(':certificateId', $_POST['certificate_id'], PDO::PARAM_INT);

    if ($stmtC->execute()) {
        $resultC = $stmtC->fetch(PDO::FETCH_ASSOC);
        // set some text to print
        $html = '<h1 style="text-align:center">' . $resultC['certificate_name'] . '</h1>';
    }




    $indexNo = $_POST['index_no'];
    $certificateNo = $_POST['certificate_no'];
    $date = $_POST['date'];
    $instrument_received_date = $_POST['instrument_received_date'];
    $equipment_used = $_POST['equipment_used'];
    $due_date = $_POST['due_date'];
    $date_place_calibration = $_POST['date_place_calibration'];

    // Add the table to the existing HTML content
    $html .= '<table style="border-top: 1px solid black; border-bottom: 1px solid black;" cellpadding="5">';
    $html .= '<tr style="padding-top: 10px; padding-bottom: 10px;">'; // Adjust the padding values as needed
    $html .= '<td><b>Index No:</b> ' . $indexNo . '</td>';
    $html .= '<td><b>Certificate No:</b> ' . $certificateNo . '</td>';
    $html .= '<td><b>Date:</b> ' . $date . '</td>';
    $html .= '</tr>';
    $html .= '</table>';


    if (isset($_POST['customer'])) {
        $customer = $_POST['customer'];

        $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
        $html .= '<tr>';
        $html .= '<td style="width:20%"><b>Customer:</b></td>';
        $html .= '<td  style="width:80%" colspan="2">' . $customer . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
    } else {
        // Handle the case when 'customer' is not set in $_POST
        $html .= '<p>No customer data available</p>';
    }

    if (isset($_POST['layout_profile'])) {

        if (isset($_POST['layout_profile_id'])) {
            $layoutProfileId = $_POST['layout_profile_id'];
        }

        if (isset($_POST['layout_profile_make'])) {
            $layoutProfileMake = $_POST['layout_profile_make'];
        }

        if (isset($_POST['layout_profile_model'])) {
            $layoutProfileModel = $_POST['layout_profile_model'];
        }

        if (isset($_POST['layout_profile_sn'])) {
            $layoutProfileSN = $_POST['layout_profile_sn'];
        }

        $profileID = $_POST['layout_profile'];
        $query = "SELECT profile_name FROM layout_profile where id = :layoutId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':layoutId', $profileID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // set some text to print
            $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
            $html .= '<tr>';
            $html .= '<td style="width:20%"><b>Unit Under Test:</b></td>';
            $html .= '<td style="width:80%" colspan="2">';
            $html .= '<table border="1" cellpadding="2">'; // This should be <table> instead of <tabel>
            $html .= '<tr><td colspan="2">' . $result['profile_name'] . '</td><td><b>ID:</b> ' . $layoutProfileId . '</td></tr>';
            $html .= '<tr><td><b>Make: </b>' . $layoutProfileMake . '</td><td><b>Model: </b>' . $layoutProfileModel . '</td><td><b>S/N: </b>' . $layoutProfileSN . '</td></tr>';
            $html .= '</table>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }
    } else {
        $html .= '<p>No profile data available</p>';
    }

    if (isset($_POST['method_of_tests'])) {
        $methodOfTests = $_POST['method_of_tests'];

        $html .= '<table style="border-bottom: 1px solid black;width:100%" cellpadding="8">';
        $html .= '<tr>';
        $html .= '<td style="width:20%"><b>Method of Test:</b></td>';
        $html .= '<td style="width:80%" colspan="2">' . $methodOfTests . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
    } else {
        // Handle the case when 'customer' is not set in $_POST
        $html .= '<p>No Method of Test data available</p>';
    }

    if (isset($_POST['traceability'])) {
        $traceability = $_POST['traceability'];

        $html .= '<table style="border-bottom: 1px solid black;"  cellpadding="8">';
        $html .= '<tr>';
        $html .= '<td style="width:20%"><b>Standard And Traceability:</b></td>';
        $html .= '<td style="width:80%" colspan="2">' . $traceability . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
    } else {
        // Handle the case when 'customer' is not set in $_POST
        $html .= '<p>No Standard And Traceability data available</p>';
    }

    if (isset($_POST['ambient'])) {
        $ambient = $_POST['ambient'];

        $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
        $html .= '<tr>';
        $html .= '<td style="width:20%"><b>Ambient Conditions:</b></td>';
        $html .= '<td style="width:80%" colspan="2">' . $ambient . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
    } else {
        // Handle the case when 'customer' is not set in $_POST
        $html .= '<p>No Ambient Conditions data available</p>';
    }

    if (isset($_POST['traceability'])) {
        $pi = $_POST['pi'];

        $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
        $html .= '<tr>';
        $html .= '<td  style="width:20%"><b>Preliminary Investigations:</b></td>';
        $html .= '<td  style="width:80%" colspan="2">' . $pi . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
    } else {
        // Handle the case when 'customer' is not set in $_POST
        $html .= '<p>No Preliminary Investigations data available</p>';
    }


    $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
    $html .= '<tr>';
    $html .= '<td style="width:20%"><b>Results: </b></td>';
    $html .= '<td  style="width:80%" colspan="2"></td>';
    $html .= '</tr>';
    $html .= '</table>';


    $html .= '<table>';
    $query = "SELECT * FROM layouts WHERE id = :layoutId";
    $statement = $conn->prepare($query);
    $statement->bindParam(':layoutId', $_POST['layout'], PDO::PARAM_INT);
    $statement->execute();
    $layout =  $statement->fetchAll(PDO::FETCH_ASSOC);

    $layoutTemplateID = $layout[0]['layout_template_id'];
    //load headings
    $query1 = "SELECT * FROM heddings WHERE parent_id = 0 AND layout_template_id = :layoutTemplateId order by parent_id";
    $statement = $conn->prepare($query1);
    $statement->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
    $statement->execute();
    $headings =  $statement->fetchAll(PDO::FETCH_ASSOC);

    $query2 = "SELECT * FROM heddings WHERE level = 2 AND layout_template_id = :layoutTemplateId order by parent_id";
    $statement2 = $conn->prepare($query2);
    $statement2->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
    $statement2->execute();
    $headings2 =  $statement2->fetchAll(PDO::FETCH_ASSOC);

    $query3 = "SELECT * FROM heddings WHERE level = 3 AND layout_template_id = :layoutTemplateId order by parent_id";
    $statement3 = $conn->prepare($query3);
    $statement3->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
    $statement3->execute();
    $headings3 =  $statement3->fetchAll(PDO::FETCH_ASSOC);

    //get max row id from values table 
    $maxquery = "SELECT MAX(row_id) max_id FROM value WHERE layout_template_id = :layoutTemplateId";
    $statementMax = $conn->prepare($maxquery);
    $statementMax->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
    $statementMax->execute();
    $result =  $statementMax->fetch(PDO::FETCH_ASSOC);

    $val = 0;
    if ($result) {
        $val =  $result['max_id'];
    }


    if ($headings) {


        $html .= '<tr>';
        foreach ($headings as $heading) {
            $html .= '<th style="border:1px solid #7f7f7f;"  colspan="' . $heading['colspan'] . '">' . $heading['title'] . '</th>';
        }
        $html .= '</tr>';

        if ($headings2) {
            $html .= '<tr>';
            foreach ($headings2 as $heading2) {
                $html .= '<th style="border:1px solid #7f7f7f;" colspan="' . $heading2['colspan'] . '">' . $heading2['title'] . '</th>';
            }
            $html .= '</tr>';
        }

        if ($headings3) {
            $html .= '<tr>';
            foreach ($headings3 as $heading3) {
                $html .= '<th style="border:1px solid #7f7f7f;" >' . $heading3['title'] . $heading3['id'] . '</th>';
            }
            $html .= '</tr>';
        }

        //get values from values table 

        for ($i = 1; $i <= $val; $i++) {
            $valuequery = "SELECT * FROM value WHERE layout_template_id = :layoutTemplateId AND row_id = :rowId";
            $statementValue = $conn->prepare($valuequery);
            $statementValue->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
            $statementValue->bindParam(':rowId', $i, PDO::PARAM_INT);
            $statementValue->execute();
            $resultValues =  $statementValue->fetchAll(PDO::FETCH_ASSOC);

            $html .= '<tr>';
            foreach ($resultValues as $resultValue) {
                $html .= '<td style="border:1px solid #7f7f7f;" >' . $resultValue['value'] . ' </td>';
            }

            $html .= '</tr>';
        }

        if (
            count($headings) > 0 && count($headings2) === 0
        ) {
            $html .= '<tr>';
            foreach ($headings as $heading) {
                $html .= '<td style="border:1px solid #7f7f7f;"  colspan="' . $heading['colspan'] . '" ><input type="text" name="heading_id[]" data-id="' . $heading['id'] . '" value=""> </td>';
            }
            $html .= '</tr>';
        }

        if (
            count($headings2) > 0 && count($headings3) === 0
        ) {
            $html .= '<tr>';
            foreach ($headings2 as $heading2) {
                $html .= '<td style="border:1px solid #7f7f7f;"  colspan="' . $heading2['colspan'] . '"><input type="text" class="input_2" name="heading_id[]" data-id="' . $heading2['id'] . '" value=""> </td>';
            }
            $html .= '</tr>';
        }

        if ($headings3) {
            $html .= '<tr>';
            foreach ($headings3 as $heading3) {
                $html .= '<td style="border:1px solid #7f7f7f;"  colspan="' . $heading3['colspan'] . '"><input type="text" class="input_3" name="heading_id[]" data-id="' . $heading3['id'] . '" value=""> </td>';
            }

            $html .= '</tr>';
        }
    }
    $html .= '</table>';


    $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
    $html .= '<tr>';
    $html .= '<td colspan="3">The reported expanded uncertainty of measurement is stated as the standard uncertainty of measurement multiplied by the coverage factorK2, which for a normal distribution corresponds to a coverage probability of approximately 95%</td>';
    $html .= '</tr>';
    $html .= '</table>';

    $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
    $html .= '<tr>';
    $html .= '<td style="width:40%"><b>Instrument Received Date : </b></td>';
    $html .= '<td  style="width:60%" colspan="2">'. $instrument_received_date.'</td>';
    $html .= '</tr>';
    $html .= '</table>';
  

    $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
    $html .= '<tr>';
    $html .= '<td style="width:40%"><b>Date and place of Calibration : </b></td>';
    $html .= '<td  style="width:60%" colspan="2">'. $date_place_calibration.'</td>';
    $html .= '</tr>';
    $html .= '</table>';

    $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
    $html .= '<tr>';
    $html .= '<td style="width:40%"><b>Due Date: </b></td>';
    $html .= '<td  style="width:60%" colspan="2">'. $due_date.'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td colspan="3" style="font-size:10px;">Note*:Due date stated above is the duedater equired by the  customer which details marked under Customer in this document.It does not reflect any suggestion or recommendation of Electruments calibration laboratory.</td>';
    $html .= '</tr>';
    $html .= '</table>';


    $html .= '<table style="border-bottom: 1px solid black;" cellpadding="30">';
    $html .= '<tr>';
    $html .= '<td style="width:10%"></td>';
    $html .= '<td style="width:40%; text-align:center;">................................<br>J . W . Amarasinghe<br>(Authorized Signatory)</td>';
    $html .= '<td  style="width:50%"></td>';
    $html .= '</tr>';
    $html .= '</table>';
    

    $html .= '<table style="border-bottom: 1px solid black;" cellpadding="8">';
    $html .= '<tr>';
    $html .= '<td colspan="3" style="font-size:10px;">Any holder of this document is advised that information contained here on reflects Electruments\' findings and results relate only to the item calibrated at the time of its intervention only and within the limits of customer\'s instructions. The organization\'s sole responsibility is to its customers only. Any unauthorized alteration, forgery, or falsification of the content or appearance of this document is unlawful and offenders may be prosecuted to the fullest extent of the law. This Document cannot be reproduced except in full.</td>';
    $html .= '</tr>';
    $html .= '</table>';




    // print a block of text using Write()
    $pdf->writeHTML(
        $html,
        true,
        false,
        true,
        false,
        ''
    );

    // ---------------------------------------------------------

    //Close and output PDF document
    $pdf->Output('example_002.pdf', 'I');
} else {
    echo "Database connection lost!";
}
