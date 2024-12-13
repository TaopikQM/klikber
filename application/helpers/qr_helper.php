<?php

    function makeQR($id,$nosur,$tahun){
    
    $etm=sha1($id);
    $tempdir = "./harta/morsip/qr/".$tahun."/";
    $vnm=substr($tahun,2);
            #parameter inputan
    $namafile = $nosur.".png";
    //date_default_timezone_set("Asia/Jakarta");
    //$date = date('Y-m-d H:i:s');
    $ida=$nosur.'|/\|'.$etm.'|/\|'.$vnm;
    $pg=base64_encode($ida);
    $isi_teks = "http://sekre.diskominfo.jatengprov.go.id/view/spt/?a=".$pg."";
            
    $quality = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
    $ukuran = 5; //batasan 1 paling kecil, 10 paling besar
    $padding = 1;
             
    QRCode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding);
     if (file_exists($tempdir.$namafile)) {
            return true;
        }else{
            return false;
        }   
    }
    function makeQRNamb($id,$nosur,$tahun){
        
        $etm=sha1($id);
        $vnm=substr($tahun,2);
        $tempdir = "./harta/morsip/qr/".$tahun."/";
                #parameter inputan
        $namafile = $nosur.".png";
        //date_default_timezone_set("Asia/Jakarta");
        //$date = date('Y-m-d H:i:s');
        $ida=$nosur.'|/\|'.$etm.'|/\|'.$vnm;
        $pg=base64_encode($ida);
        $isi_teks = "http://sekre.diskominfo.jatengprov.go.id/view/doc/?a=".$pg."";
                
        $quality = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
        $ukuran = 5; //batasan 1 paling kecil, 10 paling besar
        $padding = 1;
                 
        QRCode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding);
        if (file_exists($tempdir.$namafile)) {
            return true;
        }else{
            return false;
        }   
    }


?>