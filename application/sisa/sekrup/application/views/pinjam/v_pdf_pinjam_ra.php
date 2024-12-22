<?php
date_default_timezone_set("Asia/Jakarta");
$this->load->library('fpdf');
/*echo "<pre>";
print_r($dataitem);
echo "</pre>";*/
$id=$dataitem['id'];
$nopinjam=$dataitem['nopinjam'];
$tglaju=$dataitem['tglaju'];
$idadmin=$dataitem['idadmin'];
$tglin=$dataitem['tglin'];
//$timein=$dataitem['timein'];
$tglot=$dataitem['tglot'];
//$timeot=$dataitem['timeot'];

$ket=$dataitem['ket'];
$item=$dataitem['nmruang'];
//$kapasi=$dataitem['kapasi'];
$timetgl=$dataitem['timestamp'];



class PDF extends FPDF
{
var $angle=0;

//constructor
// Page header
function Header()
{
    $this->AddFont('Tahomabd','','Tahoma-Bold.php');//font tahoma bold
    $this->SetFont('Tahomabd','',50);
   

    if ( $this->PageNo() == 1 ) {

    // Arial bold 15
    $this->AddFont('Tahoma','','tahoma.php');//font tahoma non bold
    $this->AddFont('Tahomabd','','Tahoma-Bold.php');//font tahoma bold

    $this->SetFont('Tahomabd','',12);
    // Move to the right
    $this->SetXY(10,7);
    $this->Cell(190,5,'e-FORMULIR PEMINJAMAN RUANG KANTOR',0,2,'C');
/*    $this->SetX(40);
    $this->SetFont('Tahomabd','',22);
    $this->Cell(160,10,'DINAS KOMUNIKASI DAN INFORMATIKA',0,2,'C');
    $this->SetFont('Tahoma','',9);  
    $this->SetX(40);
    $this->Cell(160,5,'Jl. Menteri Supeno I Nomor 2 Semarang Telepon 024-8319140, Faximile 024-8319328 Kode Pos 50243',0,2,'C');
    $this->SetX(40);
    $this->Cell(160,4,'Surat Elektronik : diskominfo@jatengprov.go.id, Laman : htpp://diskominfo.jatengprov.go.id',0,2,'C');*/
   /* $this->SetLineWidth(1);    
    $this->Line(10, 13, 200, 13);  */
    }
    
}

    
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();

$pdf->AddPage("L","A5");
//Jika 4 aktif

$pdf->SetMargins(30,20,30);

/*nota dinas*/
$pdf->SetXY(10,12);


$pdf->Cell(190,4,'Nomor : '.$nopinjam,0,1,'C');
$pdf->SetFont('Tahoma','',12);
$pdf->SetX(10);
$pdf->Cell(190,6,'Dengan ini memberikan peminjaman Ruangan dengan rincian sebagai berikut :',0,1,'L');

$pdf->SetXY(12,$pdf->GetY());
$pdf->Cell(60,5,'Seksi (User Admin)',0,0,'L');
$pdf->Cell(5,5,':',0,0,'L');
$dhp=getAdmin($idadmin);
$pdf->Cell(60,5,$dhp->nm_bid."(".$dhp->username.")",0,1,'L');

$pdf->SetXY(12,$pdf->GetY());
$pdf->Cell(60,5,'Tanggal/Waktu Peminjaman',0,1,'L');

$pdf->SetXY(30,$pdf->GetY());
$pdf->Cell(42,5,'Awal',0,0,'C');
$pdf->Cell(5,5,':',0,0,'L');
$pdf->Cell(60,5,date('d-m-Y',$tglin),0,1,'L');

$pdf->Cell(42,5,'Akhir',0,0,'C');
$pdf->Cell(5,5,':',0,0,'L');
$pdf->Cell(60,5,date('d-m-Y',$tglot),0,1,'L');

$pdf->SetXY(12,$pdf->GetY());
$pdf->Cell(60,5,'Item Peminjaman',0,0,'L');
$pdf->Cell(5,5,':',0,0,'L');
$pdf->Cell(60,5,$item,0,1,'L');

$pdf->SetXY(12,$pdf->GetY());
$pdf->Cell(60,5,'Keterangan',0,0,'L');
$pdf->Cell(5,5,':',0,0,'L');
$pdf->MultiCell(125,5,$ket,0,'J');


$pdf->SetXY(12,$pdf->GetY());
$pdf->MultiCell(190,5,'Segala resiko yang timbul saat peminjaman menjadi tanggung Jawab Peminjam',0,'J');
$pdf->SetFont('Tahomabd','',7);
$kht=$pdf->GetY();
$pdf->SetXY(15,$kht+1);

$pdf->Cell(2,4,'-',0,0,'C');

$pdf->MultiCell(115,3,'Segala Perawatan Yang ditimbulkan saat peminjaman dan kebersihannya menjadi tanggung Jawab Peminjam',0,'L');
$pdf->SetX(15);
$pdf->Cell(2,4,'-',0,0,'C');
$pdf->Cell(60,4,'Resiko Biaya BBM ditangung oleh Peminjam Mobil',0,2,'L');
$pdf->SetX(15);
$pdf->Cell(2,4,'-',0,0,'C');
$pdf->Cell(60,4,'Mencucikan Mobil Apabila Telah Selesai Digunakan',0,2,'L');
$pdf->SetXY(15,$pdf->GetY());

$pdf->SetFont('Tahoma','',12);
$pdf->SetXY(130,$kht+1);

$hp1=explode(" ", $timetgl);


$pdf->Cell(60,4,'Semarang, '.$hp1[0],0,2,'C');
$pdf->Cell(60,4,'Telah Disetujui,',0,2,'C');
$pdf->Cell(60,4,'Kasubbag Umpeg',0,2,'C');
//UNtuk QR
$pdf->Image(base_url().'harta/pinjam/qr/not.png',$pdf->GetX()+20,$pdf->GetY(),17,17);
$pdf->SetXY(130,$pdf->GetY()+18);
$pdf->Cell(60,4,'Dr. GALIH WIBOWO,S.Sos,MA',0,2,'C');
$pdf->SetAutoPageBreak(false);





$pdf->Output('I',str_replace("/","_",$nopinjam).'.pdf');

?>