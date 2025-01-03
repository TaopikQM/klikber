
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<!-- Template CSS -->

<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
<!-- Custom style CSS -->

<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>


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
            <h4>Input Surat Perintah Tugas (SPT) </h4>
            
          </div>
          <div class="card-body">

            <?php $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('morsip/save_spt',$arrayName);?>
             <div class="form-row">
              <div class="form-group col-md-3">
                  <?php
                      date_default_timezone_set("Asia/Jakarta");
                      $tgloi= date("Y-m-d");
                  ?>
                  <label for="tgl">Tanggal Surat</label>
                  <input type="date" class="form-control" min="<?php echo $tgloi;?>" value="<?php echo set_value('tgl'); ?>" name="tgl" id="tgl" required="required">
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal SPT
                  </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                  <label for="tglin">Tangal Awal SPT</label>
                  <input type="date" class="form-control" min="" value="<?php echo set_value('tglin'); ?>" name="tglin" id="tglin" required="required">
                   <?php  echo form_error('tgl'); ?>
              </div>
              <div class="form-group col-md-3">
                  <label for="tglot">Tanggal Akhir SPT</label>
                  <input type="date" class="form-control" min="" value="<?php echo set_value('tglot'); ?>" name="tglot" id="tglot" required="required">
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal Akhir dan Awal SPT
                  </div>
              </div>
            </div>
            <div class="form-group">
              <label>Dasar Surat</label>
            <textarea class="form-control summernote-simple"  name="dasrut" ><?php echo set_value('dasrut');?></textarea>
               <?php  echo form_error('dasrut'); ?>
              <div class="invalid-feedback">
                        Silahkan Input Perihal Surat
              </div>
            </div>

            <div class="form-group col-md-12">
                <label>Nama DL</label>
                <select name="nmdl[]" class="form-control select2" id="nmdl" required="required" multiple="">
                      <option value="">Plih Nama Pegawai </option>                     
                </select>
                 <?php  echo form_error('kodesur'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Nama
                  </div>
            </div>
            <div class="form-group">
              <label>Keperluan</label>
            <textarea class="form-control summernote-simple" required="required" name="perlu" ><?php echo set_value('perlu');?></textarea>
               <?php  echo form_error('perlu'); ?>
              <div class="invalid-feedback">
                        Silahkan Input Perihal Surat
              </div>
            </div>
            <div class="form-group col-md-12">
                <label>Nama TTE</label>
                <select name="nmttd" class="form-control" id="nmttd" required="required" >
                      <?php
                        foreach ($pejabat as $key) { ?>
                          <option value="<?php echo $key->id; ?>"><?php echo $key->nm; ?></option>
                      <?php  }
                      ?>
                </select>
                 <?php  echo form_error('nmttd'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Nama
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
  $("#tglot").change(function() {
    var ini = $("#tglin").val();
    var oto = $("#tglot").val();
    $.ajax({
      url: "<?php echo base_url()?>morsip/getnmdl",
      type: "post",
      data: {
        in: ini,
        ot: oto
      },
      success: function(msg) {
        $("#nmdl").html(msg)
      }
    })
  });
  $("#tglin").change(function() {
    var ini = $("#tglin").val();
    var oto = $("#tglot").val();
    $.ajax({
      url: "<?php echo base_url()?>morsip/getnmdl",
      type: "post",
      data: {
        in: ini,
        ot: oto
      },
      success: function(msg) {
        $("#nmdl").html(msg)
      }
    })
  });
  
})
</script>