<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<link href="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
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
            <h4>Edit Pasien</h4>
            
          </div>
          <div class="card-body">
            <?php 
            foreach ($pasien as $ky ) {
              $id=$ky->id;
              $nama=$ky->nama;
              $alamat=$ky->alamat;
              $no_ktp=$ky->no_ktp;
              $no_hp=$ky->no_hp;
              $no_rm=$ky->no_rm;
            }
            /*echo "<pre>";
            print_r($data);
            echo "</pre>";*/


            date_default_timezone_set("Asia/Jakarta");
            $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('pasien/update',$arrayName);?>
             <div class="form-row">
                  <label for="tgl">Nama Pasien</label>
                  <input type="hidden" name="napasienold" value="<?php echo $nama;?>">
                  <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $nama;?>" required="required">
                   <?php  echo form_error('nama'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nama Pasien
                  </div>
            </div>
            <div class="form-row">
              
                <label for="no_hp">Nomor KTP</label>
                <input type="text" class="form-control" name="no_ktp" id="no_ktp" value="<?php echo $no_ktp; ?>" maxlength="17" required>
                <?php echo form_error('no_ktp');?>
                <div class="invalid-feedback">
                        Silahkan Masukan Nomor KTP
                </div>
            </div>
            <div class="form-row">
                <label for="no_hp">Nomor HP</label>
                <input type="text" class="form-control" name="no_hp" id="no_hp" value="<?php echo $no_hp; ?>" maxlength="17" required>
                <?php echo form_error('no_hp');?>
                <div class="invalid-feedback">
                        Silahkan Masukan Nomor HP
                </div>
            </div>
            <div class="form-row">
                
                
                <label for="no_rm">Nomor RM</label>
                <input type="text" class="form-control" id="no_rm" name="no_rm" value="<?php echo $no_rm; ?>" readonly>
                <?php echo form_error('no_rm');?>
                <div class="invalid-feedback">
                        Silahkan Masukan Nomor RM
                </div>
            
            </div> 
            <div class="form-row">
              <label>Alamat </label>
            <textarea class="form-control " id="alamat" name="alamat" ><?php echo $alamat;?></textarea>
               <?php  echo form_error('alamat'); ?>
              <div class="invalid-feedback">
                        Silahkan Input Alamat lainnya
              </div>
            </div>
            <div class="card-footer text-center">
              <input type="hidden" name="id" value="<?php echo $id;?>">
              <button class="btn btn-primary mr-1" type="submit">Submit</button>
              <button class="btn btn-secondary" type="button" onclick="history.back()">Reset</button>

            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
 
<!-- JS Libraies -->
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
<!-- Page Specific JS File -->
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/light-gallery.js"></script>
