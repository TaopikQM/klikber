
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
            <h4>Edit Surat Perintah Tugas (SPT) </h4>
            
          </div>
          <div class="card-body">

            <?php 
              foreach ($data as $key) {
                $tglsur=date("Y-m-d",$key->tgldl);
                $tglin=date("Y-m-d",$key->tglin);
                $tglot=date("Y-m-d",$key->tglot);
                $dasrut= base64_decode($key->dasrut);
                $perlu= base64_decode($key->keperluan);
                $nmttd= $key->nmttd;
                $orangdl= $key->nmdl;
                $ids=$key->id;
                $isup=$key->isupdate;
              }
              //print_r($data);



            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('morsip/save_e_spt',$arrayName);
              $hk=$this->session->userdata(base64_encode('jajahan'));
              for ($i=0; $i <count($hk) ; $i++) {
                if (base64_decode($hk[$i][base64_encode('apli')])=="1_morsip") {
                  $gethk=$this->encryption->decrypt($hk[$i][base64_encode('hk')]);
                }
              }
              $nwhk=explode("_",$gethk);
              
            ?>
            <?php if($isup != 1 || $nwhk[1] == 0 ){ ?>
             <div class="form-row">
              <div class="form-group col-md-3">
                  <?php
                      date_default_timezone_set("Asia/Jakarta");
                      if ($nwhk[1] != 0 ) {
                        $tgloi= $tglsur;
                      }else{
                        $tgloi= date("Y")."-1-1";
                      }
                  ?>
                  <label for="tgl">Tanggal Surat</label>
                  <input type="date" class="form-control" min="<?php echo $tgloi;?>" value="<?php echo $tglsur; ?>" name="tgl" id="tgl" required="required">
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal SPT
                  </div>
              </div>
            </div>
          <?php }?>
            <div class="form-row">
              <div class="form-group col-md-3">
                  <label for="tglin">Tangal Awal SPT</label>
                  <input type="date" class="form-control" min="<?php echo $tglsur; ?>" value="<?php echo $tglin; ?>" name="tglin" id="tglin" required="required" data-orang="<?php echo $orangdl;?>" >
                   <?php  echo form_error('tgl'); ?>
              </div>
              <div class="form-group col-md-3">
                  <label for="tglot">Tanggal Akhir SPT</label>
                  <input type="date" class="form-control" min="<?php echo $tglin; ?>" value="<?php echo $tglot; ?>" name="tglot" id="tglot" required="required" data-orang="<?php echo $orangdl;?>">
                   <?php  echo form_error('tgl'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal Akhir dan Awal SPT
                  </div>
              </div>
            </div>
            <div class="form-group">
              <label>Dasar Surat</label>
            <textarea class="form-control summernote-simple"  name="dasrut" ><?php echo $dasrut;?></textarea>
               <?php  echo form_error('dasrut'); ?>
              <div class="invalid-feedback">
                        Silahkan Input Perihal Surat
              </div>
            </div>

            <div class="form-group col-md-12">
                <label>Nama DL</label><p id="orangDL"></p>
                <select name="nmdl[]" class="form-control select2" id="nmdl" required="required" multiple="">
                      <option value="">Plih Nama Pegawai</option>
                      <?php
                        foreach ($namaspt as $kr) { ?>
                          <option selected="selected" value="<?php echo $kr->id; ?>"><?php echo base64_decode($kr->nama); ?></option>
                      <?php }
                        foreach ($dataspt as $kh) { ?>
                          <option value="<?php echo $kh->id; ?>"><?php echo base64_decode($kh->nama); ?></option>
                      <?php } ?>                     
                </select>
                 <?php  echo form_error('kodesur'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Nama
                  </div>
            </div>
            <div class="form-group">
              <label>Keperluan</label>
            <textarea class="form-control summernote-simple" required="required" name="perlu" ><?php echo $perlu;?></textarea>
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
                          <option 
                          <?php if ($nmttd==$key->id) {
                          echo "selected='selected'";
                          }?>
                        value="<?php echo $key->id; ?>"><?php echo $key->nm; ?></option>
                      <?php  }
                      ?>
                </select>
                 <?php  echo form_error('nmttd'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Nama
                  </div>
            </div> 
            
            
            <div class="card-footer text-center">
              <input type="hidden" name="idspt" value="<?php echo $ids; ?>">
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
    var or=$(this).data("orang");
    $("#tglot").attr("min", p);
    //$("#nmdl option[selected]").removeAttr("selected");
    $('#nmdl').val(null).trigger('change'); 

    $.ajax({
      url: "<?php echo base_url()?>morsip/getorangdl",
      type: "post",
      data: {
        or: or
      },
      success: function(msg) {
        
        $("#orangDL").html(msg);

      }
    })



  });
  $("#tglot").change(function() {
    var y = $("#tglot").val();
    var or=$(this).data("orang");

    $("#tglin").attr("max", y);
    //$("#nmdl option[selected]").removeAttr("selected");
    $('#nmdl').val(null).trigger('change');

    $.ajax({
      url: "<?php echo base_url()?>morsip/getorangdl",
      type: "post",
      data: {
        or: or
      },
      success: function(msg) {
        
        $("#orangDL").html(msg);

      }
    }) 
    
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
        
        $("#nmdl").html(msg);

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
        //$("#nmdl").val([]);
        $("#nmdl").html(msg)
      }
    })
  })
})
</script>