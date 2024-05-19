<?php
require('../../fpdf/fpdf.php');
require('../../helper/connect.php');

session_start();

// Fetch daily sales data
$date = date('Y-m-d'); // Current date in YYYY-MM-DD format
$startOfDay = $date . ' 00:00:00';
$endOfDay = $date . ' 23:59:59';

$query = "SELECT * FROM sale WHERE date BETWEEN '$startOfDay' AND '$endOfDay'";


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
        // Title
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(0, 2, 'CRT Minimart Terminal Report', 0, 1, 'C');
        $this->Ln(1);
        // Date and time
        $this->SetFont('Arial', '', 5);
        $this->Cell(0, 2, 'Date: ' .$_SESSION['$selected_date'], 0, 1, 'C');
        $this->Cell(0, 2, 'Time: ' . date('H:i:s'), 0, 1, 'C');
        // Generated by
        $this->Cell(0, 2, 'Report Generated by: Admin', 0, 1, 'C');
        $this->Ln(2);
    }

    // Product report
    function ProductReport()
    {
        global $connection;
        $this->SetFont('Arial', 'B', 5);
        $this->Cell(0, 2, 'Product Report:', 0, 1, 'C');
        // Fetch product data
        $query = "SELECT * FROM product";
        $result = mysqli_query($connection, $query);
        $count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $count++;
            $this->SetFont('Arial', 'B', 5);
            $this->Cell(30, 2, "Product $count", 0, 1, 'R');
            $this->SetFont('Arial', '', 5);
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Product Code: {$row['code']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Name: {$row['name']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Category: {$row['category']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Price: {$row['price']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Stock: {$row['current_stock']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Physical Count: {$row['physical_count']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Delivery: {$row['delivery']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Transfer: {$row['transfer']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Wasteges: {$row['wasteges']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Pull Out: {$row['pull_out']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Returns: {$row['returns']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Variance: {$row['variance']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Description: {$row['description']}", 1, 1, 'C');
            $this->SetX(($this->w - 30) / 2); 
            $this->Cell(30, 2, "Date Added: {$row['date_added']}", 1, 1, 'C');
            $this->Ln(4);
        }
        $this->Ln(2);
    }

    // Thank you message
    function ThankYouMessage()
    {
        $this->SetFont('Arial', 'I', 4);
        $this->Cell(0, 2, 'Thank you for your hard work!', 0, 1, 'C');
        $this->Cell(0, 2, 'For inquiries, contact: +956-143-4976', 0, 1, 'C');
        $this->Ln(1);
    }
}

// Create a new PDF instance
$pdf = new PDF('P','mm',array(83, 55)); // Adjusted width and height for vertical orientation
$pdf->AddPage();

// Add sections to the PDF
$pdf->ProductReport();
$pdf->ThankYouMessage();

// Output the PDF
$pdf->Output('I', 'Daily_Terminal_Report_' . $date . '.pdf');
?>
