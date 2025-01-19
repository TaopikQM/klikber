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
            <h4>Edit Jadwal</h4>
          </div>
          <div class="card-body">
         
            <?php 
            // Menampilkan data dokter yang sudah ada
            foreach ($jadwal as $jad) {
              $id = $jad->id;
              $id_dokter = $jad->id_dokter;
              $hari = $jad->hari;
              $jam_mulai = $jad->jam_mulai;
              $jam_selesai = $jad->jam_selesai;
            }

            // Form edit dokter
            echo form_open_multipart('dokter/update_jadwal', array('class' => 'needs-validation', 'novalidate' => '')); 
            ?>
            <div class="form-group">
                <label for="nama">Nama Hari</label>
                <select id="hari" name="hari" class="form-control select2" disabled>
                    <option value="" disabled <?= empty($hari) ? 'selected' : '' ?>>Pilih Hari</option>
                    <option value="Senin" <?= $hari == 'Senin' ? 'selected' : '' ?>>Senin</option>
                    <option value="Selasa" <?= $hari == 'Selasa' ? 'selected' : '' ?>>Selasa</option>
                    <option value="Rabu" <?= $hari == 'Rabu' ? 'selected' : '' ?>>Rabu</option>
                    <option value="Kamis" <?= $hari == 'Kamis' ? 'selected' : '' ?>>Kamis</option>
                    <option value="Jumat" <?= $hari == 'Jumat' ? 'selected' : '' ?>>Jumat</option>
                    <option value="Sabtu" <?= $hari == 'Sabtu' ? 'selected' : '' ?>>Sabtu</option>
                    <option value="Minggu" <?= $hari == 'Minggu' ? 'selected' : '' ?>>Minggu</option>
                </select>
                <!-- Input hidden untuk mengirim nilai -->
    <input type="hidden" name="hari" value="<?= $hari ?>">
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="jam_mulai">Jam Mulai</label><br>
                    <input class="form-control" 
                          type="time" 
                          id="jam_mulai" 
                          name="jam_mulai" 
                          value="<?= isset($jam_mulai) ? $jam_mulai : '' ?>" 
                          required>
                </div>
                <div class="form-group col-md-6">
                    <label for="jam_selesai">Jam Selesai</label><br>
                    <input class="form-control" 
                          type="time" 
                          id="jam_selesai" 
                          name="jam_selesai" 
                          value="<?= isset($jam_selesai) ? $jam_selesai : '' ?>" 
                          required>
                </div>
            </div>


            <div class="card-footer text-center">
              <input type="hidden" name="id" value="<?php echo $id?>">
              <input type="hidden" name="id_dokter" value="<?php echo $id_dokter?>">
              
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
