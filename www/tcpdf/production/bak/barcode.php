<?php
//============================================================+
// File name   : example_050.php
// Begin       : 2009-04-09
// Last Update : 2011-09-22
//
// Description : Example 050 for TCPDF class
//               2D Barcodes
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: 2D barcodes.
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Wirefly Design');
$pdf->SetTitle('Your tickets');
$pdf->SetSubject('');
$pdf->SetKeywords('PDF, ticket');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 050', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

// set font
$pdf->SetFont('helvetica', '', 11);

// add a page
$pdf->AddPage();

// print a message
//$txt = "You can also export 2D barcodes in other formats (PNG, SVG, HTML). Check the source code documentation of TCPDF2DBarcode class for further information.";
//$pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);

$pdf->SetFont('helvetica', '', 10);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

$tbl = <<<EOD

<table border="5" style="border-color:gray" cellpadding="10">
    <tr>
        <td width="80px" rowspan="4">Barcode</td>
        <td width="300px" height="99px" style="font-size:20;">Comlongon Rock 2013</td>
        <td width="120px"rowspan="4">Logo</td>
    </tr>
    <tr>
        <td style="height:33px" >Date time</td>
    </tr>
    <tr>
        <td style="height:33px" >Ticket type</td>
    </tr>
    <tr>
        <td style="height:66px" >Optional Extras</td>
    </tr>
</table>

EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// set style for barcode
$style = array(
    'border' => 2,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

$content = "dFj4hzk4n24b6suR" . rand(0,9999);

// QRCODE,L : QR-CODE Low error correction
//$pdf->write2DBarcode($content, 'QRCODE,L', 20, 30, 50, 50, $style, 'N');
//$pdf->Text(20, 25, 'QRCODE L');

// QRCODE,M : QR-CODE Medium error correction
//$pdf->write2DBarcode($content, 'QRCODE,M', 20, 90, 50, 50, $style, 'N');
//$pdf->Text(20, 85, 'QRCODE M');

// QRCODE,Q : QR-CODE Better error correction
$pdf->write2DBarcode($content, 'QRCODE,Q', 124, 62, 30, 30, $style, 'N');
//$pdf->Text(20, 145, 'QRCODE Q');

// QRCODE,H : QR-CODE Best error correction
//$pdf->write2DBarcode($content, 'QRCODE,H', 20, 210, 50, 50, $style, 'N');
//$pdf->Text(20, 205, 'QRCODE H');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('ticket.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
