<!-- CSS -->
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">
<link href="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
<!-- Custom style CSS -->
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>

<section class="section">
    <?php
    if ($this->session->flashdata('notif') != NULL) {
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
    <?php } ?>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Users</h4>
                    </div>
                    <div class="card-body">
                        <?php foreach ($users as $ky) {
                            $id = $ky->id;
                            $username = $ky->username;
                            $password = $ky->password;
                        } ?>
                        <?php
                        date_default_timezone_set("Asia/Jakarta");
                        $arrayName = array(
                            'class' => "needs-validation",
                            'novalidate' => ''
                        );
                       
                        echo form_open_multipart(strtolower($this->session->userdata('role')) . '/updateUs', $arrayName); ?>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tgl">Username</label>
                                <input type="hidden" name="nauseold" value="<?php echo $username; ?>">
                                <input type="text" class="form-control" name="username" id="username" value="<?php echo $username; ?>" readonly>
                                <?php echo form_error('username'); ?>
                                <div class="invalid-feedback">
                                    Silahkan Masukan Nama Username
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form untuk Ubah Password -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="old_password">Password Lama</label>
                                <input type="password" class="form-control" name="old_password" id="old_password" required>
                                <?php echo form_error('old_password'); ?>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan Password Lama
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="new_password">Password Baru</label>
                                <input type="password" class="form-control" name="new_password" id="new_password" required minlength="8">
                                <?php echo form_error('new_password'); ?>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan Password Baru (min. 8 karakter)
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="confirm_password">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                <?php echo form_error('confirm_password'); ?>
                                <div class="invalid-feedback">
                                    Silahkan Konfirmasi Password Baru
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-center">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <button class="btn btn-primary mr-1" type="submit">Submit</button>
                            <button class="btn btn-secondary" type="button" onclick="history.back()">Reset</button>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JS Libraies -->
<script src="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/js/lightgallery-all.js"></script>
<!-- Page Specific JS File -->
<script src="<?php echo base_url()?>harta/morsip/assets/js/page/light-gallery.js"></script>

<!-- <script type="text/javascript">
    // Example for Change Password functionality
    function changePassword() {
        this.form_validation.set_rules('old_password', 'Password Lama', 'required');
        this.form_validation.set_rules('new_password', 'Password Baru', 'required|min_length[8]');
        this.form_validation.set_rules('confirm_password', 'Konfirmasi Password Baru', 'required|matches[new_password]');
        
        if (this.form_validation.run() == false) {
            this.session.set_flashdata('notif_password', validation_errors());
            redirect('landing/menu');
        } else {
            var username = this.session.user_data('useryyy');
            var old_password = this.input.post('old_password');
            var new_password = this.input.post('new_password');
            
            if (this.MUsers.update_password(username, old_password, new_password)) {
                this.session.set_flashdata('notif_password', 'Password berhasil diubah');
            } else {
                this.session.set_flashdata('notif_password', 'Password lama tidak sesuai');
            }
            redirect('landing/menu');
        }
    }
</script> -->
