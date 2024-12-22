<?php
date_default_timezone_set("Asia/Jakarta");

foreach ($spt as $key) {
	$dasrut=base64_decode($key->dasrut);
	$nosur=$key->nosur;
	$namadl=$key->nmdl;
	$nmdl=explode("-", trim($namadl,"-")); $b=0;
	do{
		$nmlist[$b]=base64_decode($nmdl[$b]);
		$b++;
	}while ($b<count($nmdl));
	
	$perlu=htmlentities(base64_decode($key->keperluan));
	$tglsur=$key->tgldl;
	$tglin=$key->tglin;
	$tglot=$key->tglot;
	$nmttd=$key->nmttd;


}
$namaDL=getNmSPT($nmlist,$tahun);

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
    
}
//footer
function Footer()
    {
        $this->SetY(-25);
                         
         $this->SetLineWidth(0.5);    
         $this->Line(10, 318, 205, 318);
        // //Arial italic 8
         $this->SetFont('Tahoma','',10);
        // // time created
         
         $this->SetXY(40,319);
         $this->MultiCell(160,5,"Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara".'.',0,'J');
    }
    
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();

$pdf->AddPage("P","Legal");
//Jika 4 aktif

$pdf->SetMargins(30,20,30);

/*nota dinas*/
$pdf->SetXY(10,36);
$pdf->SetFont('Tahomabd','',14);
$pdf->Cell(195,5,'SURAT PERINTAH TUGAS',0,2,'C');
$pdf->SetLineWidth(0.6); 
$pdf->Line(76.5, 40.9, 138.5, 40.9);
$pdf->SetFont('Tahomabd','',14);
$pdf->Cell(195,7,'NOMOR : 094/'.$nosur,0,2,'C');

$pdf->SetXY(10,50);
$pdf->SetFont('Tahomabd','',13.5);
$pdf->Cell(20,5,'Dasar',0,0,'L');

$pdf->SetFont('Tahoma','',13.5);
$pdf->Cell(5,5,':',0,0,'C');

$vb=1;
foreach ($dasur as $dsrt) {
	$pdf->SetX(35);
    $pdf->Cell(5,6,$vb.'.',0,0,'C');
    $pdf->MultiCell(165,6,$dsrt->isi.'.',0,'J');
    $vb++;
}

//dasar tambahan
if (!empty($dasrut) && $dasrut != null ) {

    $sur=explode("/**/", $dasrut);
    if (count($sur)==2) {
        $pdf->SetX(35);
        $pdf->Cell(5,5,$vb.'.',0,0,'C');
        $pdf->MultiCell(165,6,$sur[0].'.',0,'J');
        $pdf->SetXY(35,$pdf->GetY());
        $pdf->Cell(5,6,'5'.'.',0,0,'C');
        $pdf->MultiCell(165,6,$sur[1].'.',0,'J');
    }else{
        $pdf->SetX(35);
        $pdf->Cell(5,6,$vb.'.',0,0,'C');
        $pdf->MultiCell(165,6,$dasrut.'.',0,'J');        
    }

}

$pdf->SetFont('Tahomabd','',14);
$pdf->SetXY(10,$pdf->GetY()+2);
$pdf->Cell(195,6,'MEMERINTAHKAN',0,2,'C');

$pdf->SetXY(10,$pdf->GetY()+2);
$pdf->SetFont('Tahomabd','',14);
$pdf->Cell(20,6,'Kepada',0,0,'L');

$pdf->SetFont('Tahoma','',14);
$pdf->Cell(5,6,':',0,0,'C');

$bgy=count((array)$namaDL);
$i=1;

if ($bgy<2) {

	foreach ($namaDL as $dhj) {
		$pdf->SetXY(35,$pdf->GetY());
        $pdf->Cell(5,6,$i.'.',0,0,'C');
        $pdf->Cell(20,6,'Nama',0,0,'L');
        $pdf->Cell(5,6,':',0,0,'L');
        $pdf->Cell(140,6,base64_decode($dhj->nama),0,2,'L');
        $pdf->SetXY(40,$pdf->GetY());
        $pdf->Cell(20,6,'NIP',0,0,'L');
        $pdf->Cell(5,6,':',0,0,'L');
        $pdf->Cell(140,6,$this->encryption->decrypt($dhj->nip),0,2,'L');
        $pdf->SetXY(40,$pdf->GetY());
        $pdf->Cell(20,6,'Pangkat',0,0,'L');
        $pdf->Cell(5,76,':',0,0,'L');
        $pdf->Cell(140,6,$dhj->golongan,0,2,'L');
        $pdf->SetXY(40,$pdf->GetY());
        $pdf->Cell(20,6,'Jabatan',0,0,'L');
        $pdf->Cell(5,6,':',0,0,'L');
        $pdf->Cell(140,6,$dhj->jabatan,0,2,'L');
        $i++;
	}

    $g=$pdf->GetY();
    $pdf->SetXY(10,$g);
    $pdf->SetFont('Tahomabd','',14);
    $pdf->Cell(20,6,'Untuk',0,0,'L');

    $pdf->SetFont('Tahoma','',14);
    $pdf->Cell(5,6,':',0,0,'C');
    $pdf->Cell(5,6,'1.',0,0,'C');
    
    $pdf->MultiCell(165,6,iconv('UTF-8', 'windows-1252', html_entity_decode($perlu)).'.',0,'J');

    $hj=2;

    foreach ($mperlu as $prl) {
    	$pdf->SetX(35);
	    $pdf->Cell(5,6,$hj.'.',0,0,'C');
	    $pdf->MultiCell(165,6,$prl->isi.'.',0,'J');
	    $hj++;	
    }

    //ditetapkan
    $rty=$pdf->GetY()+5;
    $pdf->SetXY(100,$rty);
    $pdf->Cell(35,7,'Ditetapkan di',0,0,'L');
    $pdf->Cell(5,7,':',0,0,'L');
    $pdf->Cell(30,7,'SEMARANG',0,1,'L');
    //
    $nmtgli = array(
        '01' => 'Januari', 
        '02' => 'Februari', 
        '03' => 'Maret', 
        '04' => 'April', 
        '05' => 'Mei', 
        '06' => 'Juni', 
        '07' => 'Juli', 
        '08' => 'Agustus', 
        '09' => 'September', 
        '10' => 'Oktober', 
        '11' => 'November', 
        '12' => 'Desember', 
    );
    $pdf->SetXY(100,$pdf->GetY());
    $pdf->Cell(35,7,'pada tanggal',0,0,'L');
    $pdf->Cell(5,7,':',0,0,'L');
    $ghj=explode('-',  date("Y-m-d",$tglsur));
    $cfy=$ghj[1];
    $pdf->Cell(30,7,$ghj[2].' '.$nmtgli[$ghj[1]].' '.$ghj[0],0,1,'L');
    $pdf->SetLineWidth(0.6); 
    $pdf->Line(100, $pdf->GetY(), 190, $pdf->GetY());

    $ttdm = array(
        '0' =>"" , 
        '1' =>"An. " , 
        '2' =>"Plh. " , 
    );
    $xpy=getNmTTD($nmttd);
    
    $mhk=$pdf->GetY();
    //ttd halaman 1
    $rtfd=$mhk+10;
    $pdf->Image('./harta/morsip/qr/'.$tahun.'/'.$nosur.'.png',15,319,20,20);
    
    foreach ($xpy as $cof) {
    	$ann=$cof->ttd;
    	$anjabat=$cof->nmjab;
    	$annama=$cof->nm;
    	$angol=$cof->gol;
    	$annip=base64_decode( $cof->nip);
    }
    //ttd halaman 1
    
    $rtf=$mhk+2;
    $pdf->SetXY(69,$rtf);
    $pdf->Cell(10,7,$ttdm[$ann],0,0,'L');
    $pdf->SetXY(70,$rtf);
    $pdf->SetFont('Tahomabd','',13);
    $pdf->Cell(130,7,'KEPALA DINAS KOMUNIKASI DAN INFORMATIKA',0,2,'C');
    $pdf->Cell(130,7,'PROVINSI JAWA TENGAH',0,2,'C');
    if ($ann != 0) {
        $pdf->SetFont('Tahoma','',14);
        $pdf->Cell(130,7,$anjabat,0,2,'C');    
    }
    //ttd scan
    $rm=$pdf->GetY();
    $km=$pdf->GetX();
    $pdf->Image('./harta/morsip/qr/kunci.jpeg',$km+5,$rm-5,15,15);
   
    $pdf->Image('./harta/morsip/qr/tte.jpg',111,$rm,40,5);


    $rtd=$pdf->GetY()+7;
    $pdf->SetXY(71,$rtd);
    $pdf->SetFont('Tahomabd','U',14);
    $pdf->SetLineWidth(0.4);
    $pdf->Cell(130,7,$annama,0,2,'C');
    $pdf->SetFont('Tahoma','',14);
    $pdf->Cell(130,7,$angol,0,2,'C');
    $pdf->Cell(130,7,'NIP. '.$annip,0,2,'C');

     if ($nmttd > 1) {
        $gky=$pdf->GetY()+5;
        $pdf->SetXY(10,$gky);
        $pdf->SetFont('Tahoma','',12);
        $pdf->Cell(130,7,'Tembusan :',0,2,'L');
        $pdf->Cell(130,7,'1. Kepala Dinas Komunikasi dan Informatika Provinsi Jawa Tengah',0,2,'L');
    }

}
else {
    $pdf->SetXY(35,$pdf->GetY());

    $pdf->Cell(140,7,"Terlampir",0,2,'L');

    $g=$pdf->GetY();
    $pdf->SetXY(10,$g);
    $pdf->SetFont('Tahomabd','',14);
    $pdf->Cell(20,7,'Untuk',0,0,'L');

    $pdf->SetFont('Tahoma','',14);
    $pdf->Cell(5,7,':',0,0,'C');
    $pdf->Cell(5,7,'1.',0,0,'C');
    $pdf->MultiCell(165,7,iconv('UTF-8', 'windows-1252', html_entity_decode($perlu)).'.',0,'J');

    $hj=2;

    foreach ($mperlu as $prl) {
    	$pdf->SetX(35);
	    $pdf->Cell(5,6,$hj.'.',0,0,'C');
	    $pdf->MultiCell(165,6,$prl->isi.'.',0,'J');
	    $hj++;	
    }

        //ditetapkan
    $rty=$pdf->GetY()+10;
    $pdf->SetXY(100,$rty);
    $pdf->Cell(30,7,'Ditetapkan di',0,0,'L');
    $pdf->Cell(5,7,':',0,0,'L');
    $pdf->Cell(30,7,'SEMARANG',0,1,'L');
    //
    $nmtgli = array(
        '01' => 'Januari', 
        '02' => 'Februari', 
        '03' => 'Maret', 
        '04' => 'April', 
        '05' => 'Mei', 
        '06' => 'Juni', 
        '07' => 'Juli', 
        '08' => 'Agustus', 
        '09' => 'September', 
        '10' => 'Oktober', 
        '11' => 'November', 
        '12' => 'Desember', 
    );
    $pdf->SetXY(100,$pdf->GetY());
    $pdf->Cell(30,7,'pada tanggal',0,0,'L');
    $pdf->Cell(5,7,':',0,0,'L');
    $ghj=explode('-',  date("Y-m-d",$tglsur));
    $cfy=$ghj[1];
    $pdf->Cell(30,7,$ghj[2].' '.$nmtgli[$ghj[1]].' '.$ghj[0],0,1,'L');
    $pdf->SetLineWidth(0.6); 
    $pdf->Line(100, $pdf->GetY(), 190, $pdf->GetY());

     $nmttd;
    
    $ttdm = array(
        '0' =>"" , 
        '1' =>"An. " , 
        '2' =>"Plh. " , 
    );
    $xpy=getNmTTD($nmttd);
   
    foreach ($xpy as $cof) {
    	$ann=$cof->ttd;
    	$anjabat=$cof->nmjab;
    	$annama=$cof->nm;
    	$angol=$cof->gol;
    	$annip=base64_decode( $cof->nip);
    }
    $rtfd=$pdf->GetY()+10;
    $pdf->Image('./harta/morsip/qr/'.$tahun.'/'.$nosur.'.png',15,319,20,20);
    
    $rtf=$pdf->GetY()+2;
    $pdf->SetXY(65,$rtf);
    $pdf->Cell(6,7,$ttdm[$ann],0,0,'L');
    $pdf->SetFont('Tahomabd','',14);
    $pdf->Cell(130,7,'KEPALA DINAS KOMUNIKASI DAN INFORMATIKA',0,2,'C');
    $pdf->Cell(130,7,'PROVINSI JAWA TENGAH',0,2,'C');
    if ($ann != 0) {
        $pdf->SetFont('Tahoma','',14);
        $pdf->Cell(130,7,$anjabat,0,2,'C');    
    }

     //ttd scan
    $rm=$pdf->GetY();
    $km=$pdf->GetX();
    $pdf->Image('./harta/morsip/qr/kunci.jpeg',$km+5,$rm-5,15,15);

    $pdf->Image('./harta/morsip/qr/tte.jpg',111,$rm,40,5);

    $rtd=$pdf->GetY()+7;
    $pdf->SetXY(71,$rtd);
    $pdf->SetFont('Tahomabd','U',14);
    $pdf->SetLineWidth(0.4);
    $pdf->Cell(130,7,$annama,0,2,'C');
    $pdf->SetFont('Tahoma','',14);
    $pdf->Cell(130,7,$angol,0,2,'C');
    $pdf->Cell(130,7,'NIP. '.$annip,0,2,'C');

    if ($nmttd > 1) {
        $gky=$pdf->GetY()+5;
        $pdf->SetXY(10,$gky);
        $pdf->SetFont('Tahoma','',12);
        $pdf->Cell(130,7,'Tembusan :',0,2,'L');
        $pdf->Cell(130,7,'1. Kepala Dinas Komunikasi dan Informatika Provinsi Jawa Tengah',0,2,'L');
    }

    $pdf->SetAutoPageBreak(true, 120);
   
    $pdf->SetXY(95,$pdf->GetY()+5);
    $pdf->Cell(25,7,"Lampiran",0,0,'L');
    $pdf->Cell(5,7,":",0,0,'L');
    $pdf->Cell(50,7,"Surat Perintah Tugas",0,1,'L');
    $pdf->SetXY(125,$pdf->GetY());
    
    $pdf->Cell(25,7,"Nomor",0,0,'L');
    $pdf->Cell(5,7,":",0,0,'L');
    $pdf->Cell(15,7,"094/".$nosur,0,1,'L');
    $pdf->SetXY(125,$pdf->GetY());
    $pdf->Cell(25,7,"Tanggal",0,0,'L');
    $pdf->Cell(5,7,":",0,0,'C');

    $nmtgli = array(
        '01' => 'Januari', 
        '02' => 'Februari', 
        '03' => 'Maret', 
        '04' => 'April', 
        '05' => 'Mei', 
        '06' => 'Juni', 
        '07' => 'Juli', 
        '08' => 'Agustus', 
        '09' => 'September', 
        '10' => 'Oktober', 
        '11' => 'November', 
        '12' => 'Desember', 
    );

    $pdf->SetAutoPageBreak(false);
    $ghj=explode('-', date("Y-m-d",$tglsur));
    $cfy=$ghj[1];
    $pdf->Cell(30,7,$ghj[2].' '.$nmtgli[$ghj[1]].' '.$ghj[0],0,1,'L');
    $pdf->Cell(5,7,"",0,1,'L');
    $pdf->Cell(5,7,"",0,1,'L');
    $pdf->Cell(5,7,"Daftar lampiran sebagai berikut :",0,1,'L');


	foreach ($namaDL as $dhj) {
		$pdf->SetXY(35,$pdf->GetY());
        $pdf->Cell(5,7,$i.'.',0,0,'C');
        $pdf->Cell(20,7,'Nama',0,0,'L');
        $pdf->Cell(5,7,':',0,0,'L');
        $pdf->Cell(140,7,base64_decode($dhj->nama),0,2,'L');
        $pdf->SetXY(40,$pdf->GetY());
        $pdf->Cell(20,7,'NIP',0,0,'L');
        $pdf->Cell(5,7,':',0,0,'L');
        $pdf->Cell(140,7,$this->encryption->decrypt( $dhj->nip),0,2,'L');
        $pdf->SetXY(40,$pdf->GetY());
        $pdf->Cell(20,7,'Pangkat',0,0,'L');
        $pdf->Cell(5,7,':',0,0,'L');
        $pdf->Cell(140,7,$dhj->golongan,0,2,'L');
        $pdf->SetXY(40,$pdf->GetY());
        $pdf->Cell(20,7,'Jabatan',0,0,'L');
        $pdf->Cell(5,7,':',0,0,'L');
        $pdf->Cell(140,7,$dhj->jabatan,0,2,'L');
        $i++;
	}

    $rtfd=$pdf->GetY()+10;
    $pdf->Image('./harta/morsip/qr/'.$tahun.'/'.$nosur.'.png',15,319,20,20);

    $ttdm = array(
        '0' =>"" , 
        '1' =>"An." , 
        '2' =>"Plh." , 
    );
    $xpya=getNmTTD($nmttd);
   
    foreach ($xpya as $cofa) {
    	$anna=$cofa->ttd;
    	$anjabata=$cofa->nmjab;
    	$annamaa=$cofa->nm;
    	$angola=$cofa->gol;
    	$annipa=base64_decode( $cofa->nip);
    }

    
    $rtf=$pdf->GetY()+8;
    $pdf->SetXY(65,$rtf);
    $pdf->Cell(6,6,$ttdm[$anna],0,0,'L');
    $pdf->SetFont('Tahomabd','',14);
    $pdf->Cell(130,7,'KEPALA DINAS KOMUNIKASI DAN INFORMATIKA',0,2,'C');
    $pdf->Cell(130,7,'PROVINSI JAWA TENGAH',0,2,'C');
    if ($anna != 0) {
        $pdf->SetFont('Tahoma','',14);
        $pdf->Cell(130,7,$anjabata,0,2,'C');    
    }
    $rm=$pdf->GetY();
    $km=$pdf->GetX();
    $pdf->Image('./harta/morsip/qr/kunci.jpeg',$km,$rm,20,20);
    $pdf->Image('./harta/morsip/qr/tte.jpg',111,$rm+3,50,10);
    $rtd=$pdf->GetY()+20;
    $pdf->SetXY(71,$rtd);
    $pdf->SetFont('Tahomabd','U',14);
    $pdf->SetLineWidth(0.4);
    $pdf->Cell(130,7,$annamaa,0,2,'C');
    $pdf->SetFont('Tahoma','',14);
    $pdf->Cell(130,7,$angola,0,2,'C');
    $pdf->Cell(130,7,'NIP. '.$annipa,0,2,'C');
}

$path='./harta/morsip/doc/tempdoc/'.$nosur.'.pdf';
$pdf->Output($path,'F');

?>