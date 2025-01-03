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
            <h4>Input Peminjaman </h4>
            
          </div>
          <div class="card-body">

            <?php $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('pinjam/save_pinjam',$arrayName);?>
             <div class="form-row">
              <div class="form-group col-md-3">
                  <?php 
                      date_default_timezone_set("Asia/Jakarta");
                      $tgloi= date("Y-m-d");
                      $tmput= date("H:i");
                      $currentHour = substr($tmput, 0, 2); // Extract current hour
                  ?>
                  <label for="tgl">Tanggal Peminjaman</label>
                  <input type="date" class="form-control" min="<?php echo $tgloi;?>" value="<?php echo set_value('tgl'); ?>" name="tgl" id="tgl"  required="required">
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal Pengajuan
                  </div>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group col-md-3">
                  <label for="tglin">Tangal Awal Peminjaman</label>
                  <input type="date" class="form-control tglchg" min="<?php echo $tgloi;?>" value="<?php echo set_value('tglin'); ?>" name="tglin" id="tglin" required="required" >
                   <?php  echo form_error('tgl'); ?>
                   <select name="timin" class="form-control inputan" id="timin" hidden="TRUE" required="required">
										<option value="">Pilih Jam</option>
										<?php for ($i = $currentHour; $i < 24; $i++) { ?>
												<option value="<?php echo sprintf("%02d:00", $i); ?>"><?php echo sprintf("%02d:00", $i); ?></option>
										<?php } ?>
								</select>
              </div>

              <div class="form-group col-md-3">
                  <label for="tglot">Tanggal Akhir Peminjaman</label>
                  <input type="date" class="form-control tglchg"  name="tglot" id="tglot" required="required" >
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal Akhir dan Awal SPT
                  </div>
                  <select name="timot" class="form-control inputan" id="timot" hidden="TRUE" required="required">
										<option value="">Pilih Jam</option>

										<?php for ($i =$currentHour ; $i < 24; $i++) { ?>
												<option value="<?php echo sprintf("%02d:00", $i); ?>"><?php echo sprintf("%02d:00", $i); ?></option>
										<?php } ?>
								</select>
              </div>
            </div>

            <div class="form-row" >
            <div class="form-group col-md-12">
                <label>Jenis Peminjaman</label>
                <select name="jns" style="width:100%"  class="form-control select2 inputan" id="jns" required="required">                    
                      <option value="" id="dfpinjam">Pilih Jenis Pinjaman</option>                     
                      <option value="a1">Mobil</option>                     
                      <option value="a2">Ruangan</option>                     
                      <option value="a3">Elektonik/Alat</option>                     
                </select>


                <select name="tjn" class="form-control inputan" id="tjn" hidden="TRUE">                    
                  <option value="">Pilih Tujuan Pinjaman</option>                     
                  <option value="1">Dalam Kota</option>                     
                  <option value="2">Dalam Daerah</option>                     
                  <option value="3">Luar Daerah</option>                     
                </select>


                <select name="mbl-perlu" class="form-control inputan" id="mbl-perlu" hidden="TRUE">                    
                  <option value="">Pilih Keperluan</option>                     
                  <option value="1">Dinas Luar Biasa</option>                     
                  <option value="2">Fasilitasi Kunjungan Tamu</option>                     
                  <option value="3">Rangkaian Keg. Pimpinan Daerah</option>                     
                  <option value="4">Kegiatan Lainnya</option>                     
                </select>
                 <input type="text" name="ketperlu" id="ketperlu" placeholder="Keterangan Keperluan Lainnya" class="form-control" hidden="TRUE">
                 
                 <?php  echo form_error('peminjaman'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Jenis Peminjaman
                  </div>
                
                 <!--  <button class="btn btn-info col-md-12" type="button" id="btn-mobil" hidden="TRUE">Cari Mobil</button> -->
            </div>
            </div>

            

            <div class="form-row">
            <div class="form-group col-md-12">
                <label>Item Peminjaman</label>
                <!-- <div id="itma" class='row gutters-sm'> -->
                <div id="itma" class=''>
                </div>
                 <?php  echo form_error('peminjaman'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Item
                  </div>
            </div>
            </div>

            <div class="form-group">
              <label>Keterangan Peminjaman</label>
            <textarea class="form-control inputan"  name="ket" ><?php echo set_value('dasrut');?></textarea>
               <?php  echo form_error('ket'); ?>
              <div class="invalid-feedback">
                        Silahkan Input Tujuan Peminjaman dan keterangan lainnya
              </div>
            </div>
            <div class="form-row" id="mbl" hidden="TRUE">
              <div class="form-group col-md-12" >
                  <label>Nama Driver/Sopir</label>
                  <select name="drv" class="form-control select2" style="width:100%" id="drv" required="required" >                    
                        <option value="NULL" >Pilih Nama Driver</option>                     
                        <?php
                          foreach ($namaspt as $key ) { 
                            echo "<option value=".$key->id.">".base64_decode($key->nama)."</option>";
                          }
                        ?>                     
                  </select>
              </div>

             <div class="form-group col-md-12"  >
                <label>Nama Penumpang</label>
                <select name="nmpenumpang[]" class="form-control select2" style="width:100%" id="nmpenumpang" required="required" multiple="" >
                      <option value="NULL">Plih Nama Penumpang</option>
                      <?php
                        foreach ($namaspt as $key ) { 
                          echo "<option value=".$key->id.">".base64_decode($key->nama)."</option>";
                        }
                      ?>                     
                </select>
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
  function gantitgla(val) {
    alert("coba Alert");
    

   
  }
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
  /*$("#tglin").change(function() {
    var p = $("#tglin").val();
    $("#tglot").attr("min", p)
  });
  $("#tglot").change(function() {
    var y = $("#tglot").val();
    $("#tglin").attr("max", y)
  });*/
  

  $(".tglchg").change(function() {
    $("#itma").html('');
    
    document.getElementById("tjn").setAttribute("hidden", "TRUE");
    document.getElementById("mbl-perlu").setAttribute("hidden", "TRUE");
    //$("#dfpinjam").setAttribute("selected='selected'", "TRUE");
    //document.getElementById("jns").setAselectedIndex=-1;
    //document.getElementById("dfpinjam").setAttribute("selected='selected'", "TRUE");
    //document.getElementById("dfpinjam").selected = "true";
     $('#jns').val(null).trigger('change');
    //$("#jns").selecElement.selectedIndex = -1;
  });


  $(".inputan").change(function() {
    var tgl = $("#tgl").val();
    var jns = $("#jns").val();
    
    var ini = $("#tglin").val();
    var oto = $("#tglot").val();
    if ( (tgl == null || tgl=="") || (ini == null || ini=="") || (oto == null || oto=="") && jns !="" ) {
      document.getElementById("dfpinjam").selected = "true";
      alert("Silahkan Isi Tanggal Peminjaman\n Atau Tanggal Ajuan Terlebih Dahulu");

    }else{
            if (jns=='a1') {
               $("#itma").html('');
              document.getElementById("tjn").removeAttribute("hidden");
              document.getElementById("mbl-perlu").removeAttribute("hidden");
              
              document.getElementById("mbl").removeAttribute("hidden");
              //document.getElementById("drv").removeAttribute("hidden");
              //document.getElementById("nmpenumpang").removeAttribute("hidden");
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
                    $("#itma").html(msg)
                  }
                })
              }
                
            }
            else if (jns=='a2') {
              $("#itma").html('');
              document.getElementById("timin").removeAttribute("hidden");
              document.getElementById("timot").removeAttribute("hidden"); 

              document.getElementById("tjn").setAttribute("hidden", "TRUE");
              document.getElementById("mbl-perlu").setAttribute("hidden", "TRUE");
              document.getElementById("ketperlu").setAttribute("hidden", "TRUE");

              
              document.getElementById("drv").removeAttribute("required");
              document.getElementById("nmpenumpang").removeAttribute("required");
              
              document.getElementById("mbl").setAttribute("hidden", "TRUE");
              //var tmin = $("#timin").val();
              //var tmot = $("#timot").val();
              
              var tmin = $('#timin').val();
              var tmot = $('#timot').val();

              
                if (validateTimes() !="") {
                  
                  $.ajax({
                      url: "<?php echo base_url()?>pinjam/item_ruang",
                      type: "post",
                      data: {
                          kode: jns,
                          ini: ini,
                          oto: oto,
                          tmin: tmin, 
                          tmot: tmot,
                      },
                      success: function(msg) {
                          $("#itma").html(msg);
                      }
                  });
              }
                
              
            }else if (jns=='a3') {
              $("#itma").html('');
              document.getElementById("timin").removeAttribute("hidden");
              document.getElementById("timot").removeAttribute("hidden"); 

              document.getElementById("tjn").setAttribute("hidden", "TRUE");
              document.getElementById("mbl-perlu").setAttribute("hidden", "TRUE");
              document.getElementById("ketperlu").setAttribute("hidden", "TRUE");
              
              document.getElementById("mbl").setAttribute("hidden", "TRUE");

              
              document.getElementById("drv").removeAttribute("required");
              document.getElementById("nmpenumpang").removeAttribute("required");
              var tmin = $("#timin").val();
              var tmot = $("#timot").val();

                //validateTimes();
                if (validateTimes()) {
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
            }
            else{
                //$("#pjm_mobil").hide();
              document.getElementById("timin").setAttribute("hidden", "TRUE");
              document.getElementById("timot").setAttribute("hidden", "TRUE");
              document.getElementById("tjn").setAttribute("hidden", "TRUE");
              document.getElementById("mbl-perlu").setAttribute("hidden", "TRUE");
              document.getElementById("ketperlu").setAttribute("hidden", "TRUE");
              
              document.getElementById("mbl").setAttribute("hidden", "TRUE");
              
              document.getElementById("timin").removeAttribute("required");
              document.getElementById("timot").removeAttribute("required");
              var clearHTML ="";
              $("#itma").html(clearHTML);
              }
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

          if (ini &&  oto && timotVal === timinVal) {
              alert('Waktu selesai tidak boleh kurang dari atau sama dengan waktu mulai.');
              $('#timot').val('');
              isValid = false;
          }
      }
      return isValid;
  }

  $("#mbl-perlu").change(function() {
    var j = $("#mbl-perlu").val();
    if (j == 4) {
      document.getElementById("ketperlu").removeAttribute("hidden");
    }else{
      document.getElementById("ketperlu").setAttribute("hidden", "TRUE");
    }
  });
})
</script>