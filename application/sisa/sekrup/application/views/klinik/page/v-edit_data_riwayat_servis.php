<section class="section">
    <?php if ($this->session->flashdata('notif') != NULL): ?>
        <?php
        $tep = $this->session->flashdata('notif')['tipe'];
        $is = $this->session->flashdata('notif')['isi'];
        $cs = array('1' => 'alert-primary', '2' => 'alert-warning', '3' => 'alert-danger');
        ?>
        <div class="alert <?php echo $cs[$tep]; ?> alert-dismissible show fade">
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
                        <h4>Edit Riwayat Servis</h4> 
                    </div>
                    <div class="card-body">
                        <?php 
                        date_default_timezone_set("Asia/Jakarta");
                        $arrayName = ['class' => "needs-validation", 'novalidate' => ''];
                        echo form_open_multipart('riwayatservis/save_e_riwayat', $arrayName);

                        foreach ($riwayat_servis as $key):
                            $kilometer = $key->kilometer;
                            $nm_pemegang = $key->nm_pemegang;
                            $blnket = $key->blnket;
                            $ket = $key->ket;
                            $idmobil = $key->idmobil; // Pastikan ID Mobil juga diambil
                        endforeach;
                        ?>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="nm_pemegang">Nama Pemegang<?php echo form_error('nm_pemegang'); ?></label>
                                <select name="nm_pemegang" class="form-control select" id="nm_pemegang" required>
                                    <option value="NULL" disabled>Pilih Nama Pemegang</option>
                                    <?php foreach ($driver as $drv): ?>
                                        <option value="<?php echo $drv->nmdriver; ?>" <?php if ($drv->nmdriver == $nm_pemegang) echo "selected='selected'"; ?>>
                                            <?php echo $drv->nmdriver; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="blnket">Tanggal Servis</label>
                                <input type="date" class="form-control" value="<?php echo $blnket; ?>" name="blnket" id="blnket">
                                <?php echo form_error('blnket'); ?>
                                <div class="invalid-feedback">
                                    Silahkan Masukan Tanggal Servis
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="ket">Keterangan Servis<?php echo form_error('ket'); ?></label>
                                <input type="hidden" name="ket_old" value="<?php echo $ket; ?>">
                                <input type="text" class="form-control" name="ket" id="ket" value="<?php echo $ket; ?>" required>
                                <div class="invalid-feedback">
                                    Silahkan Masukan Keterangan Servis
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="kilometer">Kilometer<?php echo form_error('kilometer'); ?></label>
                                <input type="hidden" name="kilometer_old" value="<?php echo $kilometer; ?>">
                                <input type="number" class="form-control" name="kilometer" id="kilometer" value="<?php echo $kilometer; ?>" required>
                                <div class="invalid-feedback">
                                    Silahkan Masukan Keterangan Kilometer
                                </div>
                            </div>
                        </div>

                        <!-- Input hidden untuk ID mobil -->
                        <input type="hidden" name="idmobil" value="<?php echo $idmobil; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>"> <!-- id riwayat servis -->


                        <button type="submit" class="btn btn-primary">Simpan</button>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script untuk validasi form -->
<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

<!-- JS Libraries -->
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/js/select2.min.js"></script>
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/light-gallery.js"></script>
