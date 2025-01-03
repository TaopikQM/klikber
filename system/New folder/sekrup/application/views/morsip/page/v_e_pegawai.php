
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<!-- Template CSS -->

<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">


<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<!-- <script src="https://demo.dewankomputer.com/php/dewan-mask/js/jquery.min.js"></script> -->


<!-- Custom style CSS -->

<section class="section">
<?php

foreach ($pegawai as $key) {
  $id=$key->id;
  $np=$this->encryption->decrypt($key->nip);
  if ($np=="-") {
    $nip="00000000 000000 0 000";
  }else{
    $nip=$np;
  }
  $nama=base64_decode($key->nama);
  $jab=$key->jabatan;
  $gol=$key->gol;
  $sortir=$key->sortir;
  $status=$key->status;
}
?>

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
            <h4>Input Pegawai</h4>
            
          </div>
          <div class="card-body">

            <?php $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('morsip/save_e_pegawai',$arrayName);?>
            <div class="form-group">
              <div class="form-group col-md-12">
                  <?php
                      date_default_timezone_set("Asia/Jakarta");
                      $tgloi= date("Y-m-d");
                  ?>
                  <label for="user">NIP</label>
                  <input type="text" class="form-control nip" name="nip" id="nip" required="required"  value="<?php if(set_value('nip')){ echo set_value('nip');} else{ echo $nip; } ?>" data-mask="00000000 000000 0 000" placeholder="Jika Tidak Memiliki NIP silahkan isi dengan 00000000 000000 0 000">
                   <?php  echo form_error('nip'); ?>
                  <div class="invalid-feedback">
                      <?php  echo $nip; ?>
                        Silahkan Input NIP (Nomor Induk Pegawai)
                  </div>

              </div>
              
            </div>
            <div class="form-group">
              
              <div class="form-group col-md-10">
                  <center><b><label for="user">NAMA</label></b></center>
                  <input type="text" class="form-control" name="nama" id="nama" required="required" value="<?php if(set_value('nama')){ echo set_value('nama');} else{ echo $nama; } ?>">
                   <?php  echo form_error('nama'); ?>
                  <div class="invalid-feedback">
                        Silahkan Input Nama Dengan Gelar
                  </div>
                  <div class="">
                    Contoh : Prof. Dr. DONO KASINO INDRO, S.Pd, M.Pd
                  </div>
              </div>
             
            </div>
             <div class="form-row">
              
              <div class="form-group col-md-3">
                <label>Pilih Jabatan</label>
                <select name="jabatan" class="form-control select2 " required="required">
                      <option value="">Pilih Jabatan</option>
                  <?php 
                    foreach ($jabatan as $ky) {?>
                        <option <?php if ($ky->id ==  set_value('jabatan') ) { echo "selected=selected"; }elseif ($ky->id == $jab) {echo "selected=selected";}?>
                        value="<?php echo $ky->id;?>"><?php echo $ky->nama_jabatan;?></option>
                      
                    <?php
                    }
                  ?>
                </select>
                   <?php  echo form_error('jabatan'); ?>

                <div class="invalid-feedback">
                        Silahkan Pilih Jabatan
                  </div>
              </div>

              <div class="form-group col-md-3">
                <label>Pilih Pangkat / Golongan</label>
                <select name="golongan" class="form-control select2" required="required">
                      <option value="">Pilih Pangkat / Golongan</option>
                  <?php 
                    foreach ($golongan as $ky) {?>
                        <option <?php if ($ky->id ==  set_value('golongan') ) { echo "selected=selected"; }elseif ($ky->id == $gol) {echo "selected=selected";}?>
                        value="<?php echo $ky->id;?>"><?php echo $ky->golru;?></option>
                    <?php
                    }
                  ?>
                </select>
                   <?php  echo form_error('golongan'); ?>
                <div class="invalid-feedback">
                        Silahkan Pilih Pangkat / Golongan
                  </div>
              </div>

              <div class="form-group col-md-3">
                <label>Pilih Sortir</label>
                <select name="hk" class="form-control select2" required="required">
                      <option value="">Sortir Urutan</option>
                      <option <?php if (1 ==  set_value('hk') ) { echo "selected=selected"; } elseif (1 == $sortir) {echo "selected=selected";}?> value="1" >Kepala Bidang</option>
                      <option <?php if (2 ==  set_value('hk') ) { echo "selected=selected"; } elseif (2 == $sortir) {echo "selected=selected";}?> value="2" >Kepala Seksi</option>
                      <option <?php if (3 ==  set_value('hk') ) { echo "selected=selected"; } elseif (3 == $sortir) {echo "selected=selected";}?> value="3" >PNS</option>
                      <option <?php if (4 ==  set_value('hk') ) { echo "selected=selected"; } elseif (4 == $sortir) {echo "selected=selected";}?> value="4" >PHL</option>
                      <option <?php if (5 ==  set_value('hk') ) { echo "selected=selected"; } elseif (5 == $sortir) {echo "selected=selected";}?> value="5" >Pengemudi / Lainnya</option>
                </select>
                   <?php  echo form_error('sortir'); ?>

                <div class="invalid-feedback">
                        Silahkan Pilih Sortir
                  </div>
              </div>

            </div>
            
             
            
            
            <div class="card-footer text-center">
              <input type="hidden" name="id" value="<?php echo $id; ?>">
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


<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery.mask.min.js"></script>
 