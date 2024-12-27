<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

<link href="<?php echo base_url()?>harta/morsip/assets/bundles/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
<!-- Template CSS -->
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/css/components.css">
<!-- Custom style CSS -->

<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<style>
    .input-group-append .btn {
        border: none;
        background: transparent;
        cursor: pointer;
    }

    .fa-eye, .fa-eye-slash {
        font-size: 1.2rem;
    }

    #password-match-alert, #password-mismatch-alert {
        margin-top: 5px;
        font-size: 0.9rem;
    }
</style>

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
            <h4>Edit Pass</h4>
          </div>
          <div class="card-body">
            <?php 
            // Menampilkan data dokter yang sudah ada
            foreach ($users as $key) {
              $id = $key->id;
              $username = $key->username;
              $password = $key->password;
              $id_role = $key->id_role;
            }

            // Form edit dokter
            echo form_open('landing/change_password', array('class' => 'needs-validation', 'novalidate' => '')); 
            ?>
             
            <!-- <div class="form-group">
                <label>Password Lama</label>
                <input type="password" name="password_lama" class="form-control" required>
            </div> -->
            <!-- <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password_baru" class="form-control" required>
            </div> -->
            <div class="form-group">
                <label>Password Lama</label>
                <div class="input-group">
                    <input type="password" name="password_lama" id="password_lama" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-light toggle-password" data-target="#password_lama">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <div class="input-group">
                    <input type="password" id="password_baru" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-light toggle-password" data-target="#password_baru">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <div class="input-group">
                    <input type="password" id="konfirmasi_password" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-light toggle-password" data-target="#konfirmasi_password">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="password-match-alert" class="text-success" style="display: none;">
                Password dan Konfirmasi Password cocok.
            </div>
            <div id="password-mismatch-alert" class="text-danger" style="display: none;">
                Password dan Konfirmasi Password tidak cocok.
            </div>


            <div class="card-footer text-center">
              <input type="hidden" name="id" value="<?php echo $id;?>">
              <input type="hidden" name="username" value="<?php echo $username;?>">
              <button class="btn btn-primary mr-1" type="submit" id="submit-button" disabled>Simpan</button>
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



    // Toggle visibility of password fields
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const target = document.querySelector(this.getAttribute('data-target'));
            const icon = this.querySelector('i');

            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Real-time password matching validation
    const passwordInput = document.getElementById('password_baru');
    const confirmPasswordInput = document.getElementById('konfirmasi_password');
    const matchAlert = document.getElementById('password-match-alert');
    const mismatchAlert = document.getElementById('password-mismatch-alert');

    function validatePasswords() {
      const submitButton = document.getElementById('submit-button');

        if (passwordInput.value === '' || confirmPasswordInput.value === '') {
            matchAlert.style.display = 'none';
            mismatchAlert.style.display = 'none';
            submitButton.disabled = true;
            return;
        }

        if (passwordInput.value === confirmPasswordInput.value) {
            matchAlert.style.display = 'block';
            mismatchAlert.style.display = 'none';
            submitButton.disabled = false;
        } else {
            matchAlert.style.display = 'none';
            mismatchAlert.style.display = 'block';
            submitButton.disabled = true;
        }
    }

    // Attach real-time validation to both fields
    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);
</script>
