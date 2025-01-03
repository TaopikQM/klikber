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
            <h4>Input Ruangan</h4>
            
          </div>
          <div class="card-body">
            <?php 
            date_default_timezone_set("Asia/Jakarta");
            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('ruangan/simpan',$arrayName);?>
             <div class="form-row">
              <div class="form-group col-md-3">
                  <label for="tgl">Nama Ruangan</label>
                  <input type="text" class="form-control" name="narung" id="narung" required="required">
                   <?php  echo form_error('nopol'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nama Ruangan
                  </div>
              </div>
            
              <div class="form-group col-md-3">
                  <label for="tglpjk">Lantai Ruangan</label>
                  <select name="lantai" class="form-control" id="lantai">
                      <option value="">Pilih Lantai</option>                     
                      <?php
                        for ($i=1; $i <5; $i++) { ?>
                          <option value="<?php echo sprintf("%02d", $i)?>">Lantai <?php echo sprintf("%02d", $i);?></option>                     
                      <?php
                        }
                      ?>                     
                  </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                  <label for="tgl">Kapasitas</label>
                  <input type="number" class="form-control" name="kapsi" id="kapsi" required="required">
                   <?php  echo form_error('merk'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Kapasitas Ruangan
                  </div>
              </div>
            <div class="form-group col-md-3">
                <label>Status Ruangan</label>
                <select name="stsru" class="form-control select2" id="stsru" required="required">                    
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





            

           
            <div class="form-group">
              <label>Keterangan Ruangan</label>
            <textarea class="form-control "  name="ket" ><?php echo set_value('dasrut');?></textarea>
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
            </div>
           <div class="form-group">
              <label>Dokumen Ruangan</label>
              <input type="file" class="form-control" name="dokumen" id="dokumen" accept="application/pdf">
              <div class="invalid-feedback">
                        Silahkan Upload Ruangan
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
  $(document).ready(function() {

    $("input:checkbox").on('click', function() {
      // in the handler, 'this' refers to the box clicked on
      var $box = $(this);
      if ($box.is(":checked")) {
        // the name of the box is retrieved using the .attr() method
        // as it is assumed and expected to be immutable
        var group = "input:checkbox[name='" + $box.attr("name") + "']";
        // the checked state of the group/box on the other hand will change
        // and the current value is retrieved using .prop() method
        $(group).prop("checked", false);
        $box.prop("checked", true);
      } else {
        $box.prop("checked", false);
      }
    });

    $("#nmpenumpang").select2({
      maximumSelectionLength: 3
    });
  //$("#pjm_mobil").hide();
 
  //var x = document.getElementById("timin");
  //x.style.display = "block";
  $("#tgl").change(function() {
    var p = $("#tgl").val();
    $("#tglin").attr("min", p)
  });
  $("#tglin").change(function() {
    var p = $("#tglin").val();
    $("#tglot").attr("min", p)
  });
  $("#tglot").change(function() {
    var y = $("#tglot").val();
    $("#tglin").attr("max", y)
  });
  $(".inputan").change(function() {
    var tgl = $("#tgl").val();
    var jns = $("#jns").val();
    var tmin = $("#timin").val();
    var tmot = $("#timot").val();
    var ini = $("#tglin").val();
    var oto = $("#tglot").val();
    if ( (tgl == null || tgl=="") || (ini == null || ini=="") || (oto == null || oto=="") ) {
      document.getElementById("dfpinjam").selected = "true";
      alert("Silahkan Isi Tanggal Peminjaman\n Atau Tanggal Ajuan Terlebih Dahulu");

    }else{
            if (jns=='a1') {
               $("#itma").html('');
              document.getElementById("tjn").removeAttribute("hidden");
              document.getElementById("mbl-perlu").removeAttribute("hidden");
              //document.getElementById("btn-mobil").removeAttribute("hidden");

              //menghilangkan jam pada peminjaman ruangan
              document.getElementById("timin").removeAttribute("required");
              document.getElementById("timot").removeAttribute("required");
              document.getElementById("timin").setAttribute("hidden", "TRUE");
              document.getElementById("timot").setAttribute("hidden", "TRUE");

              var tjn = $("#tjn").val();
              var prl = $("#mbl-perlu").val();

              if (tjn !="" && prl !="") {

                  $.ajax({
                  url: "<?php echo base_url()?>pinjam/item_mobil",
                  type: "post",
                  data: {
                    kode: jns,
                    tjn: tjn,
                    prl: prl,
                    ini: ini,
                    oto: oto,
                  },
                  success: function(msg) {
                    //document.getElementById("demo").innerHTML=msg;
                    $("#itma").html(msg)
                  }
                })
              }
                
            }
            else if (jns=='a2') {
              document.getElementById("timin").removeAttribute("hidden");
              document.getElementById("timot").removeAttribute("hidden"); 

              document.getElementById("tjn").setAttribute("hidden", "TRUE");
              document.getElementById("mbl-perlu").setAttribute("hidden", "TRUE");
              document.getElementById("ketperlu").setAttribute("hidden", "TRUE");
              //document.getElementById("btn-mobil").setAttribute("hidden", "TRUE");

              document.getElementById("timin").setAttribute("required", "required");
              document.getElementById("timot").setAttribute("required", "required");
              alert("Silahkan Isi Jam Peminjaman");
            }else{
                //$("#pjm_mobil").hide();
              document.getElementById("timin").setAttribute("hidden", "TRUE");
              document.getElementById("timot").setAttribute("hidden", "TRUE");
              document.getElementById("tjn").setAttribute("hidden", "TRUE");
              document.getElementById("mbl-perlu").setAttribute("hidden", "TRUE");
              document.getElementById("ketperlu").setAttribute("hidden", "TRUE");
              //document.getElementById("btn-mobil").setAttribute("hidden", "TRUE");


              document.getElementById("timin").removeAttribute("required");
              document.getElementById("timot").removeAttribute("required");
              var clearHTML ="";
              $("#itma").html(clearHTML);
              //$('#itm').val(null).trigger('change');
              //document.getElementById("itm").selectedIndex = "0"; 
//              document.getElementById("itm").selecElement.selectedIndex = 0;
            }
             /*$.ajax({
              url: "<?php //echo base_url()?>pinjam/item",
              type: "post",
              data: {
                kode: jns,
                tmin: tmin,
                tmot: tmot,
                ini: ini,
                oto: oto,
              },
              success: function(msg) {
                $("#itm").html(msg)
              }
            })*/
    }

  });
  $("#mbl-perlu").change(function() {
    var j = $("#mbl-perlu").val();
    if (j == 4) {
      document.getElementById("ketperlu").removeAttribute("hidden");
    }else{
      document.getElementById("ketperlu").setAttribute("hidden", "TRUE");
    }
  });


  $("#tglin").change(function() {
    var jns = $("#jns").val();
    var tmin = $("#timin").val();
    var tmot = $("#timot").val();
    var ini = $("#tglin").val();
    var oto = $("#tglot").val();
    $.ajax({
      url: "<?php echo base_url()?>pinjam/item",
      type: "post",
      data: {
        kode: jns,
        tmin: tmin,
        tmot: tmot,
        ini: ini,
        oto: oto,
      },
      success: function(msg) {
        $("#itm").html(msg)
      }
    })
  });

  $("#tglot").change(function() {
    var jns = $("#jns").val();
    var tmin = $("#timin").val();
    var tmot = $("#timot").val();
    var ini = $("#tglin").val();
    var oto = $("#tglot").val();
    $.ajax({
      url: "<?php echo base_url()?>pinjam/item",
      type: "post",
      data: {
        kode: jns,
        tmin: tmin,
        tmot: tmot,
        ini: ini,
        oto: oto,
      },
      success: function(msg) {
        $("#itm").html(msg)
      }
    })
  });



   $("#jnsa").change(function() {
    var tgl = $("#tgl").val();
    var jns = $("#jns").val();
    var tmin = $("#timin").val();
    var tmot = $("#timot").val();
    var ini = $("#tglin").val();
    var oto = $("#tglot").val();
    if ( (tgl == null || tgl=="") || (ini == null || ini=="") || (oto == null || oto=="") ) {
      document.getElementById("dfpinjam").selected = "true";
      alert("Silahkan Isi Tanggal Peminjaman\n Atau Tanggal Ajuan Terlebih Dahulu");

    }else{
            if (jns=='a1') {
              document.getElementById("tjn").removeAttribute("hidden");
              document.getElementById("mbl-perlu").removeAttribute("hidden");
              document.getElementById("btn-mobil").removeAttribute("hidden");

              document.getElementById("timin").removeAttribute("required");
              document.getElementById("timot").removeAttribute("required");
              document.getElementById("timin").setAttribute("hidden", "TRUE");
              document.getElementById("timot").setAttribute("hidden", "TRUE");
            }
            else if (jns=='a2') {
              document.getElementById("timin").removeAttribute("hidden");
              document.getElementById("timot").removeAttribute("hidden"); 

              document.getElementById("tjn").setAttribute("hidden", "TRUE");
              document.getElementById("mbl-perlu").setAttribute("hidden", "TRUE");
              document.getElementById("ketperlu").setAttribute("hidden", "TRUE");
              d
              document.getElementById("timin").setAttribute("required", "required");
              document.getElementById("timot").setAttribute("required", "required");
              alert("Silahkan Isi Jam Peminjaman");
            }else{
                //$("#pjm_mobil").hide();
              document.getElementById("timin").setAttribute("hidden", "TRUE");
              document.getElementById("timot").setAttribute("hidden", "TRUE");
              document.getElementById("tjn").setAttribute("hidden", "TRUE");
              document.getElementById("mbl-perlu").setAttribute("hidden", "TRUE");
              document.getElementById("ketperlu").setAttribute("hidden", "TRUE");
              


              document.getElementById("timin").removeAttribute("required");
              document.getElementById("timot").removeAttribute("required");
              
            }
             /*$.ajax({
              url: "<?php //echo base_url()?>pinjam/item",
              type: "post",
              data: {
                kode: jns,
                tmin: tmin,
                tmot: tmot,
                ini: ini,
                oto: oto,
              },
              success: function(msg) {
                $("#itm").html(msg)
              }
            })*/
    }

  });
  
})


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


