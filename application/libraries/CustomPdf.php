<?php


class CustomPDF extends FPDF
{

    function Header()
    {
        $this->SetTitle("PT. BEGAWAN POLOSORO", true);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 10, 'PT. BENGAWAN POLOSORO', 0, 1, 'C');
        $this->Ln(5);
        // To be implemented in your own inherited class
    }

    function Footer()
    {
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 5);
        // Page number
        $this->Cell(0, 0, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->GetFooterInfo();
        $this->Ln();
        $date = strftime("%e %B %Y", time());
        $this->Cell(0, 0, strftime($date), 0, 0, 'C');
        // To be implemented in your own inherited class
    }

    function SetFooterInfo($value)
    {
        $this->FooterInfo = $value;
    }

    function GetFooterInfo()
    {
        if ($this->FooterInfo != '') {

            $this->Ln();
            $this->Cell(0, 9, $this->FooterInfo, 0, 0, 'C');

        }

    }
}