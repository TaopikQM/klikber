<?php
date_default_timezone_set("Asia/Jakarta");
$this->load->library('fpdf');

// if (isset($riwayat_servis)) {
//     $idmobil = $riwayat_servis['idmobil'] ?? 'ID Mobil Tidak Ditemukan';
//     $nopol = $riwayat_servis['nopol'] ?? 'Nomor Polisi Tidak Ditemukan';
//     $nm_pemegang = $riwayat_servis['nm_pemegang'] ?? 'Nama Pemegang Tidak Ditemukan';
//     $jenis = $riwayat_servis['jenis'] ?? 'Jenis Mobil Tidak Ditemukan';
//     $nmerk = $riwayat_servis['nmerk'] ?? 'Merk Mobil Tidak Ditemukan';
//     $blnket = isset($riwayat_servis['blnket']) ? date('d-m-Y', strtotime($riwayat_servis['blnket'])) : 'Tanggal Tidak Ditemukan';
//     $ket = $riwayat_servis['ket'] ?? 'Keterangan Tidak Ditemukan';
// } else {
//     // Data tidak ada
//     $idmobil = 'ID Mobil Tidak Ditemukan';
//     $nopol = 'Nomor Polisi Tidak Ditemukan';
//     $nm_pemegang = 'Nama Pemegang Tidak Ditemukan';
//     $jenis = 'Jenis Mobil Tidak Ditemukan';
//     $nmerk = 'Merk Mobil Tidak Ditemukan';
//     $blnket = 'Tanggal Tidak Ditemukan';
//     $ket = 'Keterangan Tidak Ditemukan';
// }
?>
<?php foreach ($riwayat_servis as $key):
    $kilometer = $key->kilometer;
    $nm_pemegang = $key->nm_pemegang;
    $blnket = $key->blnket;
    $ket = $key->ket;
    $idmobil = $key->idmobil;
    $jenis = $key->jenis;
    $nopol = $key->nopol;
    $nmerk = $key->nmerk;
endforeach;
?>

<?php

class PDF extends FPDF
{
    var $angle=0;

    // Page header
    // function Header() {
    //     // Set font
    //     $this->SetFont('Arial', 'B', 12);
    //     // Move to the right
    //     $this->Cell(190, 5, 'SURAT PENGANTAR', 0, 2, 'C');
    //     // Line break
    //     $this->Ln(10);
    // }

    // function Header() {
    //     // Set font
    //     $this->SetFont('Arial', 'B', 12);
        
    //     // Move to the right and set the position for the text
    //     $this->Cell(190, 5, 'SURAT PENGANTAR', 0, 2, 'C');
        
    //     // Calculate the position for the underline
    //     $textWidth = $this->GetStringWidth('SURAT PENGANTAR');
    //     $x = (210 - $textWidth) / 2; // 210 is the width of an A4 page in portrait mode
    //     $y = $this->GetY(); // Current Y position
    
    //     // Draw the underline
    //     $this->Line($x, $y, $x + $textWidth, $y);
    //     $this->Ln(10);
    // }

    function Rotate($angle,$x=-1,$y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}
function RotatedText($x, $y, $txt, $angla)
{
    //Text rotated around its origin
    $this->Rotate($angla,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}

    function Header()
{
    $this->AddFont('Tahomabd','','Tahoma-Bold.php');//font tahoma bold
    $this->SetFont('Tahomabd','',50);
    // $this->SetTextColor(255,192,203);
    // $this->RotatedText(35,190,'P  R  E  V  I  E  W  -  S  P  T',45);
    // $this->RotatedText(15,175,'Diskominfo Prov. Jateng',45);

    if ( $this->PageNo() == 1 ) {
            // Add your stuff here
        
    // Logo
    $this->Image(base_url().'harta/morsip/assets/img/jateng.png',10,5,30,30);
    $this->SetTextColor(0,0,0);
    
    // Arial bold 15
    $this->AddFont('Tahoma','','tahoma.php');//font tahoma non bold
    $this->AddFont('Tahomabd','','Tahoma-Bold.php');//font tahoma bold

    $this->SetFont('Tahoma','',15);
    // Move to the right
    $this->SetXY(40,7);
    $this->Cell(160,7,'PEMERINTAH PROVINSI JAWA TENGAH',0,1,'C');
    $this->SetX(40);
    $this->SetFont('Tahomabd','',22);
    $this->Cell(160,10,'DINAS KOMUNIKASI DAN INFORMATIKA',0,2,'C');
    $this->SetFont('Tahoma','',9);  
    $this->SetX(40);
    $this->Cell(160,5,'Jl. Menteri Supeno I Nomor 2 Semarang Telepon 024-8319140, Faximile 024-8319328 Kode Pos 50243',0,2,'C');
    $this->SetX(40);
    $this->Cell(160,4,'Surat Elektronik : diskominfo@jatengprov.go.id, Laman : htpp://diskominfo.jatengprov.go.id',0,2,'C');
    $this->SetLineWidth(1);    
    $this->Line(10, 35, 205, 35);  
    }
    // akhir y 50
    // Garis Bawah Double
    //$this->Cell(190,1,'','B',1,'L');
    //$this->Cell(190,1,'','B',0,'L');
}

// function Footer()
//     {
//         $this->SetY(-25);
                         
//          $this->SetLineWidth(0.5);    
//          $this->Line(10, 320, 205, 320);
//         // //Arial italic 8
//          $this->SetFont('Tahoma','',10);
//         // // time created
//          //$this->Cell(160,5,'',1,0,'C');
//         //  $this->Image('./harta/morsip/qr/not.png',15,321,20,20);
//          $this->SetXY(40,320);
//          $this->MultiCell(160,5,"Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara".'.',0,'J');
//     }
    
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage("P","Legal");
$pdf->SetMargins(30,20,30);


$pdf->SetXY(10,40);
$pdf->SetFont('Tahomabd','',14);
$pdf->Cell(195,5,'SURAT PENGANTAR',0,2,'C');
$pdf->SetLineWidth(0.6); 
$pdf->Line(83, 45, 132, 45);
$pdf->SetFont('Tahomabd','',14);



// Misalkan $blnket saat ini dalam format '7-08-2024'
$originalDate = $blnket; // '7-08-2024'

// Ubah format tanggal dari 'd-m-Y' menjadi 'j F Y'
$timestamp = strtotime($originalDate);
$formattedDate = date('j F Y', $timestamp);

// Ganti nama bulan ke dalam bahasa Indonesia
$months = array(
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
);

$formattedDateIndo = strtr($formattedDate, $months);

// Kemudian, Anda bisa menampilkan tanggal dalam PDF
$pdf->SetXY(10, 40);
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->Cell(120, 6, '', 0, 0); // Empty cell for alignment
$pdf->Cell(30, 6, 'Semarang, ' . $formattedDateIndo, 0, 0);
$pdf->Ln(5);



$pdf->SetXY(10, $pdf->GetY());
$pdf->Cell(30, 6, 'Nomor Polisi', 0, 0);
$pdf->Cell(60, 6, ': ' . $nopol, 0, 1);
$pdf->SetXY(10, $pdf->GetY());
$pdf->Cell(30, 6, 'Jenis Kendaraan', 0, 0);
$pdf->Cell(60, 6, ': ' .$nmerk . $jenis, 0, 1);

$pdf->Ln(2);

$pdf->Cell(120, 6, '', 0, 0);
$pdf->Cell(60, 6, 'Kepada Yth.', 0, 0);
$pdf->Ln(5);
$pdf->Cell(120, 6, '', 0, 0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60, 6, 'Bengkel Nasima', 0, 1);
$pdf->Cell(120, 6, '', 0, 0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(60, 6, 'Di Semarang', 0, 1);
$pdf->Ln(5);

$pdf->SetXY(12, $pdf->GetY());
$pdf->Cell(30, 6, 'Dimohon untuk dapat diberikan perbaikan Service Kendaraan Dinas Sbb :', 0, 0);
$pdf->Ln(8);

$pdf->SetXY(10, $pdf->GetY());
$pdf->Cell(10, 6, 'NO', 1, 0, 'C');
$pdf->Cell(100, 6, 'JENIS PERBAIKAN', 1, 0, 'C');
$pdf->Cell(80, 6, 'KETERANGAN', 1, 0, 'C');
$pdf->Ln();

$pdf->SetXY(10, $pdf->GetY());
$pdf->Cell(10, 6,  + 1, 1, 0, 'C');
$pdf->Cell(100, 6, $ket, 1);
$pdf->Cell(80, 6, '', 1);

$pdf->Ln(8);

// Footer

$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(20, $pdf->GetY() + 5);
$pdf->Cell(60, 4, 'Mengetahui / Menyetujui', 0, 2, 'C');
$pdf->Cell(60, 4, 'Kasubbag Umpeg', 0, 2, 'C');

$pdf->SetXY(130, $pdf->GetY() - 5); // Set posisi X untuk kolom kedua
$pdf->Cell(60, 4, 'Pemohon', 0, 2, 'C');

// Menambahkan teks "Aldi" di bawah "Pemohon"
$pdf->SetXY(130, $pdf->GetY() + 18);
$pdf->Cell(60, 4, $nm_pemegang, 0, 2, 'C');

// Menambahkan teks "Dr. GALIH WIBOWO, S.Sos, MA"
$pdf->SetXY(20, $pdf->GetY());
$pdf->Cell(60, 4, 'Dr. GALIH WIBOWO, S.Sos, MA', 0, 2, 'C');

// Menggambar underline
$pdf->SetLineWidth(0.4);
$xStart = 24; // Awal garis, sama dengan posisi X dari teks
$xEnd = $xStart + 52; // Panjang garis, sama dengan lebar Cell
$yLine = $pdf->GetY(); // Posisi Y untuk garis, sedikit di bawah teks
$pdf->Line($xStart, $yLine, $xEnd, $yLine); // Menggambar garis underline

$pdf->SetXY(20, $pdf->GetY()+1);
$pdf->Cell(60, 4, 'NIP. 198010202006041011', 0, 2, 'C');


$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(80, $pdf->GetY() + 10);
$pdf->Cell(60, 4, 'Menyetujui', 0, 2, 'C');
$pdf->Cell(60, 4, 'Plt Sekretaris,', 0, 2, 'C');

// Menambahkan teks "Moch Faizin, S.Sos., MM"
$pdf->SetXY(80, $pdf->GetY() + 18);
$pdf->Cell(60, 4, 'Moch Faizin, S.Sos., MM', 0, 2, 'C');

// Menggambar underline untuk "Moch Faizin, S.Sos., MM"
$pdf->SetLineWidth(0.4);
$xStart = 90; // Awal garis, sama dengan posisi X dari teks "Moch Faizin, S.Sos., MM"
$xEnd = $xStart + 40; // Panjang garis, sama dengan lebar Cell
$yLine = $pdf->GetY(); // Posisi Y untuk garis, sedikit di bawah teks
$pdf->Line($xStart, $yLine, $xEnd, $yLine); // Menggambar garis underline

$pdf->SetXY(80, $pdf->GetY()+1);
$pdf->Cell(60, 4, 'NIP. 197311191994031005', 0, 2, 'C');


// Output PDF
$pdf->Output('I', 'Surat_Pengantar.pdf');
?>
