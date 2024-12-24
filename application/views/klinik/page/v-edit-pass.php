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
            <h4>Edit Pass</h4>
          </div>
          <div class="card-body">
            <?php 
            // Menampilkan data dokter yang sudah ada
            foreach ($users as $key) {
              $id = $key->id;
              $username = $key->username;
              $password = $key->password;
              $id_role = $key->id_role;
            }

            // Form edit dokter
            echo form_open('landing/change_password', array('class' => 'needs-validation', 'novalidate' => '')); 
            ?>
             
            <div class="form-group">
                <label>Password Lama</label>
                <input type="password" name="password_lama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password_baru" class="form-control" required>
            </div>

            <div class="card-footer text-center">
              <input type="hidden" name="id" value="<?php echo $id;?>">
              <input type="hidden" name="username" value="<?php echo $username;?>">
              <button class="btn btn-primary mr-1" type="submit">Simpan</button>
              <button class="btn btn-secondary" type="button" onclick="history.back()">Reset</button>

            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/light-gallery.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/js/scripts.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2(); // Aktivasi Select2
    });
</script>
