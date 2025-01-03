<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

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
            <h4>Input Kendaraan</h4>
            
          </div>
          <div class="card-body">
            <?php 
            date_default_timezone_set("Asia/Jakarta");
            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('mobil/save_mobil',$arrayName);?>
            <div class="form-row">

            <?php 
                $tahun=date("Y");
              ?>

              <div class="form-group col-md-3">
                  <label for="tgl">Nomor Polisi / Kendaraan</label>
                  <input type="text" class="form-control" name="nopol" id="nopol" maxlength="10"  required="required">
                   <?php  echo form_error('nopol'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nomor Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Nomor Rangka</label>
                  <input type="text" class="form-control" name="norangka" id="norangka" maxlength="17"  required="required">
                   <?php  echo form_error('norangka'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nomor Rangka Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Nomor Mesin</label>
                  <input type="text" class="form-control" name="nomesin" id="nomesin" maxlength="17"  required="required">
                   <?php  echo form_error('nomesin'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nomor Mesin Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Nomor BPKB</label>
                  <input type="text" class="form-control" name="nobpkb" id="nobpkb" maxlength="17"  required="required">
                   <?php  echo form_error('nobpkb'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nomor BPKB Kendaraan
                  </div>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group col-md-3">
                  <label for="tgl">Merk</label>
                  <input type="text" class="form-control" name="merk" id="merk" required="required">
                   <?php  echo form_error('merk'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Merk Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Jenis</label>
                  <input type="text" class="form-control" name="jenis" id="jenis" required="required">
                   <?php  echo form_error('jenis'); ?>
                  <div class="invalid-feedback">
                      Silahkan Masukan Jenis Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Ukuran / CC</label>
                  <input type="text" class="form-control" name="ukuran" id="ukuran" required="required">
                   <?php  echo form_error('ukuran'); ?>
                  <div class="invalid-feedback">
                      Silahkan Masukan Ukuran / CC Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Asal Usul</label>
                  <input type="text" class="form-control" name="asal" id="asal" required="required">
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
                    <?php
                      echo "<option value=''>Pilih Tahun</option>";
                      for ($i=2000;$i<=$tahun;$i++)
                      {
                        echo "<option value=".$i.">".$i."</option>";
                      }
                      ?>
                    </select>
                 <!-- <input type="number" class="form-control" min="1900" max="2099" step="1" value="2024" name="thbeli" id="thbeli" required="required"/> 
                    --> <?php  echo form_error('thbeli'); ?>
                  <div class="invalid-feedback">
                     Silahkan Masukan Tahun Pembelian Kendaraan
                  </div>
              </div>

              <div class="form-group col-md-3">
                  <label for="tglpjk">Tangal Pajak</label>
                  <select name="tglpjk" class="form-control" id="tglpjk">
                      <option value="">Pilih Tanggal</option>                     
                      <?php
                        for ($i=1; $i <32; $i++) { ?>
                          <option value="<?php echo sprintf("%02d", $i)?>"><?php echo sprintf("%02d", $i);?></option>                     
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
                          <option value="<?php echo $i;?>"><?php echo $bulan[$i];?></option>                     
                      <?php
                        }
                      ?>                     
                  </select>
              </div>

              <div class="form-group col-md-3">
                  <label for="tgl">Tahun Rakit</label>
                  <select class="form-control" name="th_rakit" id="th_rakit" required="required">
                    <?php
                     echo "<option value=''>Pilih Tahun</option>";
                      for ($i=2000;$i<=$tahun;$i++)
                      {
                        echo "<option value=".$i.">".$i."</option>";
                      }
                      ?>
                    </select>
                  <!--<input type="number" class="form-control" min="1900" max="2099" step="1" name="th_rakit" id="th_rakit" required="required"/> 
                    --> <?php  echo form_error('th_rakit'); ?>
                  <div class="invalid-feedback">
                     Silahkan Masukan Tahun Rakit
                  </div>
              </div>
            </div>

            <div class="form-row" >
            <div class="form-group col-md-12">
                <label>Status Kendaraan</label>
                <select name="stsken" class="form-control select2" id="stsken" required="required">                    
                    <option value="NULL" id="dfpinjam">Pilih Status</option>                     
                    <option value="0">Bisa Dipinjam</option>                     
                    <option value="1">Perawatan</option>                     
                    <option value="2">Penggunaan Khusus</option>                     
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
                  <div class="card-body">
                  
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="perlu1" name="perlu[1]" value="1">
                        <label class="form-check-label" for="perlu1">Dinas Luar Biasa</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="perlu2" name="perlu[2]" value="2">
                        <label class="form-check-label" for="perlu2">Fasilitasi Kunjungan Tamu</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="perlu3" name="perlu[3]" value="3">
                        <label class="form-check-label" for="perlu3">Rangkaian Keg. Pimpinan Daerah</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="perlu4" name="perlu[4]" value="4">
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
                  
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="tjn1" name="tjn[1]" value="1">
                        <label class="form-check-label" for="tjn1">Dalam Kota</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="tjn2" name="tjn[2]" value="2">
                        <label class="form-check-label" for="tjn2">Dalam Daerah</label>
                  </div>
                  <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="tjn3" name="tjn[3]" value="3">
                        <label class="form-check-label" for="tjn3">Luar Daerah</label>
                  </div>
                  

                  </div>
                </div>
            </div>

            <div class="form-row" >
              <div class="form-group col-md-12">
                <label>Hak Kendaraan</label>
                <select name="hak" class="form-control select2" id="hak">                    
                  <option value="">Pilih Hak Pengguna Kendaraan</option>                     
                  <option value="0">Kepala Dinas</option>                     
                  <option value="1">Sekretaris</option>                     
                  <option value="2">Kabid TIK</option>                     
                  <option value="3">Kabid PDKI</option>                     
                  <option value="4">Kabid IKP</option>                     
                  <option value="5">Kabid e-Gov</option>                     
                  <option value="6">Kabid Satistik</option>                     
                  <option value="7">POOL KANTOR</option>                     
                  <option value="8">Sekretariat</option>                     
                  <option value="9">TIK</option>                     
                  <option value="10">PDKI</option>                     
                  <option value="11">IKP</option>                     
                  <option value="12">e-Gov</option>                     
                  <option value="13">Satistik</option>                     
                  <option value="14">Komisi Informasi</option>                     
                </select>
              </div>
            </div>
            

           
            <div class="form-group">
              <label>Keterangan Kendaraan</label>
            <textarea class="form-control "  name="ket" ><?php echo set_value('dasrut');?></textarea>
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
            </div>
           <div class="form-group">
              <label>Dokumen Kendaraan</label>
              <input type="file" class="form-control" name="dokumen" id="dokumen" accept="application/pdf">
              <div class="invalid-feedback">
                        Silahkan Upload Dokumen
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


