<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
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
            <h4>Edit Alat</h4>
            
          </div>
          <div class="card-body">
            <?php 
            foreach ($data as $ky ) {
              $id=$ky->id;
              $nmbarang=$ky->nmbarang;
              $status=$ky->status;
              $nmfile=$ky->nmfile;
              $ket=$ky->ket;
            }
            /*echo "<pre>";
            print_r($data);
            echo "</pre>";*/


            date_default_timezone_set("Asia/Jakarta");
            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('alat/simpan_edit',$arrayName);?>
             <div class="form-row">
              <div class="form-group col-md-6">
                  <label for="tgl">Nama Alat</label>
                  <input type="hidden" name="narungold" value="<?php echo $nmbarang;?>">
                  <input type="text" class="form-control" name="nmalat" id="nmalat" value="<?php echo $nmbarang;?>" required="required">
                   <?php  echo form_error('nmalat'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nama Ruangan 
                  </div>
              </div>
            </div> 
            <div class="form-row">
              <div class="form-group col-md-6">
                  <label>Status Ruangan</label>
                  <select name="stsru" class="form-control select2" id="stsru" required="required">                    
                      <option value="NULL" id="dfpinjam">Pilih Status</option>                     
                      <option <?php if ($status == '0') {
                              echo "selected='selected'";
                            }?> value="0">Bisa Dipinjam</option>                     
                      <option <?php if ($status == '1') {
                              echo "selected='selected'";
                            }?> value="1">Perawatan</option>                     
                      <option <?php if ($status == '2') {
                              echo "selected='selected'";
                            }?> value="2">Penggunaan Khusus</option>                     
                  </select>
                   <?php  echo form_error('stsken'); ?>
                  <div class="invalid-feedback">
                          Silahkan Pilih Jenis Peminjaman
                    </div>
              </div>
            </div>
            <div class="form-group">
              <label>Keterangan Ruangan</label>
            <textarea class="form-control "  name="ket" ><?php echo $ket;?></textarea>
               <?php  echo form_error('ket'); ?>
              <div class="invalid-feedback">
                        Silahkan Input keterangan lainnya
              </div>
            </div>

            <div class="form-group">
              <label>Foto Ruangan</label>
              <input type="file" class="form-control" multiple="" name="foto[]" accept="image/*">
              <div class="invalid-feedback">
                        Silahkan Upload Foto Ruangan
              </div>

              <?php
                $hbg=str_replace(" ","",$nmbarang);
                $folder = "harta/pinjam/dataalat/".strtoupper($hbg);
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
              <label>Dokumen Ruangan</label>
              <br><span>Jika Upload Dokumen Maka akan Menggantikan Dokumen Sebelumnya</span>
              <input type="hidden" name="nmdoklama" value="<?php echo $nmfile;?>">
              <input type="file" class="form-control" name="dokumen" id="dokumen" accept="application/pdf">
              <div class="invalid-feedback">
                        Silahkan Upload Ruangan
              </div>
              <div class="text-center">
             <!--  <iframe src="<?php //echo base_url()?>harta/pinjam/dataruang/<?php // echo $nmfile;?>.pdf" width="600" height="400"></iframe>-->
              <embed src="<?php echo base_url()?>harta/pinjam/dataalat/<?php echo $nmfile;?>.pdf?<?php echo time();?>" width="700px" height="600px" /> 
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

