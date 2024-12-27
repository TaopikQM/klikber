<!-- CSS Resources -->
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">
<link href="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">

<!-- JS Resources -->
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>

<section class="section">
    <?php if ($this->session->flashdata('notif')): ?>
    <div class="alert <?php echo $cs[$tep];?> alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            <?php echo $this->session->flashdata('notif')['isi']; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php
      // echo "<pre>";
      // print_r($periksa);
      // echo "</pre>";
    ?>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Riwayat Pasien</h4>
                    </div>
                    <div class="card-body">
                        <?php echo form_open_multipart('dokter/update_rp', ['class' => "needs-validation", 'novalidate' => '']); ?>
                        <div class="form-group">
                            <label for="nama">Nama Pasien</label>
                            <input type="hidden" name="id_pasien_old" value="<?php echo $periksa['id_periksa']; ?>">
                            <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $periksa['nama_pasien']; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea readonly class="form-control" name="catatan"><?php echo $periksa['catatan']; ?></textarea>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                                <thead>
                                    <tr bgcolor="#cccccc">
                                        <th class="text-center">Nama Obat</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $nomor = 1; foreach ($periksa['obat'] as $key): ?>
                                    <tr bgcolor="#cccccc">
                                        <td class="text-center"><?php echo $key['nama_obat']; ?></td>
                                        <td class="text-center"><?php echo number_format($key['harga'], 0, ',', '.'); ?></td>
                                        <!-- <td class="text-center">
                                            <input type="checkbox" name="id_obat[]" value="<?php echo $key['id_obat']; ?>" class="hapus-obat" data-harga="<?php echo $key['harga']; ?>">
                                        </td> -->
                                        <td class="text-center">
                                          <button type="button" class="btn btn-danger btn-sm mt-1 remove-obat1" data-id-obat="<?php echo $key['id_obat']; ?>" data-harga="<?php echo $key['harga']; ?>">Hapus</button>
                                      </td>

                                    </tr>
                                    <?php $nomor++; endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga Periksa</label>
                            <input type="text" class="form-control" name="biaya_periksa" id="biaya_periksa" value="<?php echo number_format($periksa['biaya_periksa'], 0, ',', '.'); ?>" readonly>
                        </div>
                        <div class="form-group ">
                          <label for="obat[]">Obat</label><br/>
                          <button type="button" id="btn-tambah-obat" class="btn btn-primary mt-2">Tambah Obat</button>
                      
                          <div id="obat-list">
                              <!-- List obat yang sudah dipilih -->
                          </div>
                        </div>

                        <div class="card-footer text-center">
                            <input type="hidden" name="id_periksa" value="<?php echo $periksa['id_periksa']; ?>">
                            <input type="hidden" name="id_obat_dihapus" id="id_obat_dihapus" value="">
                            <button class="btn btn-primary mr-1" type="submit">Submit</button>
                            <button class="btn btn-secondary" type="button" onclick="history.back()">Reset</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
      // let totalHarga = 0;
      let deletedObatIds = []; // Array untuk menyimpan ID obat yang dihapus

       // Fungsi untuk menambahkan obat baru ke dalam daftar
    $('#btn-tambah-obat').on('click', function () {
        const obatList = $('#obat-list');

        // HTML untuk form input obat baru
        const obatHtml = `
            <div class="form-group ">
                <select class="form-control obat-dropdown" style="width:100%" name="obat[]">
                    <option value="">Pilih Obat</option>
                    <?php foreach ($obat as $p): ?>
                        <option data-harga="<?php echo $p->harga; ?>" value="<?php echo $p->id; ?>">
                            <?php echo $p->nama_obat . " - Rp" . number_format($p->harga, 0, ',', '.'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
               <button type="button" class="btn btn-danger btn-sm mt-1 remove-obat">Hapus</button>
            </div>
        `;

        // Tambahkan form obat baru ke daftar
        obatList.append(obatHtml);

        // Inisialisasi Select2 jika diperlukan
        $('.obat-dropdown').select2({ placeholder: "Pilih Obat", allowClear: true });
    });

    // Event untuk menangani perubahan pada dropdown obat
    $('#obat-list').on('change', '.obat-dropdown', function () {
        const hargaObat = parseInt($(this).find(':selected').data('harga')) || 0; // Ambil harga obat dari dropdown
        let totalBiaya = parseInt($('#biaya_periksa').val().replace(/\./g, '')) || 0; // Ambil total biaya awal

        // Tambahkan harga obat ke total biaya
        totalBiaya += hargaObat;

        // Update total biaya dengan format IDR
        $('#biaya_periksa').val(totalBiaya.toLocaleString('id-ID'));
    });

    // Event untuk menghapus obat dari daftar
    $('#obat-list').on('click', '.remove-obat', function () {
        const hargaObat = parseInt($(this).closest('.form-group').find('.obat-dropdown :selected').data('harga')) || 0; // Ambil harga obat
        let totalBiaya = parseInt($('#biaya_periksa').val().replace(/\./g, '')) || 0; // Ambil total biaya awal

        // Kurangi harga obat dari total biaya
        totalBiaya -= hargaObat;

        // Update total biaya dengan format IDR
        $('#biaya_periksa').val(totalBiaya.toLocaleString('id-ID'));
        $('#hidden-biaya').val(totalBiaya); // Update hidden input jika ada

        // Hapus elemen obat dari daftar
        $(this).closest('.form-group').remove();
    });
    $('.remove-obat1').on('click', function () {
        const obatId = $(this).data('id-obat');  // Ambil id_obat dari data-id-obat
        const hargaObat = parseInt($(this).data('harga')) || 0; // Ambil harga obat
        let totalBiaya = parseInt($('#biaya_periksa').val().replace(/\./g, '')) ; // Ambil total biaya awal

        // Simpan ID obat yang dihapus dalam array
        deletedObatIds.push(obatId);
        
        // Kurangi harga obat dari total biaya
        totalBiaya -= hargaObat;

        // Update total biaya dengan format IDR
        $('#biaya_periksa').val(totalBiaya.toLocaleString('id-ID'));
        $('#hidden-biaya').val(totalBiaya); // Update hidden input jika ada

        // Hapus elemen obat dari daftar
        $(this).closest('tr').remove();

        deletedObatIds.push(idObat);
    });
    // Menambahkan ID obat yang dihapus ke input tersembunyi saat form disubmit
    $('form').on('submit', function () {
        $('#id_obat_dihapus').val(deletedObatIds.join(',')); // Menyertakan ID obat yang dihapus
    });

        // $('.hapus-obat').on('change', function () {
        //     let totalBiaya = parseInt($('#biaya_periksa').val().replace(/\./g, '')) ||0;
        //     const hargaObat = parseInt($(this).data('harga'));

        //     if (this.checked) {
        //         totalBiaya -= hargaObat;
        //     } else {
        //         totalBiaya += hargaObat;
        //     }

        //     $('#biaya_periksa').val(totalBiaya.toLocaleString('id-ID'));
        // });
        

        
    });
</script>
