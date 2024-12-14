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
            <h4>Edit Dokter</h4>
          </div>
          <div class="card-body">
            <?php 
            // Menampilkan data dokter yang sudah ada
            foreach ($admin as $dok) {
              $id = $dok->id;
              $nama = $dok->nama;
              $alamat = $dok->alamat;
              $no_hp = $dok->no_hp;
            }

            // Form edit dokter
            echo form_open_multipart('admin/update', array('class' => 'needs-validation', 'novalidate' => '')); 
            ?>
            <div class="form-row">
              <div class="form-group col-md-6">
                  <label for="nama_admin">Nama Admin</label>
                  <input type="hidden" name="id_admin_old" value="<?php echo $id;?>">
                  <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $nama;?>" required>
                  <?php echo form_error('nama'); ?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nama Admin
                  </div>
              </div>
              <div class="form-group">
                <label for="no_hp">Nomor HP</label>
                <input type="text" class="form-control" name="no_hp" id="no_hp" value="<?php echo $no_hp; ?>" maxlength="17" required>
                <?php echo form_error('no_hp');?>
                  <div class="invalid-feedback">
                        Silahkan Masukan Nomor HP 
                  </div>
              </div>
              

            </div>

            <div class="form-group">
              <label for="alamat">Alamat Admin</label>
              <textarea class="form-control" name="alamat"><?php echo $alamat; ?></textarea>
              <?php echo form_error('alamat'); ?>
              <div class="invalid-feedback">
                        Silahkan Input Almaat Admin
              </div>
            </div>

            <div class="card-footer text-center">
              <input type="hidden" name="id" value="<?php echo $id;?>">
              <button class="btn btn-primary mr-1" type="submit">Simpan</button>
              <a href="<?php echo base_url('admin')?>" class="btn btn-default">Kembali</a>
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
