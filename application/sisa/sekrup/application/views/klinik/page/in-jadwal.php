<!-- Tambahkan CSS -->
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">

<!-- Tambahkan JS -->
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>

<section class="section">
  <?php if($this->session->flashdata('notif') != NULL): ?>
    <?php 
      $tep = $this->session->flashdata('notif')['tipe'];
      $is = $this->session->flashdata('notif')['isi'];
      $cs = array('1' =>'alert-primary', '2' =>'alert-warning', '3' =>'alert-danger'); 
    ?>
    <div class="alert <?php echo $cs[$tep];?> alert-dismissible show fade">
      <div class="alert-body">
        <button class="close" data-dismiss="alert">
          <span>&times;</span>
        </button>
        <?php echo $is; ?>
      </div>
    </div>
  <?php endif; ?>
  
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Input Jadwal Dokter</h4>
          </div>
          <div class="card-body">
            <form action="<?php echo site_url('dokter/simpan_jadwal'); ?>" method="post"> 
               <!-- Input Hidden untuk ID Dokter -->
            <input type="hidden" name="id_dokter" value="<?php echo isset($id) ? $id : ''; ?>">
           
              <div class="form-group">
                <label for="nama">Nama Hari</label>
                  <select id="hari" name="hari" class="form-control select2" required>
                      <option value="" disabled selected>Pilih Hari</option>
                      <option value="Senin">Senin</option>
                      <option value="Selasa">Selasa</option>
                      <option value="Rabu">Rabu</option>
                      <option value="Kamis">Kamis</option>
                      <option value="Jumat">Jumat</option>
                      <option value="Sabtu">Sabtu</option>
                      <option value="Minggu">Minggu</option>
                  </select>
                </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="jam_mulai">Jam Mulai</label><br>
                  <input class="form-control" type="time" id="jam_mulai" name="jam_mulai" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="jam_selesai">Jam Selesai</label><br>
                  <input class="form-control" type="time" id="jam_selesai" name="jam_selesai" required>
                </div>
              </div>

              <!-- Tombol -->
              <div class="card-footer text-center">
                <button class="btn btn-primary" type="submit">Submit</button>
                <!-- <button class="btn btn-secondary" type="reset">Reset</button> -->
                <button class="btn btn-secondary" type="button" onclick="history.back()">Reset</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
