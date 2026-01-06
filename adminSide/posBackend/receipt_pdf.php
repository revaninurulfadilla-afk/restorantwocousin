<?php
require('fpdf186/fpdf.php');
require_once "../config.php";

if (!isset($_GET['id_pesanan'])) {
    die("ID pesanan tidak ditemukan!");
}

$id_pesanan = (int)$_GET['id_pesanan'];

// ambil data pesanan + pembayaran
$query = mysqli_query($link, "
    SELECT p.*, py.metode, py.total_bayar, py.uang_diterima, py.kembalian, py.status_bayar, py.tanggal_bayar
    FROM pesanan p
    LEFT JOIN pembayaran py ON p.id_pesanan = py.id_pesanan
    WHERE p.id_pesanan='$id_pesanan'
");

$data = mysqli_fetch_assoc($query);

if (!$data) die("Pesanan tidak ditemukan!");

$detail = mysqli_query($link, "
    SELECT dp.*, m.nama_menu
    FROM detail_pesanan dp
    JOIN menu m ON dp.id_menu = m.id_menu
    WHERE dp.id_pesanan='$id_pesanan'
");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

$pdf->Cell(0,10,"TWO COUSIN Restaurant",0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,"Receipt Pesanan #".$id_pesanan,0,1,'C');
$pdf->Ln(5);

$pdf->Cell(0,8,"Tanggal: ".$data['tanggal_bayar'],0,1);
$pdf->Cell(0,8,"Metode: ".strtoupper($data['metode']),0,1);
$pdf->Cell(0,8,"Status: ".strtoupper($data['status_bayar']),0,1);
$pdf->Cell(0,8,"Bayar: Rp ".number_format($data['total_bayar'],0,',','.'),0,1);
$pdf->Cell(0,8,"Kembali: Rp ".number_format($data['kembalian'],0,',','.'),0,1);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(70,10,"Menu",1);
$pdf->Cell(20,10,"Qty",1);
$pdf->Cell(40,10,"Harga",1);
$pdf->Cell(50,10,"Subtotal",1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);
while($row = mysqli_fetch_assoc($detail)){
    $pdf->Cell(70,10,$row['nama_menu'],1);
    $pdf->Cell(20,10,$row['qty'],1,0,'C');
    $pdf->Cell(40,10,"Rp ".number_format($row['harga_satuan'],0,',','.'),1);
    $pdf->Cell(50,10,"Rp ".number_format($row['subtotal'],0,',','.'),1);
    $pdf->Ln();
}

while($row = mysqli_fetch_assoc($detail)){
    $pdf->Cell(70,10,$row['nama_menu'],1);
    $pdf->Cell(20,10,$row['qty'],1,0,'C');
    $pdf->Cell(40,10,"Rp ".number_format($row['harga_satuan'],0,',','.'),1);
    $pdf->Cell(50,10,"Rp ".number_format($row['subtotal'],0,',','.'),1);
    $pdf->Ln();
}

$pdf->SetFont('Arial','B',12);

$pdf->Cell(130,10,"TOTAL",1,0,'R');

$pdf->Cell(50,10,"Rp ".number_format($data['total_harga'],0,',','.'),1,1);

$pdf->Output("I","Receipt-Pesanan-$id_pesanan.pdf");
?>
