<!-- Tambahkan CSS -->
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">

<!-- Tambahkan JS -->
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>

<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


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
            <h4>Input periksa</h4>
          </div>
          <div class="card-body">
            <form action="<?php echo site_url('dokter/simpanPeriksa'); ?>" method="post"> 
            <?php
                    // echo "<pre>";
                    // print_r($daftar_poli);
                    // echo "</pre>";
                  ?>
                  <?php
                    // echo "<pre>";
                    // print_r($obat);
                    // echo "</pre>";
                  ?>

              <div class="form-group">
                  <label for="no_rm">NO RM</label>
                  <input type="text" class="form-control" id="no_rm" name="no_rm" 
                        value="<?= !empty($daftar_poli) && isset($daftar_poli['no_rm']) ? $daftar_poli['no_rm'] : ''; ?>" 
                        readonly>
                  <?php echo form_error('no_rm', '<div class="text-danger">', '</div>'); ?>
              </div>
              <div class="form-group">
                  <label for="pasien_nama">Nama Pasien</label>
                  <input type="text" class="form-control" id="pasien_nama" name="pasien_nama" 
                        value="<?= !empty($daftar_poli) && isset($daftar_poli['pasien_nama']) ? $daftar_poli['pasien_nama'] : ''; ?>" 
                        readonly>
                  <?php echo form_error('pasien_nama', '<div class="text-danger">', '</div>'); ?>
              </div>

              <div class="form-group">
                  <?php 
                      date_default_timezone_set("Asia/Jakarta");
                      $tgloi= date("Y-m-d");
                      $tmput= date("H:i");
                      $currentHour = substr($tmput, 0, 2); // Extract current hour
                  ?>
                  <label for="tgl_periksa">Tanggal Periksa</label>
                  <input type="date" class="form-control" min="<?php echo $tgloi;?>" value="<?php echo set_value('tgl_periksa'); ?>" name="tgl_periksa" id="tgl_periksa"  required="required">
                  
                  <?php  echo form_error('tgl_periksa'); ?>
                  <div class="invalid-feedback">
                        Silahkan Pilih Tanggal Periksa
                  </div>
              </div>
              

              <div class="form-group">
                <label for="catatan">catatan</label>
                
                <textarea class="form-control" name="catatan" id="catatan" required><?php echo set_value('catatan'); ?></textarea>
                <?php echo form_error('catatan', '<div class="text-danger">', '</div>'); ?>
              </div>
            
              <div class="form-row">

                <div class="form-group col-md-3">
                  <label for="obat[]">Obat</label><br/>
                  <button type="button" id="btn-tambah-obat" class="btn btn-primary mt-2">Tambah Obat</button>
              
                  <div id="obat-list">
                      <!-- List obat yang sudah dipilih -->
                  </div>
                </div>

                <div class="form-group  col-md-2">
                  <label for="tobat">Total Obat</label>
                  <div id="total-obat" class="form-control" readonly></div>
                </div>

                <div class="form-group  col-md-2">
                  <label for="thobat">Total Harga</label>
                  <div id="total-harga" class="form-control" readonly></div>
                </div>

                <div class="form-group  col-md-2">
                  <label for="bjasa">Biaya Jasa Dokter</label>
                    <input type="number" class="form-control" name="bjasa" id="biaya-jasa" value="150000" maxlength="10" >
                    <?php  echo form_error('bjasa'); ?>
                </div>

                <div class="form-group col-md-3">
                  <label for="thobat">Total Biaya Periksa</label>
                  <div name="biaya" id="total-biaya" class="form-control" readonly></div>
                  <input type="hidden" name="biaya" id="hidden-biaya">
                </div>
              </div>
              <div class="card-footer text-center">
                <input type="hidden" name="id_daftar_poli" value="<?= !empty($daftar_poli) && isset($daftar_poli['id']) ? $daftar_poli['id'] : ''; ?>">
                
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
    <script type="text/javascript">
        $(document).ready(function() {
          let totalObat = 0;
          let totalHarga = 0;
          let biayajasa = parseFloat($('#biaya-jasa').val()) || 0;

            $('#btn-tambah-obat').click(function() {
                var obatList = $('#obat-list');
                var obatHtml = '<div class="form-group mt-2"><select class="js-example-basic-single form-control" style="width:100%" name="obat[]"><option value="">Pilih Obat</option>';
                
                <?php foreach ($obat as $p): ?>
                    obatHtml += '<option data-price="<?php echo $p->harga; ?>" value="<?php echo $p->id; ?>"><?php echo $p->nama_obat . " - Rp" . number_format($p->harga, 0, ',', '.');  ?></option>';
                <?php endforeach; ?>
                
                obatHtml += '</select><button type="button" class="btn btn-danger btn-sm mt-1 remove-obat">Hapus</button></div>';
                
                obatList.append(obatHtml);
                
                // Initialize Select2 on the new select element
                $('select').select2({
                    placeholder: "Pilih Obat",
                    allowClear: true
                });
            });

            

            // Event listener untuk menghapus obat
            $('#obat-list').on('click', '.remove-obat', function() {
              $(this).closest('.form-group').remove();
              updateTotalObat();
              updateTotalHarga();
              updateTotalBiayaPeriksa();
            });

            // Fungsi untuk menghitung total obat
            function updateTotalObat() {
              totalObat = $('#obat-list .form-group').length;
              $('#total-obat').text(totalObat);
            }

            // Fungsi untuk menghitung total harga obat
            function updateTotalHarga() {
              totalHarga = 0;
              
              $('#obat-list select').each(function() {
                var price = $(this).find(':selected').data('price');
                if (price) {
                  totalHarga += parseFloat(price);
                }
              });
              var formattedHarga = 'Rp' + totalHarga.toLocaleString('id-ID');

              $('#total-harga').text(formattedHarga);
            }

            // Fungsi untuk menghitung total biaya periksa
            function updateTotalBiayaPeriksa() {
              var totalBiayaPeriksa = totalHarga + biayajasa;
              // Format mata uang dengan titik per 3 dari belakang
              var formattedTotal = 'Rp' + totalBiayaPeriksa.toLocaleString('id-ID');

              $('#total-biaya').text(formattedTotal);
              $('#hidden-biaya').val(totalBiayaPeriksa);
            }
            

            // Panggil fungsi untuk mengatur total awal
            updateTotalObat();
            updateTotalHarga();
            updateTotalBiayaPeriksa();

            // Memanggil fungsi updateTotalObat setelah setiap perubahan
            $('#obat-list').on('change', 'select', function() {
              updateTotalObat();
              updateTotalHarga();
              updateTotalBiayaPeriksa();
            });

            // Update biaya jasa jika input berubah
            $('#biaya-jasa').on('input', function() {
                biayajasa = parseFloat($(this).val()) || 0; // Pastikan konversi ke angka
                updateTotalBiayaPeriksa();
            });

            
        });
    </script>