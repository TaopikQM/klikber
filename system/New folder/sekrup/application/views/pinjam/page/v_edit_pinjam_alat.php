
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<!-- Template CSS -->

<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
<!-- Custom style CSS -->

<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>

<style type="text/css">
  .timein{display: none;} 

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
            <h4>Edit Peminjaman</h4>
            
          </div>
          <div class="card-body">
            <?php
              /*echo "<pre>";
              echo print_r($edit);
              echo "</pre>";*/
              foreach ($edit as $kgt) {
                $nopinjam=$kgt->nopinjam;
                $tglpinjam=$kgt->tglaju;
                $tglin=$kgt->tglin;
                $tglot=$kgt->tglot;
                $timein=$kgt->timein;
                $timeot=$kgt->timeot;
                $nmalatx=$kgt->nmbarang;
                $jnspinjam=$kgt->jnspinjam;
                $itmpinjam=$kgt->itmpinjam;
                $ket=$kgt->ket;
                $idpin=$kgt->id_pjm;
              }
              
            ?>
            <?php  
                $akhir=':00';
                if (strlen($timein) === 1) {
                    $timein = sprintf("%02d", $timein); 
                }
                if (strlen($timeot) === 1) {
                    $timeot = sprintf("%02d", $timeot); 
                }
                $jmin= $timein."$akhir";
                $jmot= $timeot."$akhir";
            ?>
            <?php $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('pinjam/save_e_pinjam_alat',$arrayName);?>
             <div class="form-row">
              <div class="form-group col-md-12">
                  <label for="tgl">No Peminjaman</label>
                  <input type="text" class="form-control"  value="<?php echo $nopinjam; ?>" name="nopin" id="nopin" disabled="disabled">
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal Pengajuan
                  </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                  <?php
                      date_default_timezone_set("Asia/Jakarta");
                      $tgloi= date("Y-m-d");
                      $tmput= date("H:i");
                      $currentHour = substr($tmput, 0, 2); // Extract current hour
                  
                  ?>
                  <label for="tgl">Tanggal Peminjaman</label>
                  <input type="date" class="form-control" min="<?php echo $tglpinjam;?>" value="<?php echo $tglpinjam; ?>" name="tgl" id="tgl" disabled="disabled">
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal Pengajuan
                  </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-3">
                  <label for="tglin">Tangal Awal Peminjaman</label>
                  <input type="date" class="form-control inputan" min="<?php echo $tglpinjam;?>" value="<?php echo date('Y-m-d',$tglin); ?>" name="tglin" id="tglin" required="required">
                   <?php  echo form_error('tgl'); ?>
                   <select name="timin" class="form-control inputan" id="timin" required="required">
                        <option value="">Pilih Jam</option>
                        <?php for ($i = 00; $i < 24; $i++) { 
                           $timeOption = sprintf("%02d:00", $i);
                           // Tambahkan logika untuk memeriksa apakah $timeOption sesuai dengan $jmin
                           $selected = ($timeOption == $jmin) ? 'selected' : '';
                           ?>
                                <option value="<?php echo $timeOption; ?>" <?php echo $selected; ?>><?php echo $timeOption; ?></option>
                        <?php } ?>
                    </select>
                 
              </div>

              <div class="form-group col-md-3">
                  <label for="tglot">Tanggal Akhir Peminjaman</label>
                  <input type="date" class="form-control inputan" min="" value="<?php echo date('Y-m-d',$tglot); ?>" name="tglot" id="tglot" required="required">
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal Akhir dan Awal SPT
                  </div>
                  <select name="timot" class="form-control inputan" id="timot"   required="required">
                        <option value="">Pilih Jam</option>

                        <?php for ($i =00 ; $i < 24; $i++) { 
                           $timeOption = sprintf("%02d:00", $i);
                           // Tambahkan logika untuk memeriksa apakah $timeOption sesuai dengan $jmin
                           $selected = ($timeOption == $jmot) ? 'selected' : '';
                           ?>
                               <option value="<?php echo $timeOption; ?>" <?php echo $selected; ?>><?php echo $timeOption; ?></option>
                        <?php } ?>
                    </select>
                  
              </div>
            </div>

            <div class="form-row" >
            <div class="form-group col-md-12">
                <label>Jenis Peminjaman</label>
                <select name="jns" class="form-control select2 inputan" id="jns" required="required" disabled="disabled" >                    
                      <option value="NULL" id="dfpinjam">Pilih Jenis Pinjaman</option>                     
                      <option value="a3" <?php if ($jnspinjam=="a3") { echo "selected=selected"; } ?> >Alat</option>                                       
                </select>
                
                
                 <?php  echo form_error('peminjaman'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Jenis Peminjaman
                  </div>
                
                 <!--  <button class="btn btn-info col-md-12" type="button" id="btn-mobil" hidden="TRUE">Cari Mobil</button> -->
            </div>
            </div>

            <div  class="form-row">
               
            <div class="form-group col-md-12">
                <label>Item Peminjaman</label>
                <div id="itma" class="row gutters-sm">
                  <div class="col-4 col-sm-2">
                  <label class="imagecheck mb-4">
                    <input type="radio" name="itm" value="<?php echo $itmpinjam;?>" class="imagecheck-input" checked>
                    <span class="imagecheck-figure">
                      <div id="carouselExampleIndicators0" class="carousel slide" data-ride="carousel">  
                       <?php 
                          $bdh=str_replace(" ","",$nmalatx);
                          $fold = "harta/pinjam/dataalat/".strtoupper($bdh);
                          $getdc =  glob($fold."/*.*"); ?>          
                                <div class="carousel-inner"><?php
                                  for ($iw=0; $iw < count($getdc) ; $iw++) { 
                                    $ftp ="";
                                    if ($iw==0) {
                                      $ftp="active";
                                    } ?>
                                    <div class="carousel-item <?php echo $ftp;?>">
                                      <img class="d-block w-100" src="<?php echo base_url().$getdc[$iw];?>" alt="<?php echo $nmalatx;?>" witdh="100" height="200">
                                          <div class="carousel-caption d-none d-md-block">
                                            <span class="badge badge-primary"><?php echo $nmalatx; ?></span>
                                  </div>
                                    </div>;
                                  <?php }?>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleIndicators0" role="button"
                                  data-slide="prev">
                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                  <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators0" role="button"
                                  data-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="sr-only">Next</span>
                                </a>
                          </div>
                    </span>
                  </label>
                  </div>

                <?php $aw=1; 
                  foreach ($lisalat as $keya){
                  $hbg=str_replace(" ","",$keya->nmbarang);
                  $folder = "harta/pinjam/dataalat/".strtoupper($hbg);
                      $handle =  glob($folder."/*.*"); ?>
                      <div class="col-4 col-sm-2">
                      <label class="imagecheck mb-4">
                            <input type="radio" name="itm" value="<?php echo $keya->id;?>" class="imagecheck-input" <?php if ($itmpinjam==$keya->id) {
                              echo "cheked='chaked'";
                            } ?>>
                                  <span class="imagecheck-figure">
                          <div id="carouselExampleIndicators<?php echo $aw;?>" class="carousel slide" data-ride="carousel">            
                                    <div class="carousel-inner"><?php
                                      for ($ib=0; $ib < count($handle) ; $ib++) { 
                                        $drw ="";
                                        if ($ib==0) {
                                          $drw="active";
                                        } ?>
                                        <div class="carousel-item <?php echo $drw;?>">
                                          <img class="d-block w-100" src="<?php echo base_url().$handle[$ib];?>" alt="<?php echo $keya->nmbarang;?>" witdh="100" height="200">
                                              <div class="carousel-caption d-none d-md-block">
                                                <span class="badge badge-primary"><?php echo $keya->nmbarang; ?></span>
                                      </div>
                                        </div>;
                                      <?php }?>
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleIndicators<?php echo $aw;?>" role="button"
                                      data-slide="prev">
                                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                      <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleIndicators<?php echo $aw;?>" role="button"
                                      data-slide="next">
                                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                      <span class="sr-only">Next</span>
                                    </a>
                              </div>
                              </span>
                            </label>
                          </div>
                <?php
                      $aw++;
                }
                    
                echo form_error('peminjaman'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Item
                  </div>

                </div>
            </div>
            </div>

            <div class="form-group">
              <label>Keterangan Peminjaman</label>
                <textarea class="form-control "  name="ket" ><?php echo $ket;?></textarea>
               <?php  echo form_error('ket'); ?>
              <div class="invalid-feedback">
                        Silahkan Input Tujuan Peminjaman dan keterangan lainnya
              </div>
            </div>
            </div>
            
            <div class="card-footer text-center">
              <input type="hidden" name="idpinjam" value="<?php echo $idpin;?>">
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
 
  $("#tgl").change(function() {
    var p = $("#tgl").val();
    $("#tglin").attr("min", p);
  });
  $("#tglin").change(function() {
    var p = $("#tglin").val();
    $("#tglot").attr("min", p);

    var timin = $("#timin");
    var currentHour = new Date().getHours();
    if (p === '<?php echo $tgloi; ?>') {
      timin.empty().append('<option value="">Pilih Jam</option>');
      for (var i = currentHour; i < 24; i++) {
        timin.append('<option value="' + (i < 10 ? '0' : '') + i + ':00">' + (i < 10 ? '0' : '') + i + ':00</option>');
      }
    } else {
      timin.empty().append('<option value="">Pilih Jam</option>');
      for (var i = 0; i < 24; i++) {
        timin.append('<option value="' + (i < 10 ? '0' : '') + i + ':00">' + (i < 10 ? '0' : '') + i + ':00</option>');
      }
    }
    timin.show();
  });

  $("#tglot").change(function() {
    var p = $("#tglot").val();
    var timot = $("#timot");
    timot.empty().append('<option value="">Pilih Jam</option>');
    for (var i = 0; i < 24; i++) {
      timot.append('<option value="' + (i < 10 ? '0' : '') + i + ':00">' + (i < 10 ? '0' : '') + i + ':00</option>');
    }
    timot.show();
  });
  /*$("#mbl-perlu").change(function() {
     document.getElementById("ketperlu").removeAttribute("hidden");
  });*/


  $(".inputan").change(function() {
    //alert("COBA INPUTAN");
    var tgl = $("#tgl").val();
    var jns = $("#jns").val();
    var tmin = $("#timin").val();
    var tmot = $("#timot").val();
    var ini = $("#tglin").val();
    var oto = $("#tglot").val();
    
        if (validateTimes()) {
          $("#itma").html('');
          $.ajax({
              url: "<?php echo base_url()?>pinjam/item_alat",
              type: "post",
              data: {
                  kode: jns,
                  tmin: tmin, 
                  tmot: tmot,
                  ini: ini,
                  oto: oto,
              },
              success: function(msg) {
                  $("#itma").html(msg);
              }
          });
      }
  });
  function validateTimes() {
      var ini = $('#tglin').val();
      var oto = $('#tglot').val();
      var tmin = $('#timin').val();
      var tmot = $('#timot').val();
      var now = new Date();
      var isValid = true;

      if (tmin === "" || tmot === "") {
          alert('Silahkan isi semua bidang selesai.');
          isValid = false;
      } else {
          var tglinDate = new Date(ini);
          var tglotDate = new Date(oto);
          var timinVal = parseInt(tmin.split(':')[0]);
          var timotVal = parseInt(tmot.split(':')[0]);

          if (tglotDate < tglinDate) {
              alert('Tanggal akhir tidak boleh kurang dari tanggal awal.');
              isValid = false;
          }

          if (tglinDate.getTime() === new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime() && timinVal <= now.getHours()) {
              alert('Waktu mulai tidak boleh kurang dari atau sama dengan waktu sekarang.');
              $('#timin').val('');
              isValid = false;
          }

          if (ini === oto && timotVal <= timinVal) {
              alert('Waktu selesai tidak boleh kurang dari atau sama dengan waktu mulai.');
              $('#timot').val('');
              isValid = false;
          }
      }
      return isValid;
  }
     
</script>

<script src="<?php echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/owl-carousel.js"></script>