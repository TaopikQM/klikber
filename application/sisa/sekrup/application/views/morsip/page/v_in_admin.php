
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/summernote/summernote-bs4.css">

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
            <h4>Input Admin</h4>
            
          </div>
          <div class="card-body">

            <?php $arrayName = array(
                  'class'=>"needs-validation",
                       'novalidate'=>'');
            echo form_open_multipart('morsip/save_admin',$arrayName);?>
             <div class="form-row">
              <div class="form-group col-md-3">
                  <?php
                      date_default_timezone_set("Asia/Jakarta");
                      $tgloi= date("Y-m-d");
                  ?>
                  <label for="user">Username</label>
                  <input type="text" class="form-control" name="user" id="user" required="required" value="<?php echo set_value('user'); ?>">
                   <?php  echo form_error('user'); ?>
                  <div class="invalid-feedback">
                        Silahkan Input Username
                  </div>
              </div>
              <div class="form-group col-md-3">
                <label>Pilih Subbag / Bidang</label>
                <select name="bid" class="form-control select2" required="required">
                      <option value="">Plih Subbag / Bidang</option>
                  <?php 
                    foreach ($bidang as $ky) {?>
                        <option <?php if ($ky->id ==  set_value('bid') ) { echo "selected=selected"; }?>
                        value="<?php echo $ky->id;?>"><?php echo $ky->n_bid;?></option>
                      
                    <?php
                    }
                  ?>
                </select>
                   <?php  echo form_error('bid'); ?>

                <div class="invalid-feedback">
                        Silahkan Pilih Subbag / Bidang
                  </div>
              </div>

              <div class="form-group col-md-3">
                <label>Pilih Hak Akses</label>
                <select name="hk" class="form-control select2" required="required">
                      <option value="">Hak Akses</option>
                      <option <?php if (1 ==  set_value('hk') ) { echo "selected=selected"; }?> value="1">Admin Biasa</option>
                      <option <?php if (0 ==  set_value('hk') ) { echo "selected=selected"; }?> value="0">Super Admin</option>
                  ?>
                </select>
                   <?php  echo form_error('hk'); ?>

                <div class="invalid-feedback">
                        Silahkan Pilih Hak Akses
                  </div>
              </div>
            </div>
            
             <div class="form-group">
              <label>Password</label>
              <input type="password" name="pass" placeholder="Masukan Password" class="form-control" required="required">
                   <?php  echo form_error('pass'); ?>
              <div class="invalid-feedback">
                    Silahkan Masukan Password
              </div>
            </div>
            
             <div class="form-group">
              <label>Password Confirm</label>
              <input type="password" name="passcom" placeholder="Masukan Password" class="form-control" required="required">
                   <?php  echo form_error('passcom'); ?>
              
              <div class="invalid-feedback">
                    Silahkan Masukan Password
              </div>
            </div>
            
            
            <div class="card-footer text-center">
              <button class="btn btn-primary mr-1" type="submit">Submit</button>
              <button class="btn btn-secondary" type="reset">Reset</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
