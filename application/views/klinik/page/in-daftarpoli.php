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
            <h4>Input Daftar Poli</h4>
          </div>
          <div class="card-body">
            <form action="<?php echo site_url('pasien/mendaftar'); ?>" method="post"> 
             

              <!-- Poli -->
              <div class="form-group">
                <label for="id_poli">Poli</label>
                <select name="id_poli" class="form-control select2" id="poli" required>
                  <option value="">Pilih Poli</option>
                  <?php foreach ($poli as $p): ?>
                    <option value="<?php echo $p->id; ?>" <?php echo set_select('id_poli', $p->id); ?>>
                      <?php echo $p->nama_poli; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php echo form_error('id_poli', '<div class="text-danger">', '</div>'); ?>
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

              <div class="form-group">
                <label for="dokter_jadwal">Dokter & Jadwal</label>
                <select name="id_jadwal" class="form-control select2" id="dokter_jadwal" required>
                  <option value="">Pilih Dokter & Jadwal</option>
                </select>
              </div>

            
              <div class="form-group">
                <label for="keluhan">Keluhan</label>
                <textarea class="form-control" name="keluhan" id="keluhan" required><?php echo set_value('keluhan'); ?></textarea>
                <?php echo form_error('keluhan', '<div class="text-danger">', '</div>'); ?>
              </div>

              <!-- Tombol -->
              <div class="card-footer text-center">
              <input type="hidden" name="id_pasien" value="<?php echo $id?>">
              
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

<script>
        // Fetch dokter berdasarkan poli
        $('#poli').change(function() {
            const id_poli = $(this).val();
            $.post('<?= base_url("pasien/getDokter") ?>', { id_poli: id_poli }, function(data) {
                $('#dokter').html('<option value="">Pilih Dokter</option>');
                const dokter = JSON.parse(data);
                dokter.forEach(d => {
                    $('#dokter').append(`<option value="${d.id}">${d.nama}</option>`);
                });
            });
        });

        // Fetch jadwal berdasarkan dokter
        $('#dokter').change(function() {
            const id_dokter = $(this).val();
            $.post('<?= base_url("pasien/getJadwal") ?>', { id_dokter: id_dokter }, function(data) {
                $('#jadwal').html('<option value="">Pilih Jadwal</option>');
                const jadwal = JSON.parse(data);
                jadwal.forEach(j => {
                    $('#jadwal').append(`<option value="${j.id}">${j.hari} (${j.jam_mulai} - ${j.jam_selesai})</option>`);
                });
            });
        });



        $('#poli').change(function () {
          const id_poli = $(this).val();

          // Reset dropdown Dokter & Jadwal jika Poli tidak dipilih
          if (!id_poli) {
            $('#dokter_jadwal').html('<option value="">Pilih Dokter & Jadwal</option>');
            return;
          }

          // Fetch Dokter & Jadwal
          $.post('<?= base_url("pasien/getDokterJadwal") ?>', { id_poli: id_poli }, function (data) {
            $('#dokter_jadwal').html('<option value="">Pilih Dokter & Jadwal</option>'); // Reset dropdown
            const dokterJadwal = JSON.parse(data);

            // Tambahkan opsi Dokter & Jadwal
            dokterJadwal.forEach(item => {
              $('#dokter_jadwal').append(
                // `<option value="${item.id_dokter}-${item.id_jadwal}">${item.nama_dokter} - ${item.hari} (${item.jam_mulai} - ${item.jam_selesai})</option>`
                `<option value="${item.id_jadwal}">${item.nama_dokter} - ${item.hari} (${item.jam_mulai} - ${item.jam_selesai})</option>`
             );
            });
          });
        });

    </script>
