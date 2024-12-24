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
            <h4>Input Dokter</h4>
          </div>
          <div class="card-body">
            <form action="<?php echo site_url('dokter/store'); ?>" method="post"> 
              <!-- Nama Dokter -->
              <div class="form-group">
                <label for="nama">Nama Dokter</label>
                <input type="text" class="form-control" name="nama" id="nama" value="<?php echo set_value('nama'); ?>" required>
                <?php echo form_error('nama', '<div class="text-danger">', '</div>'); ?>
              </div>

              <!-- Nomor HP -->
              <div class="form-group">
                <label for="no_hp">Nomor HP</label>
                <input type="text" class="form-control" name="no_hp" id="no_hp" value="<?php echo set_value('no_hp'); ?>" maxlength="17" required>
                <?php echo form_error('no_hp', '<div class="text-danger">', '</div>'); ?>
              </div>

              <!-- Poli -->
              <div class="form-group">
                <label for="id_poli">Poli</label>
                <select name="id_poli" class="form-control select2" id="id_poli" required>
                  <option value="">Pilih Poli</option>
                  <?php foreach ($polis as $p): ?>
                    <option value="<?php echo $p->id; ?>" <?php echo set_select('id_poli', $p->id); ?>>
                      <?php echo $p->nama_poli; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php echo form_error('id_poli', '<div class="text-danger">', '</div>'); ?>
              </div>

              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $username; ?>" readonly>
                <?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>
              </div>

              <div class="form-group">
                <label for="password"> Password</label>
                <input type="text" class="form-control" id="password" name="password" value="<?= $password; ?>" readonly>
                <?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
              </div>

              <!-- Alamat -->
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" name="alamat" id="alamat" required><?php echo set_value('alamat'); ?></textarea>
                <?php echo form_error('alamat', '<div class="text-danger">', '</div>'); ?>
              </div>

              <!-- Tombol -->
              <div class="card-footer text-center">
                <button class="btn btn-primary" type="submit">Submit</button>
                <button class="btn btn-secondary" type="button" onclick="history.back()">Reset</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
