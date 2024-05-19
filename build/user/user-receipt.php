<?php
require('../../fpdf/fpdf.php');
require('../../helper/connect.php');

session_start();

date_default_timezone_set('Asia/Manila');

// Fetch the latest sales data
$query = "SELECT * FROM sale ORDER BY date DESC LIMIT 1";
$result = mysqli_query($connection, $query);
$sales = [];
if ($result) {
    $latestSale = mysqli_fetch_assoc($result);
    $latestDate = $latestSale['date'];
    $query = "SELECT * FROM sale WHERE date = '$latestDate'";
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $sales[] = $row;
    }
}

class PDF extends FPDF
{
    function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->SetMargins(2, 2, 2); // Set margins to zero
    }

    // Page header
    function Header()
    {
        $date = date('Y-m-d');
        // Title
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(0, 2, 'CRT Minimart Sales Report', 0, 1, 'C');
        $this->Ln(1);
        // Date and time
        $this->SetFont('Arial', '', 5);
        $this->Cell(0, 2, 'Date: ' .$date, 0, 1, 'C');
        $this->Cell(0, 2, 'Time: ' . date('H:i:s'), 0, 1, 'C');
        // Generated by
        $this->Cell(0, 2, 'Report Generated by: Admin', 0, 1, 'C');
        $this->Ln(2);
    }

    // Table with latest sales
    function SalesTable($header, $data)
    {
        // Column widths
        $w = array(15, 13, 8, 15);
        // Header
        $this->SetFont('Arial', 'B', 5);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->Ln();
        // Data
        $this->SetFont('Arial', '', 5);
        foreach($data as $row)
        {
            $this->Cell($w[0],3,$row['name'],1);
            $this->Cell($w[1],3,$row['category'],1);
            $this->Cell($w[2],3,$row['sold'],1);
            $this->Cell($w[3],3, '$' . number_format($row['cash_received'], 2),1);
            $this->Ln();
        }
    }

    // Thank you message
    function ThankYouMessage()
    {
        $this->SetFont('Arial', 'I', 4);
        $this->Cell(0, 2, 'Thank you for your the Purchase!', 0, 1, 'C');
        $this->Cell(0, 2, 'For inquiries, contact: +956-143-4976', 0, 1, 'C');
        $this->Ln(1);
    }
}

// Create a new PDF instance
$pdf = new PDF('P','mm',array(83, 55)); // Adjusted width and height for vertical orientation
$pdf->AddPage();

// Define table header
$header = array('Product Name', 'Category', 'Qty', 'Total Price');

// Add table with sales data to the PDF
$pdf->SalesTable($header, $sales);

// Add thank you message
$pdf->ThankYouMessage();

// Output the PDF
$pdf->Output('I', 'Daily_Terminal_Report_' . date('Y-m-d') . '.pdf');
?>