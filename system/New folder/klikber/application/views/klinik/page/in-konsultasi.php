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
            <h4>Input konsultasi</h4>
          </div>
          <div class="card-body">
            <form action="<?php echo site_url('pasien/Skonsultasi'); ?>" method="post"> 
             

              <!-- Poli -->
              <!-- <div class="form-group">
                <label for="id_dokter">Dokter</label>
                <select name="id_dokter" class="form-control select2" id="dokter" required>
                  <option value="">Pilih Dokter</option>
                  <?php foreach ($dokter as $p): ?>
                    <option value="<?php echo $p->id; ?>" <?php echo set_select('id_dokter', $p->id); ?>>
                      <?php echo $p->nama; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php echo form_error('id_dokter', '<div class="text-danger">', '</div>'); ?>
              </div> -->
              <div class="form-group">
                <label for="id_dokter">Dokter</label>
                <select name="id_dokter" class="form-control select2" id="id_dokter" required>
                  <option value="">Pilih Dokter</option>
                  <?php foreach ($dokter as $p): ?>
                    <option value="<?php echo $p->id; ?>" <?php echo set_select('id_dokter', $p->id); ?>>
                      <?php echo $p->nama; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php echo form_error('id_dokter', '<div class="text-danger">', '</div>'); ?>
              </div>
              

              <!-- <div class="form-group">
                
                <label for="dokter">Dokter</label>
                <select name="id_dokter" class="form-control select2" id="dokter" required>
                  <option value="">Pilih Dokter</option>
                  
                </select>
              </div>

              <div class="form-group">
                <label for="dokter">Jadwal</label>
                <select name="id_jadwal" class="form-control select2" id="jadwal" required>
                  <option value="">Pilih Jadwal</option>
                  
                </select>
              </div> -->

              <!-- <div class="form-group">
                <label for="dokter_jadwal">Dokter & Jadwal</label>
                <select name="id_jadwal" class="form-control select2" id="dokter_jadwal" required>
                  <option value="">Pilih Dokter & Jadwal</option>
                </select>
              </div> -->

              <div class="form-group">
                <label for="subject">Subject</label>
                <textarea class="form-control" name="subject" id="subject" required><?php echo set_value('subject'); ?></textarea>
                <?php echo form_error('subject', '<div class="text-danger">', '</div>'); ?>
              </div>
              <div class="form-group">
                <label for="pertanyaan">Pertanyaan</label>
                <textarea class="form-control" name="pertanyaan" id="pertanyaan" required><?php echo set_value('pertanyaan'); ?></textarea>
                <?php echo form_error('pertanyaan', '<div class="text-danger">', '</div>'); ?>
              </div>

              <!-- Tombol -->
              <div class="card-footer text-center">
              <!-- <input type="hidden" name="id_pasien" value="<?php echo $id_pasien?>"> -->
              
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
