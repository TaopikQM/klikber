
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
                $nopolx=$kgt->nopol;
                $jnspinjam=$kgt->jnspinjam;
                $itmpinjam=$kgt->itmpinjam;
                $ket=$kgt->ket;
                $idpin=$kgt->id_pinjam;
                $perlu=$kgt->mbl_perlu;
                $ketperlu=$kgt->mbl_ket_perlu;
                $tujuan=$kgt->mbl_tujuan;
                $iddrv=$kgt->drv;
                $dtpenum=$kgt->penumpang;
              }
              
            ?>
            <?php $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('pinjam/save_e_pinjam_mobil',$arrayName);?>
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
                 
              </div>

              <div class="form-group col-md-3">
                  <label for="tglot">Tanggal Akhir Peminjaman</label>
                  <input type="date" class="form-control inputan" min="" value="<?php echo date('Y-m-d',$tglot); ?>" name="tglot" id="tglot" required="required">
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal Akhir dan Awal SPT
                  </div>
                  
              </div>
            </div>

            <div class="form-row" >
            <div class="form-group col-md-12">
                <label>Jenis Peminjaman</label>
                <select name="jns" class="form-control select2 inputan" id="jns" required="required" disabled="disabled" >                    
                      <option value="NULL" id="dfpinjam">Pilih Jenis Pinjaman</option>                     
                      <option value="a1" <?php if ($jnspinjam=="a1") { echo "selected=selected"; } ?> >Mobil</option>                                       
                </select>
                <select name="tjn" class="form-control inputan" id="tjn" >                    
                  <option value="">Pilih Tujuan Pinjaman</option>                     
                  <option value="1" <?php if ($tujuan=="1") { echo "selected=selected"; } ?>>Dalam Kota</option>                     
                  <option value="2" <?php if ($tujuan=="2") { echo "selected=selected"; } ?> >Dalam Daerah</option>                     
                  <option value="3" <?php if ($tujuan=="3") { echo "selected=selected"; } ?> >Luar Daerah</option>                     
                </select>
                <select name="mbl-perlu" class="form-control inputan" id="mbl-perlu" >                    
                  <option value="">Pilih Keperluan</option>                     
                  <option value="1" <?php if ($perlu=="1") { echo "selected=selected"; } ?> >Dinas Luar Biasa</option>                     
                  <option value="2" <?php if ($perlu=="2") { echo "selected=selected"; } ?> >Fasilitasi Kunjungan Tamu</option>                     
                  <option value="3" <?php if ($perlu=="3") { echo "selected=selected"; } ?>>Rangkaian Keg. Pimpinan Daerah</option>                     
                  <option value="4" <?php if ($perlu=="4") { echo "selected=selected"; } ?> >Kegiatan Lainnya</option>                     
                </select>
                <?php  
                  if ($ketperlu!=NULL) { ?>
                  <input type="text" name="ketperlu" id="ketperlu" placeholder="Keterangan Keperluan Lainnya" class="form-control" value="<?php echo $ketperlu?>">
                <?php
                  }else{?>
                  <input type="text" name="ketperlu" id="ketperlu" placeholder="Keterangan Keperluan Lainnya" class="form-control" hidden="TRUE">
                <?php    
                  }
                ?>
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
                          $bdh=str_replace(" ","",$nopolx);
                          $fold = "harta/pinjam/datamobil/".$bdh;
                          $getdc =  glob($fold."/*.*"); ?>          
                                <div class="carousel-inner"><?php
                                  for ($iw=0; $iw < count($getdc) ; $iw++) { 
                                    $ftp ="";
                                    if ($iw==0) {
                                      $ftp="active";
                                    } ?>
                                    <div class="carousel-item <?php echo $ftp;?>">
                                      <img class="d-block w-100" src="<?php echo base_url().$getdc[$iw];?>" alt="<?php echo $nopolx;?>" witdh="100" height="200">
                                          <div class="carousel-caption d-none d-md-block">
                                            <span class="badge badge-primary"><?php echo $nopolx; ?></span>
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
                  foreach ($lismobil as $keya){
                  $hbg=str_replace(" ","",$keya->nopol);
                  $folder = "harta/pinjam/datamobil/".$hbg;
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
                                          <img class="d-block w-100" src="<?php echo base_url().$handle[$ib];?>" alt="<?php echo $keya->nopol;?>" witdh="100" height="200">
                                              <div class="carousel-caption d-none d-md-block">
                                                <span class="badge badge-primary"><?php echo $keya->nopol; ?></span>
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
            <div class="form-row" >
            <div class="form-group col-md-12">
                <label>Nama Driver/Sopir</label>
                <select name="drv" class="form-control select2" id="drv" required="required">                    
                      <option value="NULL" >Pilih Nama Driver</option>                     
                      <?php
                        foreach ($namaspt as $kyw ) { ?>
                          <option value="<?php echo $kyw->id;?>" <?php if ($kyw->id==$iddrv) {
                            echo "selected='selected'";
                          } ?> ><?php echo base64_decode($kyw->nama);?> </option>;
                      <?php  }
                      ?>                     
                </select>
            </div>

             <div class="form-group col-md-12">
                <label>Nama Penumpang</label>
                <select name="nmpenumpang[]" class="form-control select2" id="nmpenumpang" required="required" multiple="">
                      <option value="NULL">Plih Nama Penumpang</option>
                      <?php
                        $hsh=explode("-",trim($dtpenum,"-"));
                        foreach ($namaspt as $kty ) { ?>
                          <option value="<?php echo $kty->id; ?>" <?php for ($ac=0; $ac < count($hsh) ; $ac++) { 
                            if ($kty->id==$hsh[$ac]) {
                              echo "selected='selected'";
                            }
                          } ?> ><?php echo  base64_decode($kty->nama)?> </option>;
                       <?php }
                      ?>                     
                </select>
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
  $(document).ready(function() {

    $("#nmpenumpang").select2({
      maximumSelectionLength: 3
    });
  //$("#pjm_mobil").hide();
 
  //var x = document.getElementById("timin");
  //x.style.display = "block";
  $("#tgl").change(function() {
    var p = $("#tgl").val();
    $("#tglin").attr("min", p);
  });
  $("#tglin").change(function() {
    var p = $("#tglin").val();
    $("#tglot").attr("min", p);
  });
  $("#tglot").change(function() {
    var y = $("#tglot").val();
    $("#tglin").attr("max", y);
  });
  /*$("#mbl-perlu").change(function() {
     document.getElementById("ketperlu").removeAttribute("hidden");
  });*/
  $(".inputan").change(function() {
    alert("COBA INPUTAN");
    var tgl = $("#tgl").val();
    var jns = $("#jns").val();
  
    var ini = $("#tglin").val();
    var oto = $("#tglot").val();
    var tjn = $("#tjn").val();
    var prl = $("#mbl-perlu").val();

    if (tjn !="" && prl !="") {
        $("#itma").html('');
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
          $("#itma").html(msg)
        }
      })
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
    

  });
  $("#mbl-perlu").change(function() {
    var j = $("#mbl-perlu").val();
    if (j == 4) {
      document.getElementById("ketperlu").removeAttribute("hidden");
    }else{
      document.getElementById("ketperlu").setAttribute("hidden", "TRUE");
    }
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
</script>

<script src="<?php echo base_url()?>harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/owl-carousel.js"></script>