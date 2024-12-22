option<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<link href="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
<!-- Template CSS -->

<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
<!-- Custom style CSS -->

<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>


<style type="text/css">

#upload-button {
    width: 150px;
    display: block;
    margin: 20px auto;
}

#file-to-upload {
    display: none;
}

#pdf-main-container {
    width: 400px;
    margin: 20px auto;
}

#pdf-loader {
    display: none;
    text-align: center;
    color: #999999;
    font-size: 13px;
    line-height: 100px;
    height: 100px;
}

#pdf-contents {
    display: none;
}

#pdf-meta {
    overflow: hidden;
    margin: 0 0 20px 0;
}

#pdf-buttons {
    float: center;
}

#page-count-container {
    float: right;
}

#pdf-current-page {
    display: inline;
}

#pdf-total-pages {
    display: inline;
}

#pdf-canvas {
    border: 1px solid rgba(0,0,0,0.2);
    box-sizing: border-box;
}

#page-loader {
    height: 100px;
    line-height: 100px;
    text-align: center;
    display: none;
    color: #999999;
    font-size: 13px;
}

</style>
<section class="section">
<?php
if($this->session->flashdata('notif') != NULL){
    $tep=$this->session->flashdata('notif')['tipe'];
    $is=$this->session->flashdata('notif')['isi'];
    $cs = array('1' =>'alert-primary' ,'2' =>'alert-warning','3' =>'alert-danger');
    
?>
<div class="alert <?php echo $cs[$tep];?> alert-dismissible show fade">
  <div class="alert-body">
    <button class="close" data-dismiss="alert">
      <span>&times;</span>
    </button>
    <?php echo $is;?>
  </div>
</div>
<?php }
?>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Edit Kendaraan</h4>
            
          </div>
          <div class="card-body">
            <?php 
            date_default_timezone_set("Asia/Jakarta");
            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('mobil/save_e_mobil',$arrayName);?>
            <?php
            /*echo "<pre>";
            print_r($data);
            echo "</pre>";*/

          foreach ($data as $key ) {
            $id=$key->id;
            $nopol=$key->nopol;
            $norangka=$key->nrangka;
            $nomesin=$key->nmesin;
            $nobpkb=$key->nbpkb;
            $merk=$key->nmerk;
            $jenis=$key->jenis;
            $thbeli=$key->thbeli;
            $ukuran=$key->ukuran;
            $asal=$key->asal;
            $pjk=$key->pjk;
            $status=$key->status;
            $nmfile=$key->nmfile;
            $perlu=$key->r_perlu;
            $tujuan=$key->r_tujuan;
            $hak=$key->hak;
            $ket=$key->ket;
            $th_rakit=$key->th_rakit;
          }
            ?>

             <div class="form-row">

             <?php 
                $tahun=date("Y");
              ?>
              <div class="form-group col-md-3">
                  <label for="tgl">Nomor Polisi / Kendaraan</label>
                  <input type="hidden" name="nopollama" id="nopollama" value="<?php echo $nopol;?>" >
                  <input type="text" class="form-control" name="nopol" id="nopol" maxlength="10" value="<?php echo $nopol;?>" required="required">
                   <?php  echo form_error('nopol'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nomor Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Nomor Rangka</label>
                  <input type="text" class="form-control" name="norangka" id="norangka" maxlength="17" value="<?php echo $norangka;?>"  required="required">
                   <?php  echo form_error('norangka'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nomor Rangka
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Nomor Mesin</label>
                  <input type="text" class="form-control" name="nomesin" id="nomesin" maxlength="17" value="<?php echo $nomesin;?>" required="required">
                   <?php  echo form_error('nomesin'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nomor Mesin
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Nomor BPKB</label>
                  <input type="text" class="form-control" name="nobpkb" id="nobpkb" maxlength="17" value="<?php echo $nobpkb;?>" required="required">
                   <?php  echo form_error('nobpkb'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nomor BPKB
                  </div>
              </div>

            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                  <label for="tgl">Merk</label>
                  <input type="text" class="form-control" name="merk" id="merk" value="<?php echo $merk;?>" required="required">
                   <?php  echo form_error('merk'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Merk Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Jenis</label>
                  <input type="text" class="form-control" name="jenis" id="jenis" value="<?php echo $jenis;?>" required="required">
                   <?php  echo form_error('jenis'); ?>
                  <div class="invalid-feedback">
                      Silahkan Masukan Jenis Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Ukuran / CC</label>
                  <input type="text" class="form-control" name="ukuran" id="ukuran" value="<?php echo $ukuran;?>"  required="required">
                   <?php  echo form_error('ukuran'); ?>
                  <div class="invalid-feedback">
                      Silahkan Masukan Ukuran / CC Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Asal Usul</label>
                  <input type="text" class="form-control" name="asal" id="asal" value="<?php echo $asal;?>" required="required">
                   <?php  echo form_error('asal'); ?>
                  <div class="invalid-feedback">
                      Silahkan Masukan Asal Usul Kendaraan
                  </div>
              </div>

            </div>
           
            
            <div class="form-row">
              <div class="form-group col-md-3">
                  <label for="tgl">Tahun Pembelian</label>
                  
                    <select class="form-control" name="thbeli" id="thbeli" required="required">
                    <option value=''>Pilih Tahun</option>
                    <?php
                      for ($i=2000;$i<=$tahun;$i++)
                      {
                        $beli= sprintf("%02d",$i);
                        $selected =($beli==$thbeli)? 'selected' : '';
                        ?>
                        <option value="<?php echo $beli;?>" <?php echo $selected;?>><?php echo $beli;?></option>
                        
                      
                     <?php }
                      ?>
                      
                    </select>
                    
                  <!--<input type="number" class="form-control" min="1900" max="2099" step="1" name="thbeli" id="thbeli" value="<?php echo $thbeli;?>" required="required"/> 
                      --><?php  echo form_error('thbeli'); ?>
                  <div class="invalid-feedback">
                     Silahkan Masukan Tahun Pembelian Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tglpjk">Tanggal Pajak</label>
                  <select name="tglpjk" class="form-control" id="tglpjk">
                      <option value="">Pilih Tanggal</option>                     
                      <?php
                        $bkw=explode("/", $pjk);
                        for ($i=1; $i <32; $i++) { ?>
                          <option <?php 
                            if ($bkw[1]==sprintf("%02d", $i)) {
                              echo "selected='selected'";
                            }
                        ?>value="<?php echo sprintf("%02d", $i)?>"><?php echo sprintf("%02d", $i);?></option>                     
                      <?php
                        }
                      ?>                     
                  </select>
              </div>

              <div class="form-group col-md-3">
                  <label for="blnpjk">Bulan Pajak</label>
                  <select name="blnpjk" class="form-control" id="blnpjk" >
                      <option value="">Pilih Bulan</option>                     
                      <?php
                        $bulan = array('1' => 'Januari','2' => 'Februari','3' => 'Maret','4' => 'April','5' => 'Mei','6' => 'Juni','7' => 'Juli','8' => 'Agustus','9' => 'September','10' => 'Oktober','11' => 'November','12' => 'Desember' );
                        for ($i=1; $i <=12; $i++) { ?>
                          <option <?php 
                            if ($bkw[0]==sprintf("%02d", $i)) {
                              echo "selected='selected'";
                            }?>
                            value="<?php echo $i;?>"><?php echo $bulan[$i];?></option>                     
                      <?php
                        }
                      ?>                     
                  </select>
              </div>

              <div class="form-group col-md-3">
                  <label for="th_rakit">Tahun Rakit</label>
                  <select class="form-control" name="th_rakit" id="th_rakit" required="required">
                    <option value=''>Pilih Tahun</option>
                    <?php
                    
                      for ($i=2000;$i<=$tahun;$i++)
                      {
                        $rakit= sprintf("%02d",$i);
                        $selected =($beli==$th_rakit)? 'selected' : '';
                        ?>
                        <option value="<?php echo $rakit;?>" <?php echo $selected;?>><?php echo $rakit;?></option>
                     <?php }
                      ?>
                      
                    </select>
                  <!--<input type="number" class="form-control" min="1900" max="2099" step="1" name="th_rakit" id="th_rakit" value="<?php echo $th_rakit;?>" required="required"/> 
                      --> <?php  echo form_error('th_rakit'); ?>
                  <div class="invalid-feedback">
                     Silahkan Masukan Tahun Pembelian Kendaraan
                  </div>
              </div>
            </div>

            <div class="form-row" >
            <div class="form-group col-md-12">
                <label>Status Kendaraan</label>
                <select name="stsken" class="form-control select2" id="stsken" required="required">                    
                    <option value="NULL" id="dfpinjam">Pilih Status</option>                     
                    <option <?php if($status==0) { echo "selected='selected'";}?> value="0">Bisa Dipinjam</option>
                    <option <?php if($status==1) { echo "selected='selected'";}?>value="1">Perawatan</option>                     
                    <option <?php if($status==2) { echo "selected='selected'";}?>value="2">Penggunaan Khusus</option>
                </select>
                 <?php  echo form_error('stsken'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Jenis Peminjaman
                  </div>
            </div>
            </div>

            <div class="form-row" >
            <div class="form-group col-md-12">
                <label>Keperluan Kendaraan</label>
                    <!-- primary -->
                  <?php
                  $prl1=NULL;
                  $prl2=NULL;
                  $prl3=NULL;
                  $prl4=NULL;
                  $hav=explode("-", $perlu);
                  for ($ix=1; $ix < count($hav); $ix++) { 
                      switch ($hav[$ix]) {
                        case '1':
                          $prl1="checked";
                          break;
                        case '2':
                          $prl2="checked";
                          break;
                        case '3':
                          $prl3="checked";
                          break;
                        case '4':
                          $prl4="checked";
                          break;
                        default:
                          $prl5=NULL;
                          break;
                      }                                          
                  }

                  ?>
                  <div class="card-body">
                  
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="perlu1" name="perlu[1]" value="1" <?php echo $prl1;?>>
                        <label class="form-check-label" for="perlu1">Dinas Luar Biasa</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="perlu2" name="perlu[2]" value="2" <?php echo $prl2;?>>
                        <label class="form-check-label" for="perlu2">Fasilitasi Kunjungan Tamu</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="perlu3" name="perlu[3]" value="3" <?php echo $prl3;?>>
                        <label class="form-check-label" for="perlu3">Rangkaian Keg. Pimpinan Daerah</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="perlu4" name="perlu[4]" value="4" <?php echo $prl4;?>>
                        <label class="form-check-label" for="perlu4">Kegiatan Lainnya</label>
                  </div>

                  </div>
                 <?php  echo form_error('perlu'); ?>
                <div class="invalid-feedback">Silahkan Pilih Keperluan Kendaraan</div>
            </div>
            </div>
            <div class="form-row" >
              <div class="form-group col-md-12">
                <label>Tujuan Area Kendaraan</label>
                <div class="card-body">
                   <?php
                  $tjn1=NULL;
                  $tjn2=NULL;
                  $tjn3=NULL;
                  $hvg=explode("-", $tujuan);
                  for ($ik=1; $ik < count($hvg); $ik++) { 
                      switch ($hvg[$ik]) {
                        case '1':
                          $tjn1="checked";
                          break;
                        case '2':
                          $tjn2="checked";
                          break;
                        case '3':
                          $tjn3="checked";
                          break;
                        default:
                          $tjn5=NULL;
                         /* $tjn1=NULL;
                          $tjn2=NULL;
                          $tjn3=NULL;*/
                         
                          break;
                      }                                          
                  }

                  ?>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="tjn1" name="tjn[1]" value="1" <?php echo $tjn1;?>>
                        <label class="form-check-label" for="tjn1">Dalam Kota</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="tjn2" name="tjn[2]" value="2" <?php echo $tjn2;?>>
                        <label class="form-check-label" for="tjn2">Dalam Daerah</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="tjn3" name="tjn[3]" value="3" <?php echo $tjn3;?>>
                        <label class="form-check-label" for="tjn3">Luar Daerah</label>
                  </div>
                  

                  </div>
                </div>
            </div>

            <div class="form-row" >
              <div class="form-group col-md-12">
                <label>Hak Kendaraan</label>
                <select name="hak" class="form-control select2" id="hak">                    
                  <option <?php if($hak==NULL) { echo "selected='selected'";}?> value="">Pilih Hak Pengguna Kendaraan</option>
                  <option <?php if($hak==0) { echo "selected='selected'";}?>value="0">Kepala Dinas</option>                     
                  <option <?php if($hak==1) { echo "selected='selected'";}?>value="1">Sekretaris</option>                     
                  <option <?php if($hak==2) { echo "selected='selected'";}?>value="2">Kabid TIK</option>                     
                  <option <?php if($hak==3) { echo "selected='selected'";}?>value="3">Kabid PDKI</option>                     
                  <option <?php if($hak==4) { echo "selected='selected'";}?>value="4">Kabid IKP</option>                     
                  <option <?php if($hak==5) { echo "selected='selected'";}?>value="5">Kabid e-Gov</option>                     
                  <option <?php if($hak==6) { echo "selected='selected'";}?>value="6">Kabid Satistik</option>                     
                  <option <?php if($hak==7) { echo "selected='selected'";}?>value="7">POOL KANTOR</option>                     
                  <option <?php if($hak==8) { echo "selected='selected'";}?>value="8">Sekretariat</option>                     
                  <option <?php if($hak==9) { echo "selected='selected'";}?>value="9">TIK</option>                     
                  <option <?php if($hak==10) { echo "selected='selected'";}?>value="10">PDKI</option>                     
                  <option <?php if($hak==11) { echo "selected='selected'";}?>value="11">IKP</option>                     
                  <option <?php if($hak==12) { echo "selected='selected'";}?>value="12">e-Gov</option>                     
                  <option <?php if($hak==13) { echo "selected='selected'";}?>value="13">Satistik</option>                     
                  <option <?php if($hak==14) { echo "selected='selected'";}?>value="14">Komisi Informasi</option>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <label>Keterangan Kendaraan</label>
            <textarea class="form-control" name="ket"><?php echo set_value('dasrut');?><?php echo $ket;?></textarea>
               <?php  echo form_error('ket'); ?>
              <div class="invalid-feedback">
                        Silahkan Input keterangan lainnya
              </div>
            </div>

            <div class="form-group">

              <label>Foto Kendaraan</label>
              <input type="file" class="form-control" multiple="" name="foto[]" accept="image/*">
              <div class="invalid-feedback">
                        Silahkan Upload Foto Kendaraan
              </div>
            
              <?php
                $hbg=str_replace(" ","",$nopol);
                $folder = "harta/pinjam/datamobil/".$hbg;
                $handle =  glob($folder."/*.*");
                /*echo "<pre>";
                print_r($handle);
                echo "</pre>";*/
              ?>
              <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
                  <?php
                    for ($iz=0; $iz < count($handle) ; $iz++) { 
                        echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <a href="'.base_url().$handle[$iz].'" data-sub-html="Demo Description" target="_blank">
                      <img class="img-responsive thumbnail" src="'.base_url().$handle[$iz].'" alt="">
                    </a>
                  </div>';
                    }
                  ?>

              </div>
            </div>
           <div class="form-group">
              <label>Dokumen Kendaraan</label>
              <br><span>Jika Upload Dokumen Maka akan Menggantikan Dokumen Sebelumnya</span>
              <input type="hidden" name="nmdoklama" value="<?php echo $nmfile;?>">
              <input type="file" class="form-control" name="dokumen" id="dokumen" accept="application/pdf" >
              <div class="invalid-feedback">
                        Silahkan Upload Dokumen
              </div>
              <div class="text-center">
              <embed src="<?php echo base_url()?>harta/pinjam/datamobil/<?php echo $nmfile;?>.pdf?<?php echo time();?>" width="700px" height="400px" />
              </div>
            </div>

            <div class="card-footer text-center">
            <label>Review File</label>
              <div id="pdf-loader">Loading document ...</div>
              <div id="pdf-contents">
                  <div id="pdf-meta">

                      <div id="pdf-buttons">
                          <button id="pdf-prev" type="button" class="btn btn-custon-four btn-primary">Previous</button>
                          <button id="pdf-next" type="button" class="btn btn-custon-four btn-primary">Next</button>
                          
                      </div>
                      <div id="page-count-container">Halaman ke <div id="pdf-current-page"></div> dari <div id="pdf-total-pages"></div></div>
                  </div>
                  <canvas id="pdf-canvas" width="500"></canvas>
                  <div id="page-loader">Loading page ...</div>
                  <p id="vv"></p>
              </div>
            </div>

            
            <div class="card-footer text-center">
              <input type="hidden" name="id" value="<?php echo $id;?>">
              <button class="btn btn-primary mr-1" type="submit">Submit</button>
              <button class="btn btn-secondary" type="reset">Reset</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">



var __PDF_DOC, __CURRENT_PAGE, __TOTAL_PAGES, __PAGE_RENDERING_IN_PROGRESS = 0,
  __CANVAS = $('#pdf-canvas').get(0),
  __CANVAS_CTX = __CANVAS.getContext('2d');

function showPDF(pdf_url) {
  $("#pdf-loader").show();
  PDFJS.getDocument({
    url: pdf_url
  }).then(function(pdf_doc) {
    __PDF_DOC = pdf_doc;
    __TOTAL_PAGES = __PDF_DOC.numPages;
    $("#pdf-loader").hide();
    $("#pdf-contents").show();
    $("#pdf-total-pages").text(__TOTAL_PAGES);
    showPage(1)
  }).catch(function(error) {
    $("#pdf-loader").hide();
    $("#upload-button").show();
    alert(error.message)
  })
}

function showPage(page_no) {
  __PAGE_RENDERING_IN_PROGRESS = 1;
  __CURRENT_PAGE = page_no;
  $("#pdf-next, #pdf-prev").attr('disabled', 'disabled');
  $("#pdf-canvas").hide();
  $("#page-loader").show();
  $("#pdf-current-page").text(page_no);
  __PDF_DOC.getPage(page_no).then(function(page) {
    var scale_required = __CANVAS.width / page.getViewport(1).width;
    var viewport = page.getViewport(scale_required);
    __CANVAS.height = viewport.height;
    var renderContext = {
      canvasContext: __CANVAS_CTX,
      viewport: viewport
    };
    page.render(renderContext).then(function() {
      __PAGE_RENDERING_IN_PROGRESS = 0;
      $("#pdf-next, #pdf-prev").removeAttr('disabled');
      $("#pdf-canvas").show();
      $("#page-loader").hide()
    })
  })
}
$("#upload-button").on('click', function() {
  $("#dokumen").trigger('click')
});
$("#dokumen").on('change', function() {
  if (['application/pdf'].indexOf($("#dokumen").get(0).files[0].type) == -1) {
    alert('Error : Not a PDF');
    return
  }
  $("#upload-button").hide();
  showPDF(URL.createObjectURL($("#dokumen").get(0).files[0]))
});
$("#pdf-prev").on('click', function() {
  if (__CURRENT_PAGE != 1)
    showPage(--__CURRENT_PAGE)
});
$("#pdf-next").on('click', function() {
  if (__CURRENT_PAGE != __TOTAL_PAGES)
    showPage(++__CURRENT_PAGE)
});


</script>

<!-- JS Libraies -->
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
<!-- Page Specific JS File -->
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/light-gallery.js"></script>
